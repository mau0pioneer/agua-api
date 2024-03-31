<?php

namespace Database\Seeders;

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
        $this->call(CollectorsSeeder::class);
        $this->call(StreetsSeeder::class);
        $this->call(FoliosSeeder::class);
        $this->call(DwellingsSeeder::class);
        $this->call(PeriodsSeeder::class);
        $this->call(ContributionsSeeder::class);
        $this->call(CensoSeeder::class);
    }
}
