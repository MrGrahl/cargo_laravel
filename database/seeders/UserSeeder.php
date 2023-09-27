<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'firstname' => 'Adrian',
            'lastname' => 'Grahl Maciel',
            'doc_type' => 'ci',
            'doc_number' => '6158932',
            'role' => 'driver',
            'email' => 'grahlmaciel@gmail.com',
            'password' => bcrypt('masterkey'),
            'phone1' => '0971200673',
            'phone2' => '',
            'company_id' => 1,
        ]);
        DB::table('drivers')->insert([
            'user_id' => 1,
            'running' => true,
        ]);
        DB::table('users')->insert([
            'firstname' => 'Adrian',
            'lastname' => 'Grahl Maciel',
            'doc_type' => 'ci',
            'doc_number' => '6158932',
            'role' => 'admin',
            'email' => 'adriangrahlmaciel@gmail.com',
            'password' => bcrypt('masterkey'),
            'phone1' => '0971200673',
            'phone2' => '',
            'company_id' => 1,
        ]);
    }
}
