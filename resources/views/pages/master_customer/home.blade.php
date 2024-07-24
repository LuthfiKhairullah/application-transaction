@extends('template/layout')

@section('title')
Daftar Customer
@endsection

@section('content')
<div class="d-flex justify-content-between p-2">
  <h3>Daftar Customer</h3>
  <button type="button" class="btn btn-primary" id="btn_add" data-bs-toggle="modal" data-bs-target="#modal_customer">Tambah Customer</button>
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
    <table class="table table-bordered table-striped table-hover nowrap" id="list_customer">
      <thead>
        <tr>
          <th class="text-center align-middle">No</th>
          <th class="text-center align-middle">Kode</th>
          <th class="text-center align-middle">Nama Customer</th>
          <th class="text-center align-middle">No Telp</th>
          <th class="text-center align-middle">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($list_customer as $no => $customer) { ?>
          <tr>
            <td>{{ $no + 1 }}</td>
            <td>{{ $customer->kode }}</td>
            <td>{{ $customer->name }}</td>
            <td>{{ $customer->telp }}</td>
            <td class="text-center">
              <div class="d-flex justify-content-center">
                <button type="button" class="btn btn-sm btn-warning btn_update me-1" data-json='{{ json_encode($customer) }}' onclick="btn_update(this, <?= $customer['id'] ?>)">Ubah</button>
                <form action="{{ secure_url('/delete_customer/' . $customer->id) }}" method="POST" id="form_delete_customer" onsubmit="return showConfirm()">
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
          <th>Nama Customer</th>
          <th>No Telp</th>
          <th></th>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
@endsection

@section('modals')
<div class="modal fade" id="modal_customer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_customerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <form class="form_update_customer" method="POST">
      @csrf
      <input type="hidden" name="_method" id="formMethod" value="POST">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal_customerLabel"></h5>
          <button type="rest" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-4">
              <label for="kode_customer">Kode Customer</label>
            </div>
            <div class="col-8">
              <input type="text" class="form-control" name="kode_customer" id="kode_customer" required>
              <input type="hidden" class="form-control" name="id_customer" id="id_customer">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-4">
              <label for="name_customer">Nama Customer</label>
            </div>
            <div class="col-8">
              <input type="text" class="form-control" name="name_customer" id="name_customer" required>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-4">
              <label for="telp_customer">No Telp</label>
            </div>
            <div class="col-8">
              <input type="text" class="form-control" name="telp_customer" id="telp_customer" oninput="inputNumber(this)" required>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="btn_update_customer"></button>
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
    $('#list_customer').DataTable({
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
    element.value = element.value.replace(/[^0-9]/g, '');
  };

  function setFormMethod(method) {
    const formMethodField = document.querySelector('#formMethod');
    formMethodField.value = method;
  }

  $('#btn_add').on('click', function() {
    $('#modal_customerLabel').html('Tambah Customer');
    $('#btn_update_customer').html('Tambah');
    $('.form_update_customer').attr('action', "{{ secure_url('/add_customer') }}");
    setFormMethod('POST');
    $('.form_update_customer')[0].reset();
    $('#modal_customer').modal('show');
  });

  function btn_update(element, id) {
    let data = $(element).data('json');

    $('#modal_customerLabel').html('Ubah Customer');
    $('#btn_update_customer').html('Ubah');
    $('.form_update_customer').attr('action', `{{ secure_url('/update_customer') }}/${id}`);
    setFormMethod('PUT');
    $('#kode_customer').val(data.kode);
    $('#name_customer').val(data.name);
    $('#telp_customer').val(data.telp);
    $('#modal_customer').modal('show');
  };

  function showConfirm() {
    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, delete it!"
    }).then((result) => {
      if (result.isConfirmed) {
        document.querySelector('#form_delete_customer').submit();
      }
    });

    return false;
  }
</script>
@endsection