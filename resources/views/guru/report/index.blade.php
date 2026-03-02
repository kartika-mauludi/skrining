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
                            <button type="button" class="btn btn-sm btn-success btnZip">
                                <span class="mr-1">Download All</span>
                                <i class="fa fa-download"></i>
                            </button>
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

<div class="modal fade" id="downloadAllModal" tabindex="-1" role="dialog" aria-labelledby="adddata" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"> Download Laporan Sebagai Korban </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form action="{{ route('guru.report.download.all') }}" method="POST" target="_blank">
                @csrf
                <input type="hidden" name="history_image" id="historyimage">
                <input type="hidden" name="kategori_image" id="kategoriimage">
                <input type="hidden" name="cyber_image" id="cyberimage">
                <input type="hidden" name="gauge_image" id="gaugeimage">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="sekolah" class="form-label">Sekolah</label>
                        <select name="sekolah" id="sekolah" class="form-control form-control-sm" style="width: 100%" required>
                            <option value=""></option>
                            @foreach ($sekolah as $sekolah)
                                <option value="{{ $sekolah->id }}" @selected(isset($request) && $request['sekolah'] == $sekolah->id)>{{ $sekolah->nama_sekolah }}</option> 
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kelas">Kelas</label>
                        <select name="kelas" id="kelas" class="form-control form-control-sm" style="width: 100%" required>
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="type">Tipe Laporan</label>
                        <select name="type" id="type" class="form-control form-control-sm" required>
                            <option value=""></option>
                            <option value="pelaku">Laporan Pelaku</option>
                            <option value="korban">Laporan Korban</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"> Close </button>
                    <button type="submit" class="btn btn-primary" > Download </button>
                </div>
            </form>
        </div>
    </div>
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

    $('#sekolah').select2({
        placeholder: '-- Pilih Data --',
        dropdownParent: $('#downloadAllModal')
    });

    $('#kelas').select2({
        placeholder: '-- Pilih Data --',
        dropdownParent: $('#downloadAllModal')
    });

    $('.btnZip').on('click', function () {

        $('#downloadAllModal').modal();
    });

    $('#sekolah').on('change', function () {
        const sekolahId = $(this).val();
        $('#kelas').html('<option value=""></option>').trigger('change');

        if (sekolahId) {
            loadKelas(sekolahId);
        }
    });

    function loadKelas(sekolahId) {

        $.get(`{{ url('/guru/sekolah/${sekolahId}') }}`)
            .done(function (res) {

                const kelasList = res.data.kelas;
                let options = '<option value=""></option>';

                kelasList.forEach(function (k) {
                    options += `
                        <option value="${k.id}">
                            ${k.nama_kelas}
                        </option>
                    `;
                });

                $('#kelas').html(options)
                .trigger('change');

            })
            .fail(function () {
                Swal.fire(
                    'Kesalahan sistem',
                    'Silahkan hubungi administrator',
                    'error'
                );
            });
    }
})
</script>
@endpush