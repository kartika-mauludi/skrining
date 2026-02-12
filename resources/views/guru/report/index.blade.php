@extends('guru.layout.index')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Report Siswa</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Report Siswa</li>
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
                                            <th>Report</th>
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
@endsection

@push('script')
<script>
$(document).ready(function(){
    var token = $('meta[name="csrf-token"]');

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
                let korbanUrl = "{{ route('guru.report.korban', ':id') }}".replace(':id', data);
                let pelakuUrl = "{{ route('guru.report.pelaku', ':id') }}".replace(':id', data);

                return `
                    <div class="btn-group d-flex gap-2">
                        <a class="btn btn-sm btn-success" href="${korbanUrl}">Korban</a>
                        <a class="btn btn-sm btn-danger" href="${pelakuUrl}">Pelaku</a>
                    </div>
                `;
            }
        }]
    });
})
</script>
@endpush