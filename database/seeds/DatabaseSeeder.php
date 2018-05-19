<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder 
{
    public function run()
    {
        $this->call(SpeakersSeeder::class);
        $this->command->info('Speakers table seeded!');
    }

}

