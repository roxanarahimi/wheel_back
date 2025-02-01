<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DailyUpdatePrizes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-update-prizes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        (new \App\Http\Controllers\DailyPrizeController())->refresh();
    }
}
