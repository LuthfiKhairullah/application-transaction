@extends('template/layout')

@section('title')
Daftar Barang
@endsection

@section('content')
<div class="d-flex justify-content-between p-2">
  <h3>Daftar Barang</h3>
  <button type="button" class="btn btn-primary" id="btn_add" data-bs-toggle="modal" data-bs-target="#modal_barang">Tambah Barang</button>
</div>
@if (session('success'))
<div class="alert alert-success">
  {{ session('success') }}
</div>
@elseif (session('error'))
<div class="alert alert-danger">
  {{ session('error') }}
</div>
@endif
@if ($errors->any())
<div class="alert alert-danger">
  <ul>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif
<div class="mb-3">
  <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover nowrap" id="list_barang">
      <thead>
        <tr>
          <th class="text-center align-middle">No</th>
          <th class="text-center align-middle">Kode</th>
          <th class="text-center align-middle">Nama Barang</th>
          <th class="text-center align-middle">Harga</th>
          <th class="text-center align-middle">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($list_barang as $no => $barang) { ?>
          <tr>
            <td>{{ $no + 1 }}</td>
            <td>{{ $barang->kode }}</td>
            <td>{{ $barang->nama }}</td>
            <td>{{ number_format($barang->harga, 2) }}</td>
            <td class="text-center">
              <div class="d-flex justify-content-center">
                <button type="button" class="btn btn-sm btn-warning btn_update me-1" data-json='{{ json_encode($barang) }}' onclick="btn_update(this, <?= $barang['id'] ?>)">Ubah</button>
                <form action="{{ route('master_barang.destroy', $barang->id) }}" method="POST" id="form_delete_barang" onsubmit="return showConfirm()">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                </form>
              </div>
            </td>
          </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <tr>
          <th>No</th>
          <th>Kode</th>
          <th>Nama Barang</th>
          <th>Harga</th>
          <th></th>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
@endsection

@section('modals')
<div class="modal fade" id="modal_barang" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_barangLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <form class="form_update_barang" method="POST">
      @csrf
      <input type="hidden" name="_method" id="formMethod" value="POST">
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
              <input type="text" class="form-control" name="kode_barang" id="kode_barang" required>
              <input type="hidden" class="form-control" name="id_barang" id="id_barang">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-4">
              <label for="nama_barang">Nama Barang</label>
            </div>
            <div class="col-8">
              <input type="text" class="form-control" name="nama_barang" id="nama_barang" required>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-4">
              <label for="harga_barang">Harga</label>
            </div>
            <div class="col-8">
              <input type="text" class="form-control" name="harga_barang" id="harga_barang" oninput="inputNumber(this)" required>
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
    $('#list_barang').DataTable({
      responsive: true,
      initComplete: function() {
        this.api()
          .columns()
          .every(function(index) {
            if (index == 4) {
              return;
            }
            let column = this;
            let select = $('<select class="form-select select2"><option value=""></option></select>')
              .appendTo($(column.footer()).empty())
              .on('change', function() {
                let val = $.fn.dataTable.util.escapeRegex($(this).val());

                column.search(val ? '^' + val + '$' : '', true, false).draw();
              });

            column
              .data()
              .unique()
              .sort()
              .each(function(d, j) {
                select.append('<option value="' + d + '">' + d + '</option>');
              });
          });
      },
    });

    $('.select2').select2();
  });

  function inputNumber(element) {
    element.value = element.value.replace(/[^0-9.]/g, '');
  };

  function setFormMethod(method) {
    const formMethodField = document.querySelector('#formMethod');
    formMethodField.value = method;
  }

  $('#btn_add').on('click', function() {
    $('#modal_customerLabel').html('Tambah Barang');
    $('#btn_update_barang').html('Tambah');
    $('.form_update_barang').attr('action', "{{ route('master_barang.store') }}");
    setFormMethod('POST');
    $('.form_update_barang')[0].reset();
    $('#modal_barang').modal('show');
  });

  function btn_update(element, id) {
    let data = $(element).data('json');

    $('#modal_customerLabel').html('Ubah Barang');
    $('#btn_update_barang').html('Ubah');
    $('.form_update_barang').attr('action', `{{ url('/update_barang') }}/${id}`);
    setFormMethod('PUT');
    $('#kode_barang').val(data.kode);
    $('#nama_barang').val(data.nama);
    $('#harga_barang').val(data.harga);
    $('#modal_barang').modal('show');
  };

  function showConfirm(element) {
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
        document.querySelector('#form_delete_barang').submit();
      }
    });

    return false;
  }
</script>
@endsection