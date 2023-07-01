<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role')->truncate();
        
        $roles = [
            'student',
            'teacher'
        ];
        foreach ($roles as $role) {
            DB::table('role')->insert(
                ['role_name' => $role]
            );
        }
    }
}
