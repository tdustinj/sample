<?php

namespace App\Console\Commands;
use Doctrine\DBAL\Driver\PDOException;
use Illuminate\Console\Command;
use App\Models\State;
use DB;


class TestStateMapFunction  extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:TestStateMapFunction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testing to see if we can get the state correct when it comes in strangly.';

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
    public function handle(){
        $tests = array('AZ.', 'AZ   ', 'colorado', 'north                car$olina', 'californa');

        foreach($tests as $test){
            // print_r("\n" . $test . "\n");
            $test = trim($test);
            // $test = trim($test, '.');
            // print_r("\n" . $test . "\n");

            $test = preg_replace("/[^A-Za-z0-9 ]/", '', $test);
            $test = preg_replace("/\s{2,}/", " ", $test);
            var_dump($test);
            print_r("\n" . $test . "\n");


            if (strlen($test) <= 2){
                $stateCode = strtoupper($test);
                $stateCode = State::where('state_code', '=', $stateCode)->first();
            } else {
                $stateCode = ucfirst($test);
                $stateCode = State::where('state_name', '=', $stateCode)->first();
            }
            

            if(isset($stateCode->state_code)){
               
            }else{
                $stateCode = State::where('state_name', 'like', $stateCode )->get();

            }
            print_r("\n" . $stateCode . "\n");
        }

        // print_r("\n" . $stateCode . "\n");
        // if (strlen($stateCode) <= 2){
        //     $stateCode = strtoupper($stateCode);
        //     $stateCode = State::where('state_code', '=', $stateCode)->first();
        // } else {
        //     $stateCode = ucfirst($stateCode);
        //     $stateCode = State::where('state_name', '=', $stateCode)->first();
        // }
        // if(isset($stateCode->state_code)){
        //     return $stateCode->state_code;
        // }
        // return $stateCode->state_code;
    }
}
