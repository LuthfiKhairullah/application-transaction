@extends('template/layout')

@section('title')
Transaksi
@endsection

@section('content')
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<form action="{{ url('/form_transaksi' . (($sales_id != '') ? '/' . $sales_id : '')) }}" method="POST" id="form_transaksi">
    @csrf
    <div class="bg-info mb-3 p-2">
        <h3>Transaksi</h3>
    </div>
    <div>
        <div class="row mb-3">
            <div class="col-4">
                <label for="kode">No</label>
            </div>
            <div class="col-8">
                <input type="text" class="form-control" name="kode" id="kode" value="{{ $sales[0]->kode ?? $no_transaksi }}" readonly>
                <input type="hidden" class="form-control" name="sales_id" id="sales_id" value="{{ $sales_id }}" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-4">
                <label for="tanggal">Tanggal</label>
            </div>
            <div class="col-8">
                <input type="date" class="form-control" name="tanggal" id="tanggal" value="{{ ($sales[0]->tgl ?? '') != '' ? date('Y-m-d', strtotime($sales[0]->tgl)) : '' }}" required>
            </div>
        </div>
    </div>
    <div class="bg-info mb-3 p-2">
        <h3>Customer</h3>
    </div>
    <div>
        <div class="row mb-3">
            <div class="col-4">
                <label for="cust_code">Kode</label>
            </div>
            <div class="col-4">
                <input type="text" class="form-control" name="cust_code" id="cust_code" value="{{ $customer[0]->kode ?? '' }}" readonly required>
                <input type="hidden" class="form-control" name="customer" id="customer" value="{{ $customer[0]->id ?? '' }}">
            </div>
            <div class="col-4">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_customer">
                    Choose
                </button>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-4">
                <label for="name">Nama</label>
            </div>
            <div class="col-8">
                <input type="text" class="form-control" name="name" id="name" value="{{ $customer[0]->name ?? '' }}" readonly required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-4">
                <label for="telp">Telp</label>
            </div>
            <div class="col-8">
                <input type="text" class="form-control" name="telp" id="telp" value="{{ $customer[0]->telp ?? '' }}" readonly required>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <div class="table-responsive">
            <table class="table table-bordered nowrap" id="list_transaksi">
                <thead>
                    <tr>
                        <th class="text-center align-middle text-nowrap" rowspan="2"><button type="button" class="btn btn-primary" id="btn_add">Tambah</button></th>
                        <th class="text-center align-middle text-nowrap" rowspan="2">No</th>
                        <th class="text-center align-middle text-nowrap" rowspan="2">Kode Barang</th>
                        <th class="text-center align-middle text-nowrap" rowspan="2">Nama Barang</th>
                        <th class="text-center align-middle text-nowrap" rowspan="2">Qty</th>
                        <th class="text-center align-middle text-nowrap" rowspan="2">Harga Barang</th>
                        <th class="text-center align-middle text-nowrap" colspan="2">Harga Bandrol</th>
                        <th class="text-center align-middle text-nowrap" rowspan="2">Harga Diskon</th>
                        <th class="text-center align-middle text-nowrap" rowspan="2">Total</th>
                    </tr>
                    <tr>
                        <th class="text-center align-middle text-nowrap">(%)</th>
                        <th class="text-center align-middle text-nowrap">(Rp)</th>
                    </tr>
                </thead>
                <tbody id="tbody_list_transaksi">
                    <?php foreach ($sales_det as $no => $sales_d) { ?>
                        <tr>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-warning btn_update" data-row="{{ $no }}" data-json='<?= json_encode($sales_d) ?>' onclick="btn_update(this)">Ubah</button>
                                <button type="button" class="btn btn-sm btn-danger" data-row="{{ $no }}" data-id="{{ $sales_d->id }}" onclick="showConfirm(this)">Hapus</button>
                                <input type="hidden" class="form-control" value="{{ $sales_d->id }}" id="sales_det_id">
                            </td>
                            <td class="text-center">{{ $no + 1 }}</td>
                            <td>{{ $sales_d->barang_kode }}</td>
                            <td>{{ $sales_d->barang_nama }}</td>
                            <td class="text-end">{{ $sales_d->qty }}</td>
                            <td class="text-end">{{ number_format($sales_d->harga_bandrol, 2) }}</td>
                            <td class="text-end">{{ $sales_d->diskon_pct }}%</td>
                            <td class="text-end">{{ ($sales_d->diskon_nilai != 0) ? number_format($sales_d->diskon_nilai, 2) : '-' }}</td>
                            <td class="text-end">{{ number_format($sales_d->harga_diskon, 2) }}</td>
                            <td class="text-end">{{ number_format($sales_d->total, 2) }}</td>
                            <input type="hidden" class="form-control" id="barang_id" value="{{ $sales_d->barang_id }}">
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="8"></td>
                        <td class="fw-bold">Sub Total</td>
                        <td class="fw-bold"><input type="text" class="form-control text-end" style="min-width: 150px" name="sub_total" id="sub_total" value="{{ number_format($sales[0]->subtotal ?? 0, 2) }}" readonly></td>
                    </tr>
                    <tr>
                        <td colspan="8"></td>
                        <td class="fw-bold">Diskon</td>
                        <td class="fw-bold"><input type="text" class="form-control text-end" style="min-width: 150px" name="diskon" id="diskon" value="{{ number_format($sales[0]->diskon ?? 0, 2) }}" oninput="autoCalculate(this)" onchange="change_input(this)"></td>
                    </tr>
                    <tr>
                        <td colspan="8"></td>
                        <td class="fw-bold">Ongkir</td>
                        <td class="fw-bold"><input type="text" class="form-control text-end" style="min-width: 150px" name="ongkir" id="ongkir" value="{{ number_format($sales[0]->ongkir ?? 0, 2) }}" oninput="autoCalculate(this)" onchange="change_input(this)"></td>
                    </tr>
                    <tr>
                        <td colspan="8"></td>
                        <td class="fw-bold">Total Bayar</td>
                        <td class="fw-bold"><input type="text" class="form-control text-end" style="min-width: 150px" name="total_bayar" id="total_bayar" value="{{ number_format($sales[0]->total_bayar ?? 0, 2) }}" readonly></td>
                    </tr>
                </tfoot>
            </table>
            <input type="hidden" id="tableData" name="tableData">
        </div>
    </div>
    <div class="text-center">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ url('/transaksi') }}" class="btn btn-secondary ms-4">Kembali</a>
    </div>
</form>
@endsection

@section('modals')
<div class="modal fade" id="modal_customer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_customerLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_customerLabel">List Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="list_customer">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Telp</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list_customer as $index => $customer)
                            <tr>
                                <td>{{ $customer->kode }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->telp }}</td>
                                <td><button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal" onclick="pick_customer(<?= $index + 1 ?>, <?= $customer->id ?>)">Choose</button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_barang" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_barangLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <form class="form_update_barang">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_barangLabel">List Barang</h5>
                    <button type="rest" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="kode_barang">Kode Barang</label>
                        </div>
                        <div class="col-8">
                            <select name="kode_barang" id="kode_barang" class="form-select" style="width: 100%;" required>
                                <option value="">-- Pilih Kode Barang --</option>
                                @foreach ($list_barang as $index => $barang)
                                <option value="<?= $barang->kode ?>">{{ $barang->kode }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" class="form-control" name="id_barang" id="id_barang">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="nama_barang">Nama Barang</label>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form-control" name="nama_barang" id="nama_barang" readonly required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="harga_barang">Harga</label>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form-control" name="harga_barang" id="harga_barang" readonly required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="qty_barang">Qty</label>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form-control" name="qty_barang" id="qty_barang" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="diskon_barang">Diskon (%)</label>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form-control" name="diskon_barang" id="diskon_barang">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btn_update_barang"></button>
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $('#kode_barang').select2({
            dropdownParent: $('#modal_barang')
        });

        set_number_and_total();
    });

    let selectedOption = [];

    $('#btn_add').on('click', function() {
        $('#btn_update_barang').html('Tambah');
        $('#btn_update_barang').attr('data-row', '');
        $('.form_update_barang').attr('onsubmit', 'add_barang(event)');
        $('.form_update_barang')[0].reset();
        $('#kode_barang').val('').trigger('change');
        $('#modal_barang').modal('show');
    });

    function btn_update(element) {
        let data = $(element).data('json');
        let row = $(element).data('row');

        $('#btn_update_barang').html('Ubah');
        $('#btn_update_barang').attr('data-row', row);
        $('.form_update_barang').attr('onsubmit', `update_barang(event, ${data.id})`);
        $('#kode_barang').val(data.barang_kode).trigger('change');
        $('#nama_barang').val(data.barang_nama);
        $('#harga_barang').val(data.harga_bandrol);
        $('#qty_barang').val(data.qty);
        $('#diskon_barang').val(data.diskon_pct);
        $('#modal_barang').modal('show');
    };

    function showConfirm(element) {
        let row = $(element).data('row');
        let id = $(element).data('id');
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then(async (result) => {
            if (result.isConfirmed) {
                $(element).closest('tr').remove();
                set_number_and_total();
                Swal.fire({
                    title: "Deleted!",
                    text: "Your file has been deleted.",
                    icon: "success"
                });
            }
        });
    }

    function number_format(number) {
        return number.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function formattedNumber(number) {
        return number.replace(/,/g, '');
    }

    function set_number_and_total() {
        let tbody = document.getElementById('tbody_list_transaksi');
        let total = 0;
        for (let i = 0; i < tbody.rows.length; i++) {
            let btnUpdate = tbody.rows[i].cells[0].querySelector('.btn_update');
            if (btnUpdate) {
                btnUpdate.setAttribute('data-row', i);
            }
            let btnDelete = tbody.rows[i].cells[0].querySelector('.btn_delete');
            if (btnDelete) {
                btnDelete.setAttribute('data-row', i);
            }
            tbody.rows[i].cells[1].textContent = i + 1;
            total += parseFloat(formattedNumber(tbody.rows[i].cells[9].textContent));
        }
        $('#sub_total').val(number_format(total));
        jumlah_bayar();
    }

    function inputNumber(element) {
        element.value = element.value.replace(/[^0-9]/g, '');
    };

    function autoCalculate(element) {
        inputNumber(element);
        jumlah_bayar();
    }

    function change_input(element) {
        element.value = number_format(parseFloat(element.value));
        jumlah_bayar();
    }

    function jumlah_bayar() {
        let sub_total = formattedNumber($('#sub_total').val());
        let diskon = formattedNumber($('#diskon').val());
        let ongkir = formattedNumber($('#ongkir').val());
        if (sub_total == '') sub_total = 0;
        if (diskon == '') diskon = 0;
        if (ongkir == '') ongkir = 0;
        let total_bayar = parseFloat(sub_total) - parseFloat(diskon) + parseFloat(ongkir);
        $('#total_bayar').val(number_format(total_bayar));
    }

    function pick_customer(row, id) {
        let table = document.getElementById('list_customer');
        let rows = table.rows[row];
        $('#cust_code').val(rows.cells[0].textContent);
        $('#name').val(rows.cells[1].textContent);
        $('#telp').val(rows.cells[2].textContent);
        $('#customer').val(id);
    }

    $('#kode_barang').change((e) => {
        $.ajax({
            url: "<?= url('/get_barang') ?>",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            data: {
                kode_barang: e.target.value,
            },
            dataType: "json",
            success: function(data) {
                if (data.length > 0) {
                    $('#nama_barang').val(data[0].nama);
                    $('#harga_barang').val(data[0].harga);
                    $('#id_barang').val(data[0].id);
                }
            }
        });
    });

    function add_barang(event) {
        event.preventDefault();
        let barang_id = $('#id_barang').val();
        let barang_kode = $('#kode_barang').val();
        let barang_nama = $('#nama_barang').val();
        let qty = $('#qty_barang').val();
        if (qty == '') qty = 0;
        let = harga_barang = $('#harga_barang').val();
        let persentase_diskon = $('#diskon_barang').val();
        if (persentase_diskon == '') persentase_diskon = 0;
        console.log(harga_barang * (persentase_diskon / 100));
        let diskon = harga_barang * (persentase_diskon / 100);
        let harga_diskon = harga_barang - diskon;
        let total = harga_diskon * qty;

        let data = {
            barang_id: barang_id,
            harga_bandrol: parseFloat(harga_barang),
            qty: parseInt(qty),
            diskon_pct: parseInt(persentase_diskon),
            diskon_nilai: parseFloat(diskon),
            harga_diskon: parseFloat(harga_diskon),
            total: parseFloat(total),
            barang_nama: barang_nama,
            barang_kode: barang_kode,
        }

        if (diskon == 0) diskon = '-';
        else diskon = number_format(parseFloat(diskon));

        let newRowHtml = `
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-warning btn_update" onclick="btn_update(this)" data-json='${JSON.stringify(data)}'>Ubah</button>
                <button type="button" class="btn btn-sm btn-danger btn_delete" onclick="btn_delete(this)">Hapus</button>
                <input type="hidden" class="form-control" value="" id="sales_det_id">
            </td>
            <td class="text-center"></td>
            <td>${$('#kode_barang').val()}</td>
            <td>${$('#nama_barang').val()}</td>
            <td class="text-end">${qty}</td>
            <td class="text-end">${number_format(parseFloat(harga_barang))}</td>
            <td class="text-end">${persentase_diskon}%</td>
            <td class="text-end">${diskon}</td>
            <td class="text-end">${number_format(parseFloat(harga_diskon))}</td>
            <td class="text-end">${number_format(total)}</td>
            <input type="hidden" class="form-control" id="barang_id" value="${barang_id}">
        `;

        let newRow = document.createElement('tr');
        newRow.innerHTML = newRowHtml;

        $('#tbody_list_transaksi').append(newRow);

        set_number_and_total();
        $('#modal_barang').modal('hide');
    };

    function update_barang(event, id) {
        event.preventDefault();
        let rowIndex = $('#btn_update_barang').data('row');
        let rows = document.querySelectorAll('#tbody_list_transaksi tr');
        let barang_id = $('#id_barang').val();
        let barang_kode = $('#kode_barang').val();
        let barang_nama = $('#nama_barang').val();
        let qty = $('#qty_barang').val();
        if (qty == '') qty = 0;
        let = harga_barang = $('#harga_barang').val();
        let persentase_diskon = $('#diskon_barang').val();
        if (persentase_diskon == '') persentase_diskon = 0;
        let diskon = harga_barang * (persentase_diskon / 100);
        let harga_diskon = harga_barang - diskon;
        let total = harga_diskon * qty;

        let data = {
            id: id,
            barang_id: barang_id,
            harga_bandrol: parseFloat(harga_barang),
            qty: parseInt(qty),
            diskon_pct: parseInt(persentase_diskon),
            diskon_nilai: parseFloat(diskon),
            harga_diskon: parseFloat(harga_diskon),
            total: parseFloat(total),
            barang_nama: barang_nama,
            barang_kode: barang_kode,
        }

        if (diskon == 0) diskon = '-';
        else diskon = number_format(parseFloat(diskon));

        let newRowHtml = `
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-warning btn_update" onclick="btn_update(this)" data-json='${JSON.stringify(data)}'>Ubah</button>
                <button type="button" class="btn btn-sm btn-danger btn_delete" onclick="btn_delete(this)">Hapus</button>
                <input type="hidden" class="form-control" value="${id}" id="sales_det_id">
            </td>
            <td class="text-center"></td>
            <td>${barang_kode}</td>
            <td>${barang_nama}</td>
            <td class="text-end">${qty}</td>
            <td class="text-end">${number_format(parseFloat(harga_barang))}</td>
            <td class="text-end">${persentase_diskon}%</td>
            <td class="text-end">${diskon}</td>
            <td class="text-end">${number_format(parseFloat(harga_diskon))}</td>
            <td class="text-end">${number_format(total)}</td>
            <input type="hidden" class="form-control" id="barang_id" value="${barang_id}">
        `;

        let newRow = document.createElement('tr');
        newRow.innerHTML = newRowHtml;

        rows[rowIndex].parentNode.replaceChild(newRow, rows[rowIndex]);

        set_number_and_total();
        $('#modal_barang').modal('hide');
    };

    $('#form_transaksi').submit(function(event) {
        let tableData = [];
        $('#tbody_list_transaksi tr').each(function() {
            let row = {};
            $(this).find('td').each(function(index, td) {
                if (index == 4) row['qty'] = $(td).text();
                else if (index == 5) row['harga_bandrol'] = $(td).text();
                else if (index == 6) row['diskon_pct'] = $(td).text();
                else if (index == 7) {
                    let diskon_nilai = '';
                    if ($(td).text() == '-') diskon_nilai = 0;
                    else diskon_nilai = $(td).text();
                    row['diskon_nilai'] = diskon_nilai;
                } else if (index == 8) row['harga_diskon'] = $(td).text();
                else if (index == 9) row['total'] = $(td).text();
            });
            row['sales_det_id'] = $(this).find('input[type="hidden"][id="sales_det_id"]').val();
            row['barang_id'] = $(this).find('input[type="hidden"][id="barang_id"]').val();

            tableData.push(row);
        });
        $('#tableData').val(JSON.stringify(tableData));
    });
</script>
@endsection