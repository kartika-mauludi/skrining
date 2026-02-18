@extends('admin.layout.index')

@section('content')


@push('style')
<style>
</style>
@endpush
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Report</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
              <li class="breadcrumb-item active">Report</li>
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
                <select name="kelas[]" id="kelas" class="form-control select2-selection__placeholder" multiple required>
                  <option value=""></option>
                </select>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-2">
              <div class="form-group">
                <label for="kelas" class="form-label">Bulan</label>
                   {{-- Bulan --}}
                    <select name="bulan" class="form-control form-control-sm">
                        <option value="">-- Pilih Bulan --</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" 
                                {{ request('bulan') == $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
              </div>
            </div>

              <div class="col-12 col-sm-6 col-md-2">
                <div class="form-group">
                  <label for="kelas" class="form-label">Tahun</label>
                    <select name="tahun" class="form-control form-control-sm">
                        @for ($y = date('Y'); $y >= 2023; $y--)
                            <option value="{{ $y }}" 
                                {{ request('tahun') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-2">
              <div class="form-group">
                <label for="kelas" class="form-label">Minggu</label>
                  <select name="minggu" class="form-control form-control-sm">
                      <option value="">-- Semua Minggu --</option>
                      @for ($m = 1; $m <= 5; $m++)
                          <option value="{{ $m }}"
                              {{ request('minggu') == $m ? 'selected' : '' }}>
                              Minggu {{ $m }}
                          </option>
                      @endfor
                  </select>
              </div>
            </div>
            
          </div>
        <a href="{{ route('report.export.csv', request()->all()) }}"
          class="btn btn-sm btn-success">
          Export CSV
        </a>
          <button class="btn btn-sm btn-info">Set Record</button>
          <a href="{{ route('admin.report') }}" class="btn btn-sm btn-secondary">Reset</a>
        </form>

        <hr>

        <!-- Main row -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                @isset($kelas)
                  <style>
                    .sociomatrix {
                        width: 100%;
                        border-collapse: collapse;
                        text-align: center;
                    }

                    .sociomatrix th,
                    .sociomatrix td {
                        border: 1px solid #ddd;
                        padding: 8px;
                        font-size: 14px;
                    }

                    .sociomatrix th {
                        background-color: #f4f6f9;
                        font-weight: 600;
                    }

                    .sociomatrix td.checked {
                        background-color: #fdecea;
                        color: #c82333;
                        font-weight: bold;
                    }

                    .sociomatrix td.self {
                        background-color: #eee;
                        color: #999;
                    }
                  </style>

                  @if ($reports->isEmpty())
                    <h5 class="text-center">Belum ada data angket yang masuk di kelas ini.</h5>
                  @else
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                @foreach ($soals as $soal)
                                    <th>{{ $loop->iteration }}</th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($reports as $row)
                                <tr>
                                  <th scope="row">{{ $loop->iteration }}</th>
                                  @foreach( $soals as $soal) 
                                   @php
                                      $jawaban = $row->jawaban
                                          ->where('soal_id', $soal->id)
                                          ->first();
                                  @endphp
                                      <td>{{ $jawaban->jawaban ?? '-' }}</td>
                                  @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                  @endif
                @else
                  <h5 class="text-center">Silahkan pilih sekolah dan kelas terlebih dahulu</h5>
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
          placeholder: '-- Pilih Data --',
          width: '100%',
          closeOnSelect: false
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

          $.get(`{{ url('/admin/sekolah/${sekolahId}') }}`)
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
