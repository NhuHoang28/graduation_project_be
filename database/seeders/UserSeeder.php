<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        DB::table('user_info')->truncate();
        
        for ($i=0; $i < 10 ; $i++) {
            $faker = Faker::create();
            DB::table('users')->insert([
                'role_id' => ($i === 0) ? config('user.account_type_id.teacher') : config('user.account_type_id.student'),
                'username' => ($i === 0) ? "teacher" : "student{$i}",
                'password' => ($i === 0) ? \Hash::make('teacher') : \Hash::make("student{$i}"),
            ]);
            DB::table('user_info')->insert([
                'user_id' => $i+1,
                'fullname' => $faker->userName(),
                'phone_number' => $faker->phoneNumber(),
                'email' => $faker->email(),
                'date_of_birth' => $faker->date(),
                'gender' => array_rand(array_values(config('user.gender'))),
                'address' => $faker->address(),
                'description' => '',
            ]);
        }
    }
}
