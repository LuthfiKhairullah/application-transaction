<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sales extends Model
{
    use HasFactory;
    protected $table = 't_sales';
    protected $fillable = ['kode', 'tgl', 'cust_id', 'subtotal', 'diskon', 'ongkir', 'total_bayar'];

    public function scopeJoinAllTransaksi($query)
    {
        return $query->join('m_customer', 'm_customer.id', '=', 't_sales.cust_id')
            ->join('t_sales_det', 't_sales_det.sales_id', '=', 't_sales.id')
            ->select('t_sales.*', 'm_customer.name as cust_name', DB::raw('SUM(t_sales_det.qty) as total_barang'))
            ->groupBy('id', 'kode', 'tgl', 'cust_id', 'subtotal', 'diskon', 'ongkir', 'total_bayar', 'cust_name', 'created_at', 'updated_at')
            ->orderBy('kode', 'ASC');
    }
}
