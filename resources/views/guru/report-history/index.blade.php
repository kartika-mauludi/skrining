@extends('guru.layout.index')

@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Report Kelas</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Report Kelas</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form action="" method="GET">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="form-group">
                            <label for="sekolah" class="form-label">Sekolah</label>
                            <select name="sekolah" id="sekolah" class="form-control form-control-sm" required>
                                <option value=""></option>
                                @foreach ($sekolah as $sekolah)
                                    <option value="{{ $sekolah->id }}" @selected(isset($request) && $request['sekolah'] == $sekolah->id)>{{ $sekolah->nama_sekolah }}</option> 
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="form-group">
                            <label for="kelas" class="form-label">Kelas</label>
                            <select name="kelas" id="kelas" class="form-control form-control-sm" required>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                </div>

                <button class="btn btn-sm btn-success">Set Record</button>
                <a href="{{ route('guru.report.kelas') }}" class="btn btn-sm btn-secondary">Reset</a>
            </form>

            <hr>

            <!-- Main row -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @isset($kelas)
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <canvas id="chartPelaku"></canvas>
                                </div>
                            </div>
                            @else
                                <div class="alert alert-light fade show text-center" role="alert">
                                    Silahkan pilih sekolah dan kelas terlebih dahulu.
                                </div>
                            @endisset
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@0.5.7/chartjs-plugin-annotation.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-gauge@0.3.0/dist/chartjs-gauge.min.js"></script>
<script src="https://unpkg.com/chartjs-plugin-datalabels@0.7.0/dist/chartjs-plugin-datalabels.js"></script>
<script>
  $(function () {

        const ctx = document.getElementById('chartPelaku');
        const Idkelas = @json($request['kelas'] ?? null);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($dataGrafik['labels']),
                datasets: [
                    {
                        label: 'Korban',
                        data: @json($dataGrafik['korban']),
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',   // biru isi
                        borderColor: 'rgba(54, 162, 235, 1)', 
                        borderWidth: 1
                    },
                    {
                        label: 'Pelaku',
                        data: @json($dataGrafik['pelaku']),
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',   // merah isi
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });

        // Init select2
        $('#sekolah').select2({
            placeholder: '-- Pilih Data --'
        });

        $('#kelas').select2({
            placeholder: '-- Pilih Data --'
        });

        // Load kelas jika sekolah sudah terpilih
        if ($('#sekolah').val()) {
            loadKelas($('#sekolah').val());
        }

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

                    $('#kelas')
                        .html(options)
                        .val(Idkelas)
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

  });
</script>
@endpush
