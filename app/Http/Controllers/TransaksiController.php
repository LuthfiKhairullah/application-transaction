<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Customer;
use App\Models\Sales;
use App\Models\SalesDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class TransaksiController extends Controller
{
    public function index()
    {
        return view('pages.transaksi.home', [
            'transactions' => Sales::joinAllTransaksi()->get()
        ]);
    }

    public function create()
    {
        $last_transaksi = Sales::max('kode');
        $no_transaksi = date('Ym-0001');
        if ($last_transaksi >= $no_transaksi) {
            $no_transaksi = date('Ym-' . sprintf('%04d', substr($last_transaksi, 7, (strlen($no_transaksi) - 1)) + 1));
        }
        $list_barang = Barang::where('status', 'active')->orderBy('kode', 'ASC')->get();
        $list_customer = Customer::where('status', 'active')->orderBy('kode', 'ASC')->get();
        return view('pages.transaksi.detail_transaksi', [
            'sales_id' => '',
            'sales' => [],
            'sales_det' => [],
            'no_transaksi' => $no_transaksi,
            'list_customer' => $list_customer,
            'list_barang' => $list_barang,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:15',
            'tanggal' => 'required|date',
            'customer' => 'required|string',
            'sub_total' => 'required|string',
            'total_bayar' => 'required|string',
        ]);

        $list_data = json_decode($request->input('tableData'), true);

        $sales = Sales::create([
            'kode' => $request->input('kode'),
            'tgl' => ($request->input('tanggal') != '') ? $request->input('tanggal') . date(' H:i:s') : date('Y-m-d H:i:s'),
            'cust_id' => $request->input('customer'),
            'subtotal' => str_replace(',', '', $request->input('sub_total')),
            'diskon' => str_replace(',', '', $request->input('diskon')),
            'ongkir' => str_replace(',', '', $request->input('ongkir')),
            'total_bayar' => str_replace(',', '', $request->input('total_bayar')),
        ]);

        $sales_id = $sales->id;
        foreach ($list_data as $data) {
            SalesDetail::create([
                'sales_id' => $sales_id,
                'barang_id' => $data['barang_id'],
                'harga_bandrol' => str_replace(',', '', $data['harga_bandrol']),
                'qty' => $data['qty'],
                'diskon_pct' => str_replace('%', '', $data['diskon_pct']),
                'diskon_nilai' => str_replace(',', '', $data['diskon_nilai']),
                'harga_diskon' => str_replace(',', '', $data['harga_diskon']),
                'total' => str_replace(',', '', $data['total']),
            ]);
        }

        return redirect()->route('transaksi.create')
            ->with('success', 'Note created successfully.');
    }

    public function show(string $id)
    {
        $sales = Sales::where('id', $id)->get();
        $sales_det = SalesDetail::joinBarang()->where('sales_id', $id)->get();
        if (count($sales) > 0) $customer = Customer::where('id', $sales[0]['cust_id'])->get();
        else $customer = [];
        $list_barang = Barang::where('status', 'active')->orderBy('kode', 'ASC')->get();
        $list_customer = Customer::where('status', 'active')->orderBy('kode', 'ASC')->get();
        return view('pages.transaksi.detail_transaksi', [
            'sales_id' => $id,
            'sales' => $sales,
            'sales_det' => $sales_det,
            'customer' => $customer,
            'list_customer' => $list_customer,
            'list_barang' => $list_barang,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $sales = Sales::findOrFail($id);
        $data = [
            'tgl' => $request->input('tanggal') . date(' H:i:s'),
            'cust_id' => $request->input('customer'),
            'subtotal' => str_replace(',', '', $request->input('sub_total')),
            'diskon' => str_replace(',', '', $request->input('diskon')),
            'ongkir' => str_replace(',', '', $request->input('ongkir')),
            'total_bayar' => str_replace(',', '', $request->input('total_bayar')),
        ];
        $sales->fill($data);
        $sales->save();

        $list_data = json_decode($request->input('tableData'), true);
        $all_sales_det = SalesDetail::where('sales_id', $id)->get();
        $arr_data = [];
        foreach ($list_data as $data) {
            if ($data['sales_det_id'] != '') {
                $arr_data[] = $data['sales_det_id'];
                $sales_det = SalesDetail::findOrFail($data['sales_det_id']);
                $data_details = [
                    'barang_id' => $data['barang_id'],
                    'harga_bandrol' => str_replace(',', '', $data['harga_bandrol']),
                    'qty' => $data['qty'],
                    'diskon_pct' => str_replace('%', '', $data['diskon_pct']),
                    'diskon_nilai' => str_replace(',', '', $data['diskon_nilai']),
                    'harga_diskon' => str_replace(',', '', $data['harga_diskon']),
                    'total' => str_replace(',', '', $data['total']),
                ];
                $sales_det->fill($data_details);
                $sales_det->save();
            } else {
                SalesDetail::create([
                    'sales_id' => $id,
                    'barang_id' => $data['barang_id'],
                    'harga_bandrol' => str_replace(',', '', $data['harga_bandrol']),
                    'qty' => $data['qty'],
                    'diskon_pct' => str_replace('%', '', $data['diskon_pct']),
                    'diskon_nilai' => str_replace(',', '', $data['diskon_nilai']),
                    'harga_diskon' => str_replace(',', '', $data['harga_diskon']),
                    'total' => str_replace(',', '', $data['total']),
                ]);
            }
        }

        foreach ($all_sales_det as $sales_d) {
            if (!in_array($sales_d['id'], $arr_data)) SalesDetail::destroy($sales_d['id']);
        }

        return redirect()->route('transaksi.show', ['id' => $id])
            ->with('success', 'Note created successfully.');
    }

    public function get_barang(Request $request)
    {
        $kode_barang = $request->input('kode_barang');

        $data_barang = Barang::where('kode', $kode_barang)->get();

        return json_encode($data_barang);
    }
}
