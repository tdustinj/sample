<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BootstrapDatabaseOrderTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:bootstrapOrderTypes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bootstraps your environment\'s database with order types.';

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
        $orderTypes = array(
            'SHIP',
            'CARRY OUT',
            'THIRD PARTY FULFILLMENT',
            'INSTALL',
            'SUPPORT',
            'SERVICE',
            'DELIVERY',
        );

        foreach ($orderTypes as  $key => $orderTypeValue) {
            $orderType = new \App\Models\OrderType();
            $orderType->order_type_name = $orderTypeValue;
            try {
                $orderType->save();
            } catch (\Exception $e) {
                echo $e->getMessage() . "\n";
            }
        }
    }
}
