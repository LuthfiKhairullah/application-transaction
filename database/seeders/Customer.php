<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Customer extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_customer')->insert([
            [
                'kode' => 'AA001',
                'name' => 'Cust A',
                'telp' => '081251231232',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'AA025',
                'name' => 'Cust B',
                'telp' => '081251231234',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'AA002',
                'name' => 'Cust C',
                'telp' => '081251231235',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
