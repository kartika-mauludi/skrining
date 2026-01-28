@extends('guru.layout.index')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Siswa</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('guru.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Siswa</li>
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
                            <button class="btn btn-sm btn-info" data-toggle="modal"data-target="#importModal">Import</button>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No Absen</th>
                                            <th>Kelas</th>
                                            <th>NIS</th>
                                            <th>Nama Siswa</th>
                                            <th>Tempat Lahir</th>
                                            <th>Tanggal Lahir</th>
                                            <th>Alamat</th>
                                            <th>Nama Wali</th>
                                            <th>No. Telpon Wali</th>
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
        </div>
    </section>
</div>

@include('guru.siswa.modal')
@include('guru.siswa.modal_import')
@endsection

@push('script')
<script>
$(document).ready(function(){
    var token = $('meta[name="csrf-token"]');

    $('#kelas_id').select2({
        placeholder: '-- Pilih Data --',
        dropdownParent: $('#dataModal')
    })

    var table = $('#datatable').DataTable({
        processing  : true,
        serverSide  : false,
        ajax: {
            url: "{{ route('guru.siswa.data') }}",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': token.attr('content')
            }
        },
        columns: [{
            data    : 'no_absen',
            render  : (data) => data ? `${data}` : `-`
        },{
            data    : 'kelas.nama_kelas',
            render  : (data) => data ? `${data}` : `-` 
        },{
            data    : 'nis',
            render  : (data) => data ? `${data}` : `-` 
        },{
            data    : 'nama_lengkap',
            render  : (data) => data ? `${data}` : `-` 
        },{
            data    : 'tgl_lahir',
            render  : (data) => data ? `${data}` : `-` 
        },{
            data    : 'tempat_lahir',
            render  : (data) => data ? `${data}` : `-` 
        },{
            data    : 'alamat',
            render  : (data) => data ? `${data}` : `-` 
        },{
            data    : 'nama_wali',
            render  : (data) => data ? `${data}` : `-` 
        },{
            data    : 'no_tlp_wali',
            render  : (data) => data ? `${data}` : `-` 
        },{
        data: 'id',
            render: function(data, type, row){
                let editUrl = "{{ route('guru.siswa.edit', ':id') }}";
                let deleteUrl = "{{ route('guru.siswa.destroy', ':id') }}";

                editUrl = editUrl.replace(':id', data);
                deleteUrl = deleteUrl.replace(':id', data);

                return `
                    <div class="btn-group d-flex gap-2">
                        <button class="btn btn-sm btn-warning edit-btn" data-url="${editUrl}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-url="${deleteUrl}">Hapus</button>
                    </div>
                `;
            }
        }]
    });

    $('#add').on('click', function () {
        let storeUrl = "{{ route('guru.siswa.store', ':id') }}";

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

            let updateUrl = "{{ route('guru.siswa.update', ':id') }}";
            updateUrl = updateUrl.replace(':id', data['data']['id']);
        
            $('#method').val('PUT')
            $('#kelas_id').val(data['data']['kelas_id']).change();
            $('#nama_lengkap').val(data['data']['nama_wali']);
            $('#no_absen').val(data['data']['no_absen']);
            $('#nis').val(data['data']['nis']);
            $('#tgl_lahir').val(data['data']['tgl_lahir']);
            $('#tempat_lahir').val(data['data']['tempat_lahir']);
            $('#alamat').val(data['data']['alamat']);
            $('#nama_wali').val(data['data']['nama_wali']);
            $('#no_tlp_wali').val(data['data']['no_tlp_wali']);

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