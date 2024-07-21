<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Jobs\ColasJob;
use App\Services\TestService;

class ProgrammerTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:programmer-task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba de Scheduler';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info("ENTRE AL SCHEDULER");
        TestService::procesarFilesPendientes();

        //
    }
}
