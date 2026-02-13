@extends('guru.layout.index')

@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Report Matriks</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Report Matriks</li>
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
          <a href="{{ route('guru.report.matriks') }}" class="btn btn-sm btn-secondary">Reset</a>
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
                    <div class="alert alert-warning fade show text-center" role="alert">
                        Belum ada data angket yang masuk di kelas ini.
                    </div>
                  @else
                    @php
                        $matrix = [];

                        foreach ($reports as $r) {
                            $matrix[$r->id_siswa_pelapor][$r->id_siswa_terlapor] = true;
                        }
                    @endphp
                    
                    <table class="sociomatrix">
                        <thead>
                            <tr>
                                <th>Siswa</th>
                                @foreach ($siswa as $col)
                                    <th>{{ $col->nama_lengkap }}</th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($siswa as $row)
                                <tr>
                                    <th class="text-start">{{ $row->nama_lengkap }}</th>

                                    @foreach ($siswa as $col)
                                        @if ($row->id === $col->id)
                                            <td class="self">–</td>
                                        @elseif (!empty($matrix[$row->id][$col->id]))
                                            <td class="checked">✓</td>
                                        @else
                                            <td></td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                  @endif
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
