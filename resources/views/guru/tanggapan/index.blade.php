@extends('guru.layout.index')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Tanggapan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Tanggapan</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        @if (session('message'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                {{ session('message') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="card-header">
                            <h3 class="card-title"></h3>
                            <button class="btn btn-sm btn-success" id="add"> Tambah </button>
                            <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#importModal"> Import </button>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="datatable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggapan</th>
                                        <th>Status</th>
                                        <th>Pembuat</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@include('guru.tanggapan.modal')
@include('guru.tanggapan.modal_import')
@endsection

@push('script')
<script>
$(document).ready(function(){
    var token = $('meta[name="csrf-token"]');

    var table = $('#datatable').DataTable({
        processing  : true,
        serverSide  : false,
        ajax: {
            url: "{{ route('guru.tanggapan.data') }}",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': token.attr('content')
            }
        },
        columns: [{
            data : null, render:(data,type,row,meta)=>{
                return `<div class='text-center'>${meta.row + 1}.</div>`;
            }
        },{
            data    : 'feedback_deskripsi',
            render  : (data) => data ? `${data}` : `-` 
        },{
            data    : 'status',
            render  : (data) => data? `${data}` : `0`
        },{
            data    : 'guru',
            render  : (data) => data ? `${data.nama_lengkap}` : `Admin` 
        },{
            data: 'id',
            render: function(data, type, row){
                let editUrl = "{{ route('guru.tanggapan.edit', ':id') }}";
                let deleteUrl = "{{ route('guru.tanggapan.destroy', ':id') }}";

                editUrl = editUrl.replace(':id', data);
                deleteUrl = deleteUrl.replace(':id', data);

                console.log(row.id_guru);
                

                return row.id_guru != null ? `
                    <div class="btn-group d-flex gap-2">
                        <button class="btn btn-sm btn-warning edit-btn" data-url="${editUrl}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-url="${deleteUrl}">Hapus</button>
                    </div>
                ` : `-`;
            }
        }]
    });

    $('#add').on('click', function () {
        let storeUrl = "{{ route('guru.tanggapan.store') }}";
        const $sn   = $('#addTanggapan');
        initSummernote($sn);
        $sn.summernote('code', '');

        $('#dataForm').prop('action', storeUrl).trigger('reset');
        $('#method').val('POST')
        $('#dataModal').modal('show');
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

    $('#datatable').on('click', '.edit-btn', function (){
        const url = $(this).data('url');

        $.get(url, function(data, status) {
            if (status != 'success') {
                swal.fire('Kesalahan sistem','Silahkan hubungi administrator','error')
                return
            }

            let updateUrl = "{{ route('guru.tanggapan.update', ':id') }}";
            updateUrl = updateUrl.replace(':id', data['data']['id']);
        
            
            $('#method').val('PUT')
            $('#status').val(data['data']['status']).change();

            const $sn   = $('#addTanggapan');
            initSummernote($sn);
            $sn.summernote('code', data['data']['feedback_deskripsi'] ?? '');

            $('#dataForm').prop('action', updateUrl);
            $('#dataModal').modal('show');
        }).fail(function(jqXHR, textStatus, errorThrown) {
            swal.fire('Kesalahan sistem',textStatus,'error')
        });
    });

    $('#datatable').on('click', '.delete-btn', function (){
        const url = $(this).data('url');

        swal.fire({
            title: 'Perhatian',
            text: 'Apakah anda yakin ?',
            icon: 'warning',
            showDenyButton: true,
            confirmButtonText: 'Hapus',
            denyButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-danger',
                denyButton: 'btn btn-secondary',
            },
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token.attr('content')
                    },
                    success: function(response) {
                        swal.fire({
                            title:'Berhasil',
                            text:'Data berhasil dihapus',
                            icon:'success',
                            allowOutsideClick: false
                        })
                        .then((result) => {
                            location.reload();
                        })
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        swal.fire('Kesalahan sistem',textStatus,'error')
                    }
                })
            }
        });
    });
})
</script>
@endpush