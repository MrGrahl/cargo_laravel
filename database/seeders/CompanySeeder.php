<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('companies')->insert([
            'name' => 'Soluntech',
            'industry' => '',
            'registration_number' => '6158932-8',
            'country' => 'Paraguay',
            'address'  => 'Ruta 14 KM2',
            'city' => 'Cambyretá',
            'state' => 'Itapúa',
            'postal_code' => '6000',
        ]);
    }
}
