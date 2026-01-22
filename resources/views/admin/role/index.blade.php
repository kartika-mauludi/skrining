@extends('admin.layout.index')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Master Role</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
              <li class="breadcrumb-item active">Master Role</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"></h3>
                 <button class="btn btn-success p-1" id="add"  data-toggle="modal" data-target="#addData"> Tambah </button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="tbl-role" class="table table-bordered table-striped">
                 <thead>
                  <tr>
                    <th>No</th>
                    <th>Role</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
          
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>No</th>
                    <th>Role</th>
                    <th>Action</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
        </div>
      </div>
    </div>
</section>
</div>

 @include('admin.role.modal')
@endsection

@push('script')

<script>

  // isi data table
  $(document).ready(function(){
    var table = $('#tbl-role').DataTable({
      processing  : true,
      ordering    : true,
      serverSide  : false,
      ajax        : "{{ route('admin.role.data') }}",
      columns     : [{
        data : null, render:(data,type,row,meta)=>{
          return `<div class='text-center'>${meta.row + 1}.</div>`;
        }
      },{
        data    : 'role',
        render  : (data) => data ? `${data}` : `-` 
      },
      {
        data: 'id',
        render: function(data, type, row){
          return `
             <div class="btn-group d-flex gap-5">
                  <button class="btn btn-sm btn-warning edit-btn" data-id="${data}">Edit</button>
                  <button class="btn btn-sm btn-danger delete-btn" data-id="${data}">Hapus</button>
              </div>
          `;
        }
      }
    ]
    })
  })

  // tambah data
$(document).on('submit','#addrole', function(e){
  var table = $('.table').DataTable();
  e.preventDefault();
  showLoading();
  var form = this;
  var formData = new FormData($(this)[0]);
  let errorMessage = "Terjadi Kesalahan Ketika Menambah Data";

  $.ajax({
    url   : $(form).attr('action'),
    type  : 'POST',
    data  : formData,
    contentType : false,
    processData : false,
    success : function(response){
      closeLoading();
      if(response.status == 200){
        swal.fire('Berhasil Menambah Data',response.message, 'success')
      }else{
        swal.fire('Tidak Berhasil Menambahkan Role', response.message, 'error');
      }
      $(form).closest('.modal').modal('hide');
      form.reset();
      table.ajax.reload();
    },
    error : function(response){
      closeLoading();
      if (response.status === 422) {
          let errors = response.responseJSON.errors;
          errorMessage = Object.values(errors).flat().join('<br>');
          console.log(errorMessage);
          // Tampilkan semua error
          for (let field in errors) {
              $(`[name="${field}"]`)
                  .addClass('is-invalid')
                  .after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
          }
      } else if (response.responseJSON && response.responseJSON.message) {
          errorMessage = response.responseJSON.message;
      }

      swal.fire({
                title : 'Gagal tambah data',
                html  : errorMessage,
                icon  : 'error' 
            });
    }

  });
})


// edit data 

$('.table').on('click','.edit-btn', function(){
  var id = $(this).data('id');
  showLoading();
  var url = "{{ route('role.edit',':id') }}".replace(':id',id);
  $.get(url, function(data){
    closeLoading();
    $('#editID').val(data.id);
    $('#role').val(data.role);
  var updateUrl ="{{ route('role.update',':id') }}".replace(':id',data.id);
    $('#formeditrole').attr('action',updateUrl);
    $('#editData').modal('show');

  })
});


$('#formeditrole').on('submit',function(e){
  var table = $('.table').DataTable();
  e.preventDefault();
  // showLoading();
  var form = this;
  var formData = new FormData($(this)[0]);
  $.ajax({
      url   : $(this).attr('action'),
      type  : 'POST',
      data  : formData,
      async : false,
      cache : false,
      contentType : false,
      processData : false,
      success : function(response){
        closeLoading();
        if(response.status == 200){
          swal.fire('Berhasil', response.message,'success');
        }else{
          swal.fire('gagal',response.message,'error');
        }
        $(form).closest('.modal').modal('hide');
        form.reset();
        table.ajax.reload();
      },
        error : function(response){
          closeLoading();
          let errorMessage = "Terjadi Kesalahan Ketika Update Data";
          if(response.responseJSON){
            if(response.responseJSON.errors){
              errorMessage = Object.values(response.responseJSON.errors).flat().join("<br>");
            }
            else if(response.responseJSON.message){
              errorMessage = response.responseJSON.message;
            }
          }
          swal.fire({
            title : 'gagal',
            html  : errorMessage,
            icon  : 'error'
          });
        }
  });
});

// delete data 

$('.table').on('click','.delete-btn', function(){
  var table = $('.table').DataTable();
  var id    = $(this).data('id');
  var url   = "{{ route('role.destroy',':id') }}".replace(':id',id);
  
  swal.fire({
    title   : "Hapus Role",
    text    : "Apakah anda yakin menghapus role ini ?",
    icon    : "warning",
    showCancelButton  : true,
    confirmButtonText : "Hapus",
    cancelButtonText  :  "Batal",
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6'
  }).then((result)=>{
      if (result.isConfirmed) {
      showLoading();
      $.ajax({
        url   : url,
        type  : "DELETE",
        data  : {_token:'{{ csrf_token() }}'},
        success : function(response){
          closeLoading();
          if(response.status == 200){
              swal.fire('Berhasil', response.message,'success')
          }else{
            swal.fire('gagal',response.message,'error')
          }
          table.ajax.reload();
        },
        error : function(){
          closeLoading();
          swal.fire('gagal','Terjadi Kesalahan Ketika menghapus Data','error');
        }
         });
        }
       

      });
})

</script>
@endpush