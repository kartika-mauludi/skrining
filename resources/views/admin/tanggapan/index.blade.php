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
            <h1>Data Tanggapan</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
              <li class="breadcrumb-item active">Data Tanggapan</li>
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
                 <button class="btn btn-primary p-1" id="import"  data-toggle="modal" data-target="#importModal"> Import </button>
                 <button class="btn btn-danger p-1 deleteAll" id="deleteAll" >Hapus Semua Soal</button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="tbl-tanggapan" class="table table-bordered table-striped">
                 <thead>
                  <tr>
                    <th>No</th>
                    <th>Tanggapan</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
          
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>No</th>
                    <th>Tanggapan</th>
                    <th>Status</th>
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

 @include('admin.tanggapan.modal')
 @include('admin.tanggapan.modal_import')
@endsection

@push('script')
<script>
  // isi data table
  let table
  $(document).ready(function(){
    var table = $('#tbl-tanggapan').DataTable({
      responsive  : true,
      processing  : true,
      ordering    : true,
      serverSide  : false,
      ajax        : "{{ route('admin.tanggapan.data') }}",
      columns     : [{
        data : null, render:(data,type,row,meta)=>{
          return `<div class='text-center'>${meta.row + 1}.</div>`;
        }
      },{
        data    : 'feedback_deskripsi',
        render  : (data) => data ? `${data}` : `-` 
      },{
        data    : 'status',
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

  $(document).on('click', '#add', function () {
    const $form = $('#addDataForm');
    const $sn   = $('#addTanggapan');
    $form[0].reset();
    initSummernote($sn);
    $sn.summernote('code', '');
});

function initSummernote($el) {
  if ($el.next('.note-editor').length) {
        $el.summernote('destroy');
    }

  if (!$el.next('.note-editor').length) {
    $el.summernote({
      height: 120,
      toolbar: [
        ['style', ['bold', 'italic', 'underline']],
        ['para', ['ul', 'ol']],
        ['insert', ['link']],
        ['view', ['codeview']]
      ]
    });
  }
}

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
var url   = "{{ route('tanggapan.destroy',':id') }}".replace(':id',id);

  swal.fire({
    title   :"Hapus Tanggapan",
    text    : "Anda Yakin Untuk Hapus Tanggapan Ini ?",
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
  const $sn = $('#editNamaTanggapan');
  showLoading();
  var url = "{{ route('tanggapan.edit',':id') }}".replace(':id',id);
  $.get(url, function(data){
    console.log(data);
    closeLoading();
    $('#editId').val(data.id);
     initSummernote($sn);
     $sn.summernote('code', data.feedback_deskripsi ?? '');
    $('#editStatus').val(data.status);
    var updateUrl = "{{ route('tanggapan.update',':id') }}".replace(':id',data.id);
    $('#EditDataForm').attr('action',updateUrl);
    $('#editData').modal('show');
    $('#editData').on('shown.bs.modal', function () {
         $('#editStatus').val(String(data.status ?? '')).trigger('change');
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

//  =================== delete all =======================


$(document).on('click', '#deleteAll', function () {
    Swal.fire({
        title: 'Hapus Semua Tanggapan?',
        text: 'Semua pertanyaan akan dihapus dan tidak bisa dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus semua',
        cancelButtonText: 'Batal'
    }).then(result => {
        if (result.isConfirmed) {
            showLoading();

            $.ajax({
                url: "{{ route('admin.tanggapan.destroyAll') }}",
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function (res) {
                    closeLoading();

                    if (res.status === 200) {
                        Swal.fire('Berhasil', res.message, 'success');
                        // reload datatable
                        $('.table').DataTable().ajax.reload();
                    } else {
                        Swal.fire('Gagal', res.message, 'error');
                    }
                },
                error: function () {
                    closeLoading();
                    Swal.fire('Error', 'Terjadi kesalahan server', 'error');
                }
            });
        }
    });
});



</script>
@endpush