<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ImportFoodFacts extends Command
{
    protected $signature = 'import:foodfacts';
    protected $description = 'Importa dados do Open Food Facts e salva no banco de dados';

    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $files = [
            'products_01.json.gz',
            'products_02.json.gz',
            'products_03.json.gz',
            'products_04.json.gz',
            'products_05.json.gz',
            'products_06.json.gz',
            'products_07.json.gz',
            'products_08.json.gz',
            'products_09.json.gz'

        ];

        foreach ($files as $file) {
            $this->info("Importando: {$file}");

            $url = "https://challenges.coode.sh/food/data/json/{$file}";
            $response = Http::get($url);

            if ($response->successful()) {
                $this->info("Arquivo {$file} baixado com sucesso.");
                $this->processFile($response->body());
            } else {
                $this->error("Falha ao obter o arquivo: {$file}");
                Log::error("Erro ao baixar o arquivo {$file} - Status: {$response->status()}");
            }
        }


        Cache::put('last_cron_run', now());
        $this->info('Cache atualizado com o horário da última importação.');
    }

    private function processFile($content)
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'foodfacts');
        file_put_contents($tempFile, $content);

        $gzipStream = gzopen($tempFile, 'rb');
        if ($gzipStream === false) {
            $this->error("Falha ao abrir o arquivo compactado");
            unlink($tempFile);
            return;
        }

        $batchSize = 100;
        $bufferSize = 1048576;
        $buffer = '';
        $products = [];
        $productCount = 0;
        $this->info('Processando dados...');

        while (!gzeof($gzipStream) && $productCount < $batchSize) {
            $buffer .= gzread($gzipStream, $bufferSize);

            while (true) {
                $jsonStart = strpos($buffer, '{');
                $jsonEnd = strpos($buffer, '}', $jsonStart);

                if ($jsonStart === false || $jsonEnd === false) {
                    break;
                }

                $jsonString = substr($buffer, $jsonStart, $jsonEnd - $jsonStart + 1);
                $buffer = substr($buffer, $jsonEnd + 1);

                $productData = json_decode($jsonString, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $products[] = $productData;
                    $productCount++;

                    if (count($products) >= $batchSize || $productCount >= $batchSize) {
                        $this->processBatch($products);
                        $products = [];


                        if ($productCount >= $batchSize) {
                            break 2;
                        }
                    }
                }
            }
        }


        if (count($products) > 0) {
            $this->processBatch($products);
        }

        gzclose($gzipStream);
        unlink($tempFile);



        $this->info('Importação concluída.');
    }

    private function processBatch(array $products)
    {
        foreach ($products as $productData) {
            $productData['code'] = str_replace(['"', "'"], '', $productData['code']);
            $productData['url'] = substr($productData['url'], 0, 255);
            $productData['image_url'] = substr($productData['image_url'], 0, 255);

            $productData['created_t'] = date('Y-m-d H:i:s', $productData['created_t']);
            $productData['last_modified_t'] = date('Y-m-d H:i:s', $productData['last_modified_t']);

            Product::updateOrCreate(
                ['code' => $productData['code']],
                array_merge($productData, [
                    'imported_t' => now(),
                    'status' => 'published',
                ])
            );
        }

        $this->info(count($products) . ' produtos processados com sucesso.');
    }
}
