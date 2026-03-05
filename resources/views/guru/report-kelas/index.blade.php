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
                                <table class="table table-sm table-bordered">
                                    <thead class="bg-dark text-center">
                                        <tr>
                                            <th style="vertical-align: middle">Nomor</th>
                                            <th style="vertical-align: middle">Nomor Induk</th>
                                            <th style="vertical-align: middle">Nama</th>
                                            <th style="vertical-align: middle">∑ Laporan</th>
                                            <th style="vertical-align: middle">Nama Pelapor</th>
                                            <th style="vertical-align: middle">Alasan</th>
                                            <th style="vertical-align: middle">Saling Melapor</th>
                                            <th style="vertical-align: middle">Kecenderungan <br> Sebagai Pelaku</th>
                                            <th style="vertical-align: middle">Kecenderungan <br> Sebagai Korban</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-light">
                                        @foreach ($siswa as $datasiswa)
                                            <tr>
                                                <td class=" text-center">{{ $datasiswa->no_absen }}</td>
                                                <td class=" text-center">{{ $datasiswa->nis ?? '-' }}</td>
                                                <td>{{ $datasiswa->nama_lengkap }}</td>
                                                <td class=" text-center">{{ $datasiswa->sebagaipelaku->count() }}</td>
                                                <td>
                                                    @foreach ($datasiswa->sebagaipelaku as $sebagaipelaku)
                                                    {{ $sebagaipelaku->siswa->nama_lengkap . ";" }}
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach ($datasiswa->sebagaipelaku as $sebagaipelaku)
                                                    {{ $sebagaipelaku->alasan . ";" }}
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach ($mutualBySiswa[$datasiswa->id] ?? [] as $partnerId)
                                                        {{ $datasiswa->nama_lengkap }} - {{ $siswa->firstWhere('id', $partnerId)->nama_lengkap ?? '' }};
                                                    @endforeach
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $skorAll = App\Helpers\HitungSkor::hitungPelakuPerIndikator(
                                                            $datasiswa->id,
                                                            $indikator
                                                        );

                                                        $allValues = collect($skorAll['datasets'])
                                                        ->pluck('data')
                                                        ->flatten()
                                                        ->filter(fn ($v) => $v > 0);
                                                    @endphp

                                                    {{ $allValues->avg() ?? 0 }}
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $skorAll = App\Helpers\HitungSkor::hitungKorbanPerIndikator(
                                                            $datasiswa->id,
                                                            $indikator
                                                        );

                                                        $allValues = collect($skorAll['datasets'])
                                                        ->pluck('data')
                                                        ->flatten()
                                                        ->filter(fn ($v) => $v > 0);
                                                    @endphp

                                                    {{ $allValues->avg() ?? 0 }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
<script>
  $(function () {

      const Idkelas = @json($request['kelas'] ?? null);

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
