<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBarangRequest;
use App\Models\Barang;

class BarangController extends Controller
{
    public function index()
    {
        return view('pages.master_barang.home', [
            'list_barang' => Barang::where('status', 'active')->orderBy('kode', 'ASC')->get()
        ]);
    }

    public function store(StoreBarangRequest $request)
    {
        $barang = Barang::where('kode', $request->input('kode_barang'))->where('status', 'active')->get();
        if (count($barang) == 0) {
            Barang::create([
                'kode' => $request->input('kode_barang'),
                'nama' => $request->input('nama_barang'),
                'harga' => $request->input('harga_barang'),
            ]);
        } else {
            return redirect()->route('master_barang.index')
                ->with('error', 'Kode Barang sudah digunakan.');
        }
        return redirect()->route('master_barang.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function update(StoreBarangRequest $request, string $id)
    {
        $kode_exists = Barang::where('kode', $request->input('kode_barang'))->where('id', '!=', $id)->where('status', 'active')->get();
        if (count($kode_exists) == 0) {
            $barang = Barang::findOrFail($id);
            $data = [
                'kode' => $request->input('kode_barang'),
                'nama' => $request->input('nama_barang'),
                'harga' => $request->input('harga_barang'),
            ];
            $barang->fill($data);
            $barang->save();
        } else {
            return redirect()->route('master_barang.index')
                ->with('error', 'Kode Barang sudah digunakan.');
        }
        return redirect()->route('master_barang.index')
            ->with('success', 'Barang berhasil diubah.');
    }

    public function destroy(string $id)
    {
        $barang = Barang::findOrFail($id);
        $data = [
            'status' => 'not active',
        ];
        $barang->fill($data);
        $barang->save();

        return redirect()->route('master_barang.index')
            ->with('success', 'Barang berhasil dihapus.');
    }
}
