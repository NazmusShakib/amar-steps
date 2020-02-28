<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            RolesPermissionsTableSeeder::class,
        ]);

        \App\Models\BadgeUnit::updateOrCreate([
            'actual_name' => 'Walk steps'
        ], [
            'short_name' => 'Steps',
        ]);
        \App\Models\BadgeUnit::updateOrCreate([
            'actual_name' => 'Walk distance'
        ], [
            'short_name' => 'Distance',
        ]);


        Artisan::call('passport:install', ['--force' => true]);
    }
}
