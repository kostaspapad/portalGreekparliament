<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder 
{
    public function run()
    {
        // Order is strict
        $this->call(SpeakersSeeder::class);
        $this->command->info('Speakers table seeded!');

        $this->call(PartiesTableSeeder::class);
        $this->command->info('Parties table seeded!');
        
        $this->call(ConferencesTableSeeder::class);
        $this->command->info('Conferences table seeded!');

        $this->call(MembershipsTableSeeder::class);
        $this->command->info('Memberships table seeded!');
        
        $this->command->info('------------------------');
        $this->command->info('Table seeding successful');
        $this->command->info('------------------------');
    }

}

