<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;


class   UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        DB::table('users')->insert([
            'name' => 'Superadmin',
            'email' => 'Superadmin@example.com',
            'role' => 'admin',
            'manager_id' => 0,
            'password' => bcrypt('password'),

        ]);
        DB::table('users')->insert([
            'name' => 'manager',
            'email' => 'manager@example.com',
            'role' => 'manager',
            'manager_id' => 0,
            'password' => bcrypt('password'),

        ]); DB::table('leave_types')->insert([
            'name' => 'sick',
        ]);

        for ($i = 0; $i < 3; $i++) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->email,
                'role' => $faker->name,
                'manager_id' => 1,
                'password' => bcrypt($faker->password),

            ]);
        }
    }
}
