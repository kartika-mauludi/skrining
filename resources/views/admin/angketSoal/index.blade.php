@extends('admin.layout.index')

@section('content')
@push('style')
<style>
     .select2 {
        color: #000 !important
    }
    .sortable-placeholder {
        border: 2px dashed #ccc;
        height: 60px;
        margin-top: 10px;
    }
    .drag-handle {
        cursor: move;
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
            <h1>Data Soal</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('angket.index') }}">Angket</a></li>
              <li class="breadcrumb-item active">Data Soal</li>
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
                 <button class="btn btn-secondary p-1" id="kembali"  data-toggle="modal">Kembali </button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="tbl-angketSoal" class="table table-bordered table-striped">
                 <thead>
                  <tr>
                    <th>Urut</th>
                    <th>Soal</th>
                    <th>Tipe Soal</th>
                    <th>Ruang Lingkup</th>
                    <!-- <th></th> -->
                    <th>Sekolah</th>
                    <th>Owner</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
          
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Urut</th>
                    <th>Soal</th>
                    <th>Tipe Soal</th>
                    <th>Ruang Lingkup</th>
                    <!-- <th></th> -->
                    <th>Sekolah</th>
                    <th>Owner</th>
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

 @include('admin.angketSoal.modal')
@endsection

@push('script')
<script>
  // isi data table
  $(document).ready(function(){
    var table = $('#tbl-angketSoal').DataTable({
      responsive  : true,
      processing  : true,
      ordering    : true,
      serverSide  : false,
      ajax        : "{{ route('admin.angketSoal.data') }}",
      columns     : [{
        data    : 'sequence',
        render  : (data) => data ? `${data}` : `-` 
      },
      {
        data    : 'soal',
        render  : (data) => data ? `${data}` : `-` 
      }, {
        data    : 'tipe_soal',
        render  : (data) => data ? `${data}` : `-` 
      },{
        data    : 'ruang_lingkup',
        render  : (data) => data ? `${data}` : `-` 
      },
      {
        data    : 'sekolah_id',
        render  : (data) => data ? `${data}` : `-` 
      },{
        data    : 'guru_id',
        render  : (data) => data ? `${data}` : `Admin` 
      },
      {
        data: 'id',
        render: function(data, type, row){
          return `
             <div class="btn-group d-flex gap-5">
                  <button class="btn btn-sm btn-primary jawaban-btn" data-id="${data}">Jawaban</button>
                  <button class="btn btn-sm btn-warning edit-btn" data-id="${data}">Edit</button>
                  <button class="btn btn-sm btn-danger delete-btn" data-id="${data}">Hapus</button>
              </div>
          `;
        }
      }
    ]
    })
  })

   $(document).on("click", "#kembali", function () {
    window.location.href = "{{ route('angket.index') }}";
  });

  $(document).on("click", ".jawaban-btn", function () {
    window.location.href = "edit.html";
  });

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
        Swal.fire('Berhasil', response.message, 'success');
      }else{
        Swal.fire('Gagal', response.message, 'error');
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
var url   = "{{ route('angket.destroy',':id') }}".replace(':id',id);

  Swal.fire({
    title   :"Hapus Angket",
    text    : "Anda Yakin Untuk Hapus Angket Ini ?",
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
            Swal.fire('Berhasil', response.message,'success');
          }else{
            Swal.fire('Gagal',response.message,'error')
          }
          table.ajax.reload();
        },
        error : function(response){
           closeLoading();
          if(response.status == 419){
          Swal.fire('Gagal',response.responseJSON.message,'error')
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
  var url = "{{ route('angket.edit',':id') }}".replace(':id',id);
  $.get(url, function(data){
    console.log(data);
    closeLoading();
    $('#editId').val(data.id);
    $('#editNamaAngket').val(data.nama_angket);
    var updateUrl = "{{ route('angket.update',':id') }}".replace(':id',data.id);
    $('#EditDataForm').attr('action',updateUrl);
    $('#editData').modal('show');
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
        Swal.fire('Berhasil',response.message, 'success')
      }else{
        Swal.fire('gagal',response.errorMessage, 'error')
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
      Swal.fire({
        title : 'gagal',
        html  : errorMessage,
        icon  : 'error'
      });
    }
    
  });
});

 let soalIndex = 0;

  // ===============================
    // ADD NEW QUESTION
    // ===============================
    $('.addNew').on('click', function () {
        const parent = $('#soal-container');
        const idx = soalIndex++;

        let input = `
        <div class="soal-input border p-2 rounded mt-3" data-index="${idx}">
            <div class="d-flex align-items-start">
                <span class="drag-handle mr-2">☰</span>
                <span role="button" class="text-danger mr-2 rmInput">
                    <i class="fas fa-trash"></i>
                </span>
                <input type="hidden"
                  name="soal[${idx}][sequence]"
                  class="sequence-input"
                  value="${idx + 1}">

                <div class="w-100">
                    <textarea
                        name="soal[${idx}][pertanyaan]"
                        class="form-control form-control-sm mb-2"
                        placeholder="Pertanyaan"
                        required></textarea>

                    <div class="d-flex mb-2">
                      <select
                            name="soal[${idx}][tipe_soal]"
                            class="form-control form-control-sm w-25 mr-1 tipe-soal"
                            required>
                            <option value="">Tipe Soal</option>
                            <option value="radio">Pilihan</option>
                            <option value="range">Range</option>
                            <option value="text">Text</option>
                            <option value="keterangan">Keterangan</option>
                        </select>

                       <select
                            name="soal[${idx}][ruang]"
                            class="form-control form-control-sm w-25 mr-1 ruang-soal"
                            required>
                            <option value="">Ruang Linkup</option>
                            <option value="lingkungan kelas">Di dalam kelas</option>
                            <option value="sosmed">Sosial Media</option>
                            <option value="game">Game</option>
                            <option value="lainnya">Lain lain</option>
                        </select>
                      
                         <select
                            name="soal[${idx}][indikator]"
                            class="form-control form-control-sm w-25 mr-1 indikator-soal"
                            required>
                            <option value="">Indikator</option>
                            <option value="pelaku">Pelaku</option>
                            <option value="korban">Korban</option>
                        </select>
                    </div>

                    <div class="opsi-container"></div>

                    <button type="button"
                        class="btn btn-sm btn-outline-primary add-opsi mt-1"
                        style="display:none;">
                        + Tambah Opsi
                    </button>

                    <small class="text-muted nilai-info"></small>
                </div>
            </div>
        </div>
        `;

        parent.append(input);
    });
    // ========================= ====
    // Drag
    // =============================== 

    $('#soal-container').sortable({
      handle: '.drag-handle',
      placeholder: 'sortable-placeholder',
      update: function () {
          updateSoalOrder();
      }
    });

    // ================================
    // update Soal Order
    // ================================
   function updateSoalOrder() {
        $('#soal-container .soal-input').each(function (index) {
            $(this).attr('data-index', index);

            // update sequence (1,2,3,...)
            $(this).find('.sequence-input').val(index + 1);

            // soal
            $(this).find('textarea').attr(
                'name',
                `soal[${index}][soal]`
            );

            $(this).find('.ruang-soal').attr(
                'name',
                `soal[${index}][ruang]`
            );

            $(this).find('.tipe-soal').attr(
                'name',
                `soal[${index}][tipe_soal]`
            );

            $(this).find('select[name*="[indikator]"]').attr(
                'name',
                `soal[${index}][indikator]`
            );

            // opsi
            $(this).find('.opsi-item').each(function (opsiIndex) {
                $(this).find('input[name*="[label]"]').attr(
                    'name',
                    `soal[${index}][opsi][${opsiIndex}][label]`
                );
                $(this).find('input[name*="[nilai]"]').attr(
                    'name',
                    `soal[${index}][opsi][${opsiIndex}][nilai]`
                );
            });
        });
    }

     
    // ===============================
    // REMOVE QUESTION
    // ===============================
    $(document).on('click', '.rmInput', function () {
        $(this).closest('.soal-input').remove();
    });

    // ===============================
    // CHANGE TYPE
    // ===============================
    $(document).on('change', '.tipe-soal', function () {
        const soal = $(this).closest('.soal-input');
        const idx = soal.data('index');
        const tipe = $(this).val();
        const opsiContainer = soal.find('.opsi-container');
        const btnAdd = soal.find('.add-opsi');
        const ruang = soal.find('.ruang-soal');
        const indikator = soal.find('.indikator-soal');

        opsiContainer.empty();
        soal.find('.nilai-info').text('');

        if (tipe === 'checkbox') {
            for (let i = 0; i < 2; i++) {
                opsiContainer.append(renderOpsi(idx, i));
            }
            btnAdd.show();
            updateNilai(soal);
        } else {
            btnAdd.hide();
        }
        
       
        if (tipe === 'keterangan') {
            // sembunyikan
            ruang.closest('select').prop('required', false);
            indikator.closest('select').prop('required', false);

            ruang.val('').prop('disabled', true).hide();
            indikator.val('').prop('disabled', true).hide();
        } else {
            // tampilkan lagi
            ruang.prop('disabled', false).show().prop('required', true);
            indikator.prop('disabled', false).show().prop('required', true);
        }
    });

    // ===============================
    // ADD OPTION
    // ===============================
    $(document).on('click', '.add-opsi', function () {
        const soal = $(this).closest('.soal-input');
        const idx = soal.data('index');
        const opsiIndex = soal.find('.opsi-item').length;
        soal.find('.opsi-container').append(renderOpsi(idx, opsiIndex));
        updateNilai(soal);
    });

    // ===============================
    // REMOVE OPTION
    // ===============================
    $(document).on('click', '.remove-opsi', function () {
        const soal = $(this).closest('.soal-input');
        if (soal.find('.opsi-item').length <= 2) {
            alert('Minimal 2 opsi');
            return;
        }
        $(this).closest('.opsi-item').remove();
        updateNilai(soal);
    });

    // ===============================
    // UPDATE SCORE
    // ===============================
    $(document).on('input', '.bobot', function () {
        updateNilai($(this).closest('.soal-input'));
    });

    // ===============================
    // FUNCTIONS
    // ===============================
    function renderOpsi(idx, opsiIndex) {

        return `
        <div class="d-flex align-items-center opsi-item mb-1">
            <input type="text"
                name="soal[${idx}][opsi][${opsiIndex}][label]"
                class="form-control form-control-sm mr-1"
                placeholder="Pilihan"
                required>

            <input type="hidden"
                name="soal[${idx}][opsi][${opsiIndex}][nilai]"
                class="nilai-input">

            <span role="button" class="text-danger remove-opsi">
                <i class="fas fa-times"></i>
            </span>
        </div>
        `;
    }
</script>
@endpush