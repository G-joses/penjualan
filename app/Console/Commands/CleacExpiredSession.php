<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CleanExpiredSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean expired user sessions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cleaned = User::cleanExpiredSessions();
        $this->info("Cleaned $cleaned expired sessions.");

        // Log ke file
        logger("Auto-cleaned $cleaned expired sessions at " . now());
    }
}
