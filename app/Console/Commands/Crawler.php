<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CrawlerController;

class Crawler extends Command
{
    private $craw;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crawler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get data from dantri.vn page then insert to db';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CrawlerController $crawler)
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
        return $this->craw->getCrawler();
    }
}
