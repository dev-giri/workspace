<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder; 

class AppTestCommandSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        dd(\Hyn\Tenancy\Facades\TenancyFacade::website(),app(\Hyn\Tenancy\Environment::class)->tenant(),app(\Hyn\Tenancy\Environment::class)->hostname());
        
        
        
    }
}