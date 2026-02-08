@extends('guru.layout.index')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Sekolah</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Sekolah</li>
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
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="datatable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Sekolah</th>
                                        <th>No. Telpon</th>
                                        <th>Alamat Lengkap</th>
                                        <th>Website</th>
                                        <th>Email</th>
                                        <th>Logo</th>
                                        <th>Jumlah Kelas</th>
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

@include('guru.sekolah.modal')
@endsection

@push('script')
<script>
$(document).ready(function(){
    var token = $('meta[name="csrf-token"]');

    var table = $('#datatable').DataTable({
        processing  : true,
        serverSide  : false,
        ajax: {
            url: "{{ route('guru.sekolah.data') }}",
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
            data    : 'nama_sekolah',
            render  : (data) => data ? `${data}` : `-` 
        },{
            data    : 'no_tlp',
            render  : (data) => data ? `${data}` : `-` 
        },{
            data    : 'alamat_lengkap',
            render  : (data) => data ? `${data}` : `-` 
        },{
            data    : 'email',
            render  : (data) => data ? `${data}` : `-` 
        },{
            data    : 'email',
            render  : (data) => data ? `${data}` : `-` 
        },{
            data    : 'logo',
            render  : function(data, type, row) {
                if (data) {
                    return `<img src="/storage/${data}" width="40" height="40"/>`;
                } else {
                    return '-';
                }
            }
        },{
            data    : 'kelas_count',
            render  : (data) => data? `${data}` : `0`
        },{
        data: 'id',
            render: function(data, type, row){
                let kelasUrl = "{{ route('guru.kelas.index') }}?sekolah_id=" + data;
                let editUrl = "{{ route('guru.sekolah.edit', ':id') }}";
                let deleteUrl = "{{ route('guru.sekolah.destroy', ':id') }}";

                editUrl = editUrl.replace(':id', data);
                deleteUrl = deleteUrl.replace(':id', data);

                return `
                    <div class="btn-group d-flex gap-2">
                        <a class="btn btn-sm btn-info" href="${kelasUrl}">Kelas</a>
                        <button class="btn btn-sm btn-warning edit-btn" data-url="${editUrl}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-url="${deleteUrl}">Hapus</button>
                    </div>
                `;
            }
        }]
    });

    $('#logo').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(file);
        }
    });

    $('#add').on('click', function () {
        let storeUrl = "{{ route('guru.sekolah.store', ':id') }}";

        $('#dataForm').prop('action', storeUrl).trigger('reset');
        $('#method').val('POST')
        $('#dataModal').modal('show');
    });

    $('#datatable').on('click', '.edit-btn', function (){
        const url = $(this).data('url');

        $.get(url, function(data, status) {
            if (status != 'success') {
                swal.fire('Kesalahan sistem','Silahkan hubungi administrator','error')
                return
            }

            let updateUrl = "{{ route('guru.sekolah.update', ':id') }}";
            updateUrl = updateUrl.replace(':id', data['data']['id']);

            if (data['data']['logo']) {
                $('#preview').attr('src', `/upload/${data['data']['logo']}`).show();
            } else {
                $('#preview').hide();
            }
        
            $('#method').val('PUT')
            $('#nama_sekolah').val(data['data']['nama_sekolah']);
            $('#no_tlp').val(data['data']['no_tlp']);
            $('#alamat_lengkap').val(data['data']['alamat_lengkap']);

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