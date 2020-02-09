<?php

use App\Profile;
use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $users = [
            [
                'email' => 'admin@example.com',
                'name' => 'Admin User',
                'phone' => '0111',
            ],
            [
                'email' => 'staff@example.com',
                'name' => 'Staff Account',
                'phone' => '0222',
            ],
            [
                'email' => 'subscriber@example.com',
                'name' => 'Subscriber Account',
                'phone' => '0333',
            ],
        ];

        foreach ($users as $user) {
            $userObj = User::updateOrCreate([
                'email' => $user['email']
            ], [
                'name' => $user['name'],
                'phone' => $user['phone'],
                'password' => bcrypt('123456'),
                'phone_verified_at' => now(),
            ]);

            $profile = new Profile();
            $profile->gender = $faker->randomElement(['male', 'female']);
            $profile->dob = $faker->date('Y-m-d');
            $profile->bio = $faker->realText(150);
            $profile->address = $faker->address;

            $userObj->profile()->save($profile);
        }
    }
}
