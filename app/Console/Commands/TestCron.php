<?php
// app/Console/Commands/TestCron.php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestCron extends Command
{
    protected $signature = 'test:cron';
    protected $description = 'Comando de teste para verificar o cron';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('O cron está funcionando corretamente.');
    }
}
