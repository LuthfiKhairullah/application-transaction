<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        return view('pages.master_customer.home', [
            'list_customer' => Customer::where('status', 'active')->orderBy('kode', 'ASC')->get()
        ]);
    }

    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::where('kode', $request->input('kode_customer'))->where('status', 'active')->get();
        if (count($customer) == 0) {
            Customer::create([
                'kode' => $request->input('kode_customer'),
                'name' => $request->input('name_customer'),
                'telp' => $request->input('telp_customer'),
            ]);
        } else {
            return redirect()->route('master_customer.index')
                ->with('error', 'Kode Customer sudah digunakan.');
        }
        return redirect()->route('master_customer.index')
            ->with('success', 'Customer berhasil ditambahkan.');
    }

    public function update(StoreCustomerRequest $request, string $id)
    {
        $kode_exists = Customer::where('kode', $request->input('kode_customer'))->where('id', '!=', $id)->where('status', 'active')->get();
        if (count($kode_exists) == 0) {
            $customer = Customer::findOrFail($id);
            $data = [
                'kode' => $request->input('kode_customer'),
                'name' => $request->input('name_customer'),
                'telp' => $request->input('telp_customer'),
            ];
            $customer->fill($data);
            $customer->save();
        } else {
            return redirect()->route('master_customer.index')
                ->with('error', 'Kode Customer sudah digunakan.');
        }
        return redirect()->route('master_customer.index')
            ->with('success', 'Customer berhasil diubah.');
    }

    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id);
        $data = [
            'status' => 'not active',
        ];
        $customer->fill($data);
        $customer->save();

        return redirect()->route('master_customer.index')
            ->with('success', 'Customer berhasil dihapus.');
    }
}
