<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesDetail extends Model
{
    use HasFactory;
    protected $table = 't_sales_det';
    protected $fillable = ['id', 'sales_id', 'barang_id', 'harga_bandrol', 'qty', 'diskon_pct', 'diskon_nilai', 'harga_diskon', 'total'];

    public function scopeJoinBarang($query)
    {
        return $query->join('m_barang', 'm_barang.id', '=', 't_sales_det.barang_id')
            ->select('t_sales_det.*', 'm_barang.nama as barang_nama', 'm_barang.kode as barang_kode');
    }
}
