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
              <div class="card-header d-flex justify-content-between">
                <div>
                 <button class="btn btn-secondary p-1" id="kembali"  data-toggle="modal">Kembali </button>
                </div>
                <div class="ml-auto">
                 <button class="btn btn-success p-1 addNew" id="add"  data-toggle="modal" data-target="#addData"> Tambah </button>
                 <button class="btn btn-primary p-1" id="import"  data-toggle="modal" data-target="#importModal"> Import </button>
                 <button class="btn btn-sm btn-warning edit-btn" data-toggle="modal"  data-target=".addDataModal">Edit</button>
                 <button class="btn btn-sm btn-danger deleteAll" id="deleteAll" >Hapus Semua Soal</button>
                </div>
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
                    <th>Indikator</th>
                    <th>Indikasi Bully</th>
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
                    <th>Indikator</th>
                    <th>Indikasi Bully</th>
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
 @include('admin.angketSoal.modal_import')
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
        data    : 'lokasi_kejadian',
        render  : (data) => data ? `${data}` : `-` 
      },{
        data    : 'indikasi_siswa',
        render  : (data) => data ? `${data}` : `-` 
      },{
        data    : 'indikasi_bully',
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
  let table;
  if ($.fn.DataTable.isDataTable('#tbl-angketSoal')) {
      table = $('#tbl-angketSoal').DataTable();
  }

  e.preventDefault();
  showLoading();
    var form = this;

  // 🔥 1. Update name index kalau perlu
  updateSoalOrder();

  // 🔥 2. Sync Summernote KE TEXTAREA DULU
  $('.summernote').each(function () {
      $(this).val($(this).summernote('code'));
  });

  // 🔥 3. BARU buat FormData
  var formData = new FormData(form);

  // 🔎 DEBUG (WAJIB CEK SEKALI)
  for (let pair of formData.entries()) {
      console.log(pair[0] + " => " + pair[1]);
  }
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
      if (table) {
        table.ajax.reload(null, false); // reload tanpa reset paging
       }
    },
    error : function(response){
      // closeLoading();
      // if(response.status === 400 ||  response.status === 422){
      //   let errors = response.responseJSON.errors;
      //   errorMessage = Object.values(errors).flat().join('<br>');
       
      // }

      // closeLoading();

if (response.status === 400 || response.status === 422) {

    let errorMessage = 'Terjadi kesalahan';

    if (response.responseJSON) {

        if (response.responseJSON.errors) {
            errorMessage = Object.values(response.responseJSON.errors)
                .flat()
                .join('<br>');
        } else if (response.responseJSON.message) {
            errorMessage = response.responseJSON.message;
        }

    }

    console.log(errorMessage);
       Swal.fire({
        title : 'Data Gagal',
        html  : errorMessage,
        icon  : 'error' 
    });

}
      else if (response.responseJSON && response.responseJSON.message) {
                errorMessage = response.responseJSON.message;
    }


    }
  });
});


// delete

$(document).on('click','.delete-btn',function(){
var id    = $(this).data('id');
var url   = "{{ route('angketsoal.destroy',':id') }}".replace(':id',id);
let table;
  if ($.fn.DataTable.isDataTable('#tbl-angketSoal')) {
      table = $('#tbl-angketSoal').DataTable();
  }


  Swal.fire({
    title   :"Hapus Soal",
    text    : "Anda Yakin Untuk Hapus Soal Ini ?",
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
           if (table) {
                        table.ajax.reload(null, false); // reload tanpa reset paging
                    }
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


$(document).on('click', '#deleteAll', function () {
    Swal.fire({
        title: 'Hapus Semua Soal?',
        text: 'Semua pertanyaan akan dihapus dan tidak bisa dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus semua',
        cancelButtonText: 'Batal'
    }).then(result => {
        if (result.isConfirmed) {
            showLoading();

            $.ajax({
                url: "{{ route('angketsoal.destroyAll') }}",
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                    angket_id: '{{ $angket->id }}'
                },
                success: function (res) {
                    closeLoading();

                    if (res.status === 200) {
                        Swal.fire('Berhasil', res.message, 'success');

                        // reload datatable
                       if (table) {
                        table.ajax.reload(null, false); // reload tanpa reset paging
                      }

                        // bersihkan modal juga
                        $('#soal-container').empty();
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


// ============================
//  edit
// ============================

let isEditMode = false;

 const soalData = @json($soal); 
    let soalIndex = 0;
    $(document).ready(function () {
     $('.addNew').on('click', function () {
       isEditMode = false;
        $('#soal-container').empty();
        soalIndex = 0;

        renderSoal(null); // soal baru kosong
        $('.addSoal').show();
        $('.rmInput').show();
        $('#addData').modal('show');
     });
     $('.addSoal').on('click', function () {
          renderSoal(null);
      });
    });



function renderSoal(data = null) {
    const parent = $('#soal-container');
    const idx = soalIndex++;

    let html = `
    <div class="soal-input border p-2 rounded mt-3" data-index="${idx}">
        <div class="d-flex align-items-start">
            <span class="drag-handle mr-2">☰</span>
            <span role="button" class="text-danger mr-2 rmInput">
                <i class="fas fa-trash"></i>
            </span>

            <input type="hidden" name="soal[${idx}][id]" class="inputId" value="${data?.id ?? ''}">
            <input type="hidden" name="soal[${idx}][sequence]" class="sequence-input" value="${idx + 1}">

            <div class="w-100">
                <textarea
                    name="soal[${idx}][soal]"
                    class="form-control form-control-sm mb-2 summernote"
                    placeholder="Pertanyaan"
                    required>${data?.soal ?? ''}</textarea>

                <div class="d-flex mb-2">
                    <select name="soal[${idx}][tipe_soal]"
                        class="form-control form-control-sm w-25 mr-1 tipe-soal" required>
                        <option value="">Tipe Soal</option>
                        <option value="range">Skala</option>
                        <option value="text">Text</option>
                        <option value="keterangan">Keterangan</option>
                    </select>

                    <select name="soal[${idx}][ruang]"
                        class="form-control form-control-sm w-25 mr-1 lokasi_kejadian" required>
                        <option value="">Ruang Linkup</option>
                        <option value="lingkungan kelas">Di dalam kelas</option>
                        <option value="sosmed">Sosial Media</option>
                        <option value="game">Game</option>
                        <option value="lainnya">Lain lain</option>
                    </select>

                    <select name="soal[${idx}][indikator]"
                        class="form-control form-control-sm w-25 mr-1 indikasi_siswa" required>
                        <option value="">Indikator</option>
                        <option value="pelaku">Pelaku</option>
                        <option value="korban">Korban</option>
                    </select>

                      <select name="soal[${idx}][indikasi_bully]"
                        class="form-control form-control-sm w-25 mr-1 indikasi_bully" required>
                        <option value="">Indikasi</option>
                        <option value="verbal">Verbal</option>
                        <option value="fisik">Fisik</option>
                        <option value="sosial">Sosial</option>
                        <option value="impersonation">Impersonation</option>
                        <option value="visual_sexual">Visual Sexual</option>
                        <option value="written_verbal">Written Verbal</option>
                        <option value="online_exclusion">Online Exclusion</option>
                    </select>
                </div>
                <div class="opsi-container"></div>

                <button type="button"
                    class="btn btn-sm btn-outline-primary add-opsi mt-1"
                    style="display:none;">
                    + Tambah Opsi
                </button>
            </div>
        </div>
    </div>
    `;

     let el = $(html);
    parent.append(el);
    initSummernote(el.find('.summernote'));

    // 🧠 ISI DATA HANYA JIKA EDIT
    if (!data) return;

    el.find('.tipe-soal')
      .val(data.tipe_soal)
      .trigger('change');

    el.find('.lokasi_kejadian').val(data.lokasi_kejadian);
    el.find('.indikasi_siswa').val(data.indikasi_siswa);
    el.find('.indikasi_bully').val(data.indikasi_bully);

    if (
        (data.tipe_soal === 'radio' || data.tipe_soal === 'checkbox') &&
        Array.isArray(data.opsi)
    ) {
        const opsiContainer = el.find('.opsi-container');
        opsiContainer.empty();

        data.opsi.forEach(val => {
            opsiContainer.append(`
                <input type="text"
                    class="form-control form-control-sm mb-1"
                    name="soal[${idx}][opsi][]"
                    value="${val}">
            `);
        });

        el.find('.add-opsi').show();
    }
    
}


function renderSoalHTML(data, idx) {
    return `
      <div class="soal-input border p-2 rounded mt-3" data-index="${idx}">
        <div class="d-flex align-items-start">
            <span class="drag-handle mr-2">☰</span>
            <span role="button" class="text-danger mr-2 rmInput">
                <i class="fas fa-trash"></i>
            </span>

            <input type="text" name="soal[${idx}][id]" class="inputId" value="${data?.id ?? ''}">
            <input type="hidden" name="soal[${idx}][sequence]" class="sequence-input" value="${idx + 1}">

            <div class="w-100">
                <textarea
                    name="soal[${idx}][soal]"
                    class="form-control form-control-sm mb-2 summernote"
                    placeholder="Pertanyaan"
                    required>${data?.soal ?? ''}</textarea>

                <div class="d-flex mb-2">
                    <select name="soal[${idx}][tipe_soal]"
                        class="form-control form-control-sm w-25 mr-1 tipe-soal" required>
                        <option value="">Tipe Soal</option>
                        <option value="range">Skala</option>
                        <option value="text">Text</option>
                        <option value="keterangan">Keterangan</option>
                    </select>

                    <select name="soal[${idx}][ruang]"
                        class="form-control form-control-sm w-25 mr-1 lokasi_kejadian" required>
                        <option value="">Ruang Linkup</option>
                        <option value="lingkungan kelas">Di dalam kelas</option>
                        <option value="sosmed">Sosial Media</option>
                        <option value="game">Game</option>
                        <option value="lainnya">Lain lain</option>
                    </select>

                    <select name="soal[${idx}][indikator]"
                        class="form-control form-control-sm w-25 mr-1 indikasi_siswa" required>
                        <option value="">Indikator</option>
                        <option value="pelaku">Pelaku</option>
                        <option value="korban">Korban</option>
                    </select>

                     <select name="soal[${idx}][indikasi_bully]"
                        class="form-control form-control-sm w-25 mr-1 indikasi_bully" required>
                        <option value="">Indikator</option>
                        <option value="verbal">Verbal</option>
                        <option value="fisik">Fisik</option>
                        <option value="sosial">Sosial</option>
                        <option value="impersonation">Impersonation</option>
                        <option value="visual_sexual">Visual Sexual</option>
                        <option value="written_verbal">Written Verbal</option>
                        <option value="online_exclusion">Online Exclusion</option>
                    </select>

                </div>

                <div class="opsi-container"></div>

                <button type="button"
                    class="btn btn-sm btn-outline-primary add-opsi mt-1"
                    style="display:none;">
                    + Tambah Opsi
                </button>
            </div>
        </div>
    </div>
    `;
}


function initSummernote($el) {
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


function loadSoalForEdit() {
    return $.get("{{ route('admin.angketSoal.data') }}");
}



$(document).on('click', '.edit-btn', function () {
    isEditMode = true;
    const $container = $('#soal-container');

    $container.empty();
    soalIndex = 0;

    loadSoalForEdit().done(res => {

        let html = '';
        res.data.forEach(item => {
            html += renderSoalHTML(item, soalIndex++);
        });

        // 1️⃣ render HTML
        $container.html(html);

         initSummernote($container.find('.summernote'));

        // 2️⃣ isi data per soal
        res.data.forEach((item, index) => {
            const $soal = $container.find('.soal-input').eq(index);

            // set select
            $soal.find('.tipe-soal').val(item.tipe_soal).trigger('change');
            $soal.find('.lokasi_kejadian').val(item.lokasi_kejadian);
            $soal.find('.indikasi_siswa').val(item.indikasi_siswa);
            $soal.find('.indikasi_bully').val(item.indikasi_bully);

            // render opsi kalau ada
            if (
                (item.tipe_soal === 'checkbox') &&
                Array.isArray(item.opsi)
            ) {
                const $opsiContainer = $soal.find('.opsi-container');
                $opsiContainer.empty();

                item.opsi.forEach((opsi, i) => {
                    $opsiContainer.append(renderOpsi(index, i, opsi));
                });

                $soal.find('.add-opsi').show();
            }
        });

        $('.addSoal').hide();
        $('.rmInput').hide();
        $container.sortable('refresh');
        $('#addData').modal('show');
    });
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

             // ✅ sequence (VALUE + NAME)
            $(this).find('.sequence-input')
                .val(index + 1)
                .attr('name', `soal[${index}][sequence]`);

             // ✅ id juga HARUS ikut rename
             $(this).find('.inputId')
              .attr('name', `soal[${index}][id]`);
            // update sequence (1,2,3,...)

            // soal
            $(this).find('textarea.summernote')
              .attr('name', `soal[${index}][soal]`);

            $(this).find('.lokasi_kejadian').attr(
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

              $(this).find('select[name*="[indikasi_bully]"]').attr(
                'name',
                `soal[${index}][indikasi_bully]`
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
         let $soal = $(this).closest('.soal-input');
          let $sn = $soal.find('.summernote');

          if ($sn.next('.note-editor').length) {
            $sn.summernote('destroy');
          }

          $soal.remove();
    });

    // ===============================
    // CHANGE TYPE
    // ===============================
    $(document).on('change', '.tipe-soal', function () {

        const soal = $(this).closest('.soal-input');
        const idx  = soal.data('index');
        const tipe = $(this).val();

        const opsiContainer = soal.find('.opsi-container');
        const btnAdd = soal.find('.add-opsi');
        const ruang = soal.find('.lokasi_kejadian');
        const indikator = soal.find('.indikasi_siswa');
        const indikator2 = soal.find('.indikasi_bully');

        // reset
        opsiContainer.empty();
        soal.find('.nilai-info').text('');
        btnAdd.hide();

        // ===== OPSI =====
        if (tipe === 'checkbox') {
            opsiContainer.append(renderOpsi(idx));
            opsiContainer.append(renderOpsi(idx));
            btnAdd.show();
        }

        if (tipe === 'text') {
          ruang.val('lingkungan kelas');
          indikator.val('korban');
          
          ruang.find('option').each(function () {
            if ($(this).val() !== 'lingkungan kelas') {
              $(this).prop('disabled', true);1
            }
          });
           indikator.find('option').each(function(){
            if ($(this).val() !== 'korban') {
                  $(this).prop('disabled', true);1
              }
          });
             ruang.prop('disabled', false).prop('required', true);
             indikator.prop('disabled', false).prop('required', true);
          } else {
             ruang.find('option').prop('disabled', false); 
             indikator.find('option').prop('disabled', false); 
         }

        // ===== KETERANGAN =====
        if (tipe === 'keterangan') {
            ruang.prop('required', false).prop('disabled', true).hide().val('');
            indikator.prop('required', false).prop('disabled', true).hide().val('');
            indikator2.prop('required', false).prop('disabled', true).hide().val('');
        } else {
            ruang.prop('disabled', false).show().prop('required', true);
            indikator.prop('disabled', false).show().prop('required', true);
            indikator2.prop('disabled', false).show().prop('required', true);
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