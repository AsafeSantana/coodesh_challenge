<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StatusController extends Controller
{
    public function status()
    {
        // Checar a conexão com o banco de dados
        $dbStatus = $this->checkDatabaseConnection();

        // Horário da última execução do CRON
        $lastCronRun = $this->getLastCronRunTime();

        // Tempo online
        $uptime = $this->getUptime();

        // Uso de memória
        $memoryUsage = $this->getMemoryUsage();

        return response()->json([
            'database' => $dbStatus,
            'last_cron_run' => $lastCronRun,
            'uptime' => $uptime,
            'memory_usage' => $memoryUsage,
        ]);
    }

    private function checkDatabaseConnection()
    {
        try {
            DB::connection()->getPdo();
            return 'OK';
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    private function getLastCronRunTime()
    {
        
        return Cache::get('last_cron_run', 'Unknown');
    }

    private function getUptime()
    {
        $startTime = Cache::get('app_start_time', now());
        $uptime = now()->diff($startTime)->format('%d dias, %h horas, %i minutos');
        return ['uptime' => $uptime];
    }

    private function getMemoryUsage()
    {
        return [
            'memory_usage' => round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB',
            'memory_peak_usage' => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB',
        ];
    }
}
