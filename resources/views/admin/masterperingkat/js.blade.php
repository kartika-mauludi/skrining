
@push('script')

<script>

// start master rank
  // isi data table
  $(document).ready(function(){
    var table = $('#tbl-rank').DataTable({
      processing  : true,
      ordering    : true,
      serverSide  : false,
      ajax        : "{{ route('admin.masterperingkat.data') }}",
      columns     : [{
        data : null, render:(data,type,row,meta)=>{
          return `<div class='text-center'>${meta.row + 1}.</div>`;
        }
      },{
        data    : 'name',
        render  : (data) => data ? `${data}` : `-` 
      },
      {
        data: 'id',
        render: function(data, type, row){
          return `
             <div class="btn-group d-flex gap-5">
                  <a href="#" class="btn btn-sm btn-success indikator-btn" data-id="${data}">Lihat Indikator</a>
                  <button class="btn btn-sm btn-warning edit-btn" data-id="${data}">Edit</button>
                  <button class="btn btn-sm btn-danger delete-btn" data-id="${data}">Hapus</button>
              </div>
          `;
        }
      }
    ]
    })
  })

  
$(document).on('click', '.indikator-btn', function (e) {
    e.preventDefault();

    let id  = $(this).data('id');
    let url = "{{ route('indikator.show', ':id') }}".replace(':id', id);

    window.location.href = url;
});

  // tambah data
$(document).on('submit','#addperingkat', function(e){
  var table = $('#tbl-rank').DataTable();
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
        swal.fire('Tidak Berhasil Menambahkan Data', response.message, 'error');
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

// indikator
$(document).on('click','.indikator-btn',function(){
  var id  = $(this).data('id');
  var url = "{{ route('indikator.show',':id') }}".replace(':id',id);

  $.ajax({
        url: url,
        type: 'GET',
        success: function(res){
            console.log(res);
        },
        error: function(err){
            console.error(err);
        }
    });
    
});
  
// edit data 

$('#tbl-rank').on('click','.edit-btn', function(){

  var id = $(this).data('id');
  showLoading();
  var url = "{{ route('masterperingkat.edit',':id') }}".replace(':id',id);
  $.get(url, function(data){
    closeLoading();
    $('#editID').val(data.id);
    $('#peringkat').val(data.name);
  var updateUrl ="{{ route('masterperingkat.update',':id') }}".replace(':id',data.id);
    $('#formeditperingkat').attr('action',updateUrl);
    $('#editData').modal('show');

  })
});


$('#formeditperingkat').on('submit',function(e){
  var table = $('#tbl-rank').DataTable();
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

$('#tbl-rank').on('click','.delete-btn', function(){
  var table = $('#tbl-rank').DataTable();
  var id    = $(this).data('id');
  var url   = "{{ route('masterperingkat.destroy',':id') }}".replace(':id',id);
  
  swal.fire({
    title   : "Hapus Data",
    text    : "Apakah anda yakin menghapus data ini ?",
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
// end master rank 

// ============================ start master periode ================================================

 $(document).ready(function(){
    var table = $('#tbl-periode').DataTable({
      processing  : true,
      ordering    : true,
      serverSide  : false,
      ajax        : "{{ route('admin.periode.data') }}",
      columns     : [{
        data : null, render:(data,type,row,meta)=>{
          return `<div class='text-center'>${meta.row + 1}.</div>`;
        }
      },{
        data    : 'periode',
        render  : (data) => data ? `${data}` : `-` 
      },{
        data    : 'edisi',
        render  : (data) => data ? `${data}` : `-` 
      },
      {
        data: 'id',
        render: function(data, type, row){
          return `
             <div class="btn-group d-flex gap-5">
                  <button class="btn btn-sm btn-warning editPeriode-btn" data-id="${data}">Edit</button>
                  <button class="btn btn-sm btn-danger deletePeriode-btn" data-id="${data}">Hapus</button>
              </div>
          `;
        }
      }
    ]
    })
  })

  // tambah data periode
$(document).on('submit','#addperiode', function(e){
  var table = $('#tbl-periode').DataTable();
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
        swal.fire('Tidak Berhasil Menambahkan Data', response.message, 'error');
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

// edit data periode

$('#tbl-periode').on('click','.editPeriode-btn', function(){
  var id = $(this).data('id');
  showLoading();
  var url = "{{ route('periode.edit',':id') }}".replace(':id',id);
  $.get(url, function(data){
    closeLoading();
    $('#editID').val(data.id);
    $('#periode').val(data.periode);
    $('#edisi').val(data.edisi);
  var updateUrl ="{{ route('periode.update',':id') }}".replace(':id',data.id);
    $('#formeditPeriode').attr('action',updateUrl);
    $('#editDataPeriode').modal('show');

  })
});


$('#formeditPeriode').on('submit',function(e){
  var table = $('#tbl-periode').DataTable();
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

// delete data periode

$('#tbl-periode').on('click','.deletePeriode-btn', function(){
  var table = $('#tbl-periode').DataTable();
  var id    = $(this).data('id');
  var url   = "{{ route('periode.destroy',':id') }}".replace(':id',id);
  
  swal.fire({
    title   : "Hapus Data",
    text    : "Apakah anda yakin menghapus data ini ?",
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
// end master  periode

</script>
@endpush