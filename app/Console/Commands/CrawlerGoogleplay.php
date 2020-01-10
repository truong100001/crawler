<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CrawlerGoogleplayController;

class CrawlerGoogleplay extends Command
{
    private $craw;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler-googleplay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CrawlerGoogleplayController $crawler)
    {
        parent::__construct();
        $this->craw = $crawler;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->craw->craw();
    }
}
