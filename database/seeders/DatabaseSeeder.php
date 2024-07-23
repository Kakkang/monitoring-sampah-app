<?php

use Database\Seeders\IndoRegionDistrictSeeder;
use Database\Seeders\IndoRegionProvinceSeeder;
use Database\Seeders\IndoRegionRegencySeeder;
use Database\Seeders\IndoRegionSeeder;
use Database\Seeders\IndoRegionVillageSeeder;
use Database\Seeders\UsersSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersSeeder::class);
        $this->call(IndoRegionSeeder::class);
    }
}