<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder 
{
    public function run()
    {
        $this->call(SpeakersSeeder::class);
        $this->command->info('Speakers table seeded!');

        $this->call(ConferencesTableSeeder::class);
        $this->command->info('Conferences table seeded!');

        $this->call(MembershipsTableSeeder::class);
        $this->command->info('Memberships table seeded!');
        
        
        
        // $this->call(PartiesTableSeeder::class);
        // $this->command->info('Parties table seeded!');
    }

}

