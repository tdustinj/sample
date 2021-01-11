<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        ============================================= NOTE =============================================
            Seeds should be used for putting mock data into our database, not for
            inserting data needed for production (such as fulfillment types, locations etc).
            If you would like to add bootstrapping of actual needed production data,
            build a Command class and add it to the array of commands to call in "BootstrapDatabase".
        ================================================================================================
        */

        // Syntax for calling seeders:
        // $this->call(SomeSeederClass::class);
    }
}
