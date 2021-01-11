<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Elasticsearch;

class ElasticInitialLoad extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:elastic-load';

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
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $client = Elasticsearch\ClientBuilder::create()->build();

    }
}
