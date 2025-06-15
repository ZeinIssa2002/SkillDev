<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\AuthController;

class CleanupGuestAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accounts:cleanup-guests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up guest accounts older than 30 seconds';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Cleaning up old guest accounts...');
        AuthController::cleanupGuestAccounts();
        $this->info('Guest accounts cleanup completed!');
        return 0;
    }
}
