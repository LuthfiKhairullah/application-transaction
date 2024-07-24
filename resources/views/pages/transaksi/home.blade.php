@extends('template/layout')

@section('title')
Daftar Transaksi
@endsection

@section('content')
<div class="d-flex justify-content-between p-2">
  <h3>Daftar Transaksi</h3>
  <a href="{{ secure_url('/form_transaksi') }}" class="btn btn-md btn-primary">Tambah Transaksi</a>
</div>
<div class="mb-3">
  <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover nowrap" id="list_transactions">
      <thead>
        <tr>
          <th class="text-center align-middle">No</th>
          <th class="text-center align-middle">No Transaksi</th>
          <th class="text-center align-middle">Tanggal</th>
          <th class="text-center align-middle">Nama Customer</th>
          <th class="text-center align-middle">Jumlah Barang</th>
          <th class="text-center align-middle">Sub Total</th>
          <th class="text-center align-middle">Diskon</th>
          <th class="text-center align-middle">Ongkir</th>
          <th class="text-center align-middle">Total</th>
        </tr>
      </thead>
      <tbody>
        <?php $grand_total = 0;
        foreach ($transactions as $no => $transaction) {
          $grand_total += $transaction['total_bayar'] ?>
          <tr>
            <td>{{ $no + 1 }}</td>
            <td style="cursor: pointer;" onclick="location.href='<?= secure_url('/form_transaksi/' . $transaction['id']) ?>'">{{ $transaction->kode }}</td>
            <td style="cursor: pointer;" onclick="location.href='<?= secure_url('/form_transaksi/' . $transaction['id']) ?>'">{{ date('Y-m-d', strtotime($transaction->tgl)) }}</td>
            <td style="cursor: pointer;" onclick="location.href='<?= secure_url('/form_transaksi/' . $transaction['id']) ?>'">{{ $transaction->cust_name }}</td>
            <td style="cursor: pointer;" onclick="location.href='<?= secure_url('/form_transaksi/' . $transaction['id']) ?>'">{{ $transaction->total_barang }}</td>
            <td style="cursor: pointer;" onclick="location.href='<?= secure_url('/form_transaksi/' . $transaction['id']) ?>'">{{ number_format($transaction->subtotal, 2) }}</td>
            <td style="cursor: pointer;" onclick="location.href='<?= secure_url('/form_transaksi/' . $transaction['id']) ?>'">{{ $transaction->diskon > 0 ? number_format($transaction->diskon, 2) : '' }}</td>
            <td style="cursor: pointer;" onclick="location.href='<?= secure_url('/form_transaksi/' . $transaction['id']) ?>'">{{ $transaction->ongkir > 0 ? number_format($transaction->ongkir, 2) : '' }}</td>
            <td style="cursor: pointer;" onclick="location.href='<?= secure_url('/form_transaksi/' . $transaction['id']) ?>'">{{ number_format($transaction->total_bayar, 2) }}</td>
          </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <tr>
          <td class="text-center align-middle fw-bold" colspan="8">Grand Total</td>
          <td class="fw-bold">{{ number_format($grand_total, 2) }}</td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
@endsection

@section('scripts')
<script>
  $('#list_transactions').DataTable({
    responsive: true,
    columnDefs: [{
      responsivePriority: 1,
      targets: 8
    }]
  });
</script>
@endsection