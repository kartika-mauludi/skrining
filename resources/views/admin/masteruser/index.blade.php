@extends('admin.layout.index')

@section('content')
@push('style')
<style>
     .select2 {
        color: #000 !important
    }
</style>
@endpush
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data User</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
              <li class="breadcrumb-item active">Data User</li>
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
                <table id="tbl-user" class="table table-bordered table-striped">
                 <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
          
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
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

 @include('admin.masteruser.modal')
@endsection

@push('script')
<script>
  // eye password
document.getElementById('togglePassword').addEventListener('click', function () {
    const input = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash'); // ubah ke icon hide
    } else {
        input.type = "password";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye'); // kembali ke icon show
    }
});


  // isi data table
  $(document).ready(function(){
    var table = $('#tbl-user').DataTable({
      // dom         : 'Bfrtip',
      responsive    : 'true',
      // buttons     : ['copy', 'csv', 'excel', 'pdf', 'print'],
      processing  : true,
      ordering    : true,
      serverSide  : false,
      ajax        : "{{ route('admin.masteruser.data') }}",
      columns     : [{
        data : null, render:(data,type,row,meta)=>{
          return `<div class='text-center'>${meta.row + 1}.</div>`;
        }
      },{
        data    : 'name',
        render  : (data) => data ? `${data}` : `-` 
      },{
        data    : 'email',
        render  : (data) => data ? `${data}` : `-`
      },
      {
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
$(document).on('submit','#addDataForm', function(e){
  var table = $('.table').DataTable();
  e.preventDefault();
  showLoading();
  var form = this;
  var formData = new FormData($(this)[0]);
  let error = "Terjadi Kesalahan Ketika Menambah Data";
  
  $.ajax({
    url   : $(form).attr('action'),
    type  : 'POST',
    data  : formData,
    processData: false,
    contentType: false,
    success : function(response){
      closeLoading();
      if(response.status == 200){
        swal.fire('Berhasil', response.message, 'success');
      }else{
        swal.fire('Gagal', response.message, 'error');
      }
      $(form).closest('.modal').modal('hide');
      form.reset();
      table.ajax.reload();
    },
    error : function(response){
      closeLoading();
      if(response.status === 400 ||  response.status === 422){
        let errors = response.responseJSON.errors;
        errorMessage = Object.values(errors).flat().join('<br>');
       
      }
      else if (response.responseJSON && response.responseJSON.message) {
                errorMessage = response.responseJSON.message;
    }
    Swal.fire({
        title : 'Gagal tambah data',
        html  : errorMessage,
        icon  : 'error' 
    });

    }
  });
});


// delete

$(document).on('click','.delete-btn',function(){
var table = $('.table').DataTable();
var id    = $(this).data('id');
var url   = "{{ route('masteruser.destroy',':id') }}".replace(':id',id);

  swal.fire({
    title   :"Hapus User",
    text    : "Anda Yakin Untuk Hapus User Ini ?",
    icon    : 'warning',
    showCancelButton  : true,
    confirmButtonText : "Hapus",
    cancelButtonText  : "Batal",
  }).then((result)=>{
    if(result.isConfirmed){
      showLoading();
      $.ajax({
        url   : url,
        type  : 'DELETE',
        data  : {_token : '{{ csrf_token() }}'},
        success : function(response){
          closeLoading();
          if(response.status == 200){
            swal.fire('Berhasil', response.message,'success');
          }else{
            swal.fire('Gagal',response.message,'error')
          }
          table.ajax.reload();
        },
        error : function(response){
           closeLoading();
          if(response.status == 419){
          swal.fire('Gagal',response.responseJSON.message,'error')
          }
         
        }
      });
    }
  });
});


// edit modal

$('.table').on('click','.edit-btn', function(){
  var id = $(this).data('id');
  showLoading();
  var url = "{{ route('masteruser.edit',':id') }}".replace(':id',id);
  $.get(url, function(data){
    console.log(data);
    closeLoading();
    $('#editId').val(data.id);
    $('#editName').val(data.name);
    $('#editEmail').val(data.email);
    $('#editRole').val(data.role);
    var updateUrl = "{{ route('masteruser.update',':id') }}".replace(':id',data.id);
    $('#EditDataForm').attr('action',updateUrl);
    $('#editData').modal('show');
     $('#editData').on('shown.bs.modal', function () {
        let roles = data.roles ?? [];
        let roleIds = roles.map(role => role.id);
        $('#editRole').val(roleIds).trigger('change');
      });
  });
});

// update

$('#EditDataForm').on('submit',function(e){
  var table = $('.table').DataTable();
    e.preventDefault();
    showLoading();
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
        swal.fire('Berhasil',response.message, 'success')
      }else{
        swal.fire('gagal',response.errorMessage, 'error')
      }
       $(form).closest('.modal').modal('hide');
      form.reset();
      table.ajax.reload();
    },
    error : function(response){
      closeLoading();
      let errorMessage = 'Terjadi kesalahan ketika update data';
      if(response.responseJSON){
        if(response.responseJSON.errors){
          errorMessage = Object.values(response.responseJSON.errors).flat().join('<br>');
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


</script>
@endpush