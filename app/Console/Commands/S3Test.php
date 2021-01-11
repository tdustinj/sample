<?php

namespace App\Console\Commands;




use Illuminate\Console\Command;


class S3Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:testS3';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test S3';

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

    //

    public function handle()
    {

        $my_file = 'file3.txt';
        $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
        $data = 'Test data to see if this works! 2';
        fwrite($handle, $data);

        $storagePath = \Storage::disk('s3')->put( 'test3.txt', $data);
        //print_r($storagePath);
        $contents = \Storage::disk('s3')->get('test3.txt');
        print_r($contents);

    }


}
