<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('orders')->insert([
            'company_id' => 1,
            'created_by' => 2,
            'delivered_at' => Carbon::now(),
            'driver_id' => 1,
            // 'client_id',
            'order_cost' => 15000,
            'delivery_cost' => 73000,
            'description' => '',
            'status' => 'pending',
            'client_name' => 'Natalia Delgado',
            'client_phone' => '0971331257',
            'client_address' => 'Enc. Rotonda Boquerón. Rta 6',
            'client_latitude' => '-27.325748',
            'client_longitude' => '-55.859371',
        ]);
    }
}
