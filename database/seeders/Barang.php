<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Barang extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_barang')->insert([
            [
                'kode' => 'A001',
                'nama' => 'Barang A',
                'harga' => 300000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'C025',
                'nama' => 'Barang B',
                'harga' => 250000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'A002',
                'nama' => 'Barang C',
                'harga' => 200000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
