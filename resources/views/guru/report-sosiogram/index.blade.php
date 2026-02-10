@extends('guru.layout.index')

@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Report Sosiogram</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Report Sosiogram</li>
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
          <a href="{{ route('guru.report.sosiogram') }}" class="btn btn-sm btn-secondary">Reset</a>
        </form>

        <hr>

        <!-- Main row -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                @isset($kelas)
                  <style>
                    .sosiogram {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        grid-template-columns: 1fr 200px 1fr;
                        gap: 70px;
                        position: relative;
                    }

                    .sosiogram-column {
                        display: flex;
                        flex-direction: column;
                        gap: 12px;
                        width: max-content;
                    }

                    .node {
                        padding: 8px 12px;
                        background: #f4f6f9;
                        border: 1px solid #ddd;
                        border-radius: 6px;
                        text-align: center;
                        font-size: 14px;
                        position: relative;
                    }

                    .node.pelapor {
                        background: #e3f2fd;
                    }

                    .node.terlapor {
                        background: #fdecea;
                    }

                    .lines {
                        position: absolute;
                        inset: 0;
                        pointer-events: none;
                    }
                  </style>

                  @if ($reports->isEmpty())
                    <h5 class="text-center">Belum ada data angket yang masuk di kelas ini.</h5>
                  @else
                    <div class="row">
                      <div class="col-6 col-sm-3">
                          <div class="card">
                            <div class="card-header bg-info">
                              <h6 class="font-weight-bold text-center">Pelaku Tertinggi</h6>
                            </div>
                            <div class="card-body">
                              <p class="my-3 text-center">{{ $mostReported->siswapelaku->nama_lengkap }}</p>
                            </div>
                            <div class="card-footer">
                              <h6 class="text-muted text-center">Jumlah Aduan: {{ "$mostReported->count Kali" }}</h6>
                            </div>
                          </div>
                          <div class="card">
                            <div class="card-header bg-info">
                              <h6 class="font-weight-bold text-center">Saling Melaporkan</h6>
                            </div>
                            <div class="card-body">
                              <ul>
                                  @foreach ($mutualReporteds as $pair)
                                    <li>
                                        {{ $pair['siswa_a']->nama_lengkap }}
                                        ↔
                                        {{ $pair['siswa_b']->nama_lengkap }}
                                    </li>
                                  @endforeach
                              </ul>
                            </div>
                          </div>
                          <div class="card">
                            <div class="card-header bg-info">
                              <h6 class="font-weight-bold text-center">Tidak Diadukan</h6>
                            </div>
                            <div class="card-body">
                              <ul>
                                @foreach ($notReporteds as $notReported)
                                  <li>{{ $notReported->nama_lengkap }}</li>
                                @endforeach
                              </ul>
                            </div>
                          </div>
                      </div>
                      <div class="col-6 col-sm-9">
                        <div class="sosiogram" id="sosiogram">

                            <!-- KIRI : Siswa (Pelapor) -->
                            <div class="sosiogram-column">
                                <span>Korban</span>
                                @foreach($siswa as $s)
                                    <div class="node pelapor"
                                        id="pelapor-{{ $s->id }}">
                                        {{ $s->no_absen }}
                                    </div>
                                @endforeach
                            </div>

                            <!-- TENGAH : SVG GARIS -->
                            <svg class="lines" id="lines"></svg>

                            <!-- KANAN : Siswa (Terlapor) -->
                            <div class="sosiogram-column">
                                <span>Pelaku</span>
                                @foreach($siswa as $s)
                                    <div class="node terlapor"
                                        id="terlapor-{{ $s->id }}">
                                        {{ $s->no_absen }}
                                    </div>
                                @endforeach
                            </div>

                            <div class="sosiogram-column">
                              <span>Identitas</span>
                              @foreach ($siswa as $s)
                                  <div class="node d-flex justify-content-between align-items-center">
                                    <p class="m-0 ml-2">{{ "$s->no_absen. $s->nama_lengkap" }}</p>
                                    <p class="m-0 ml-5">{{ $s->jk }}</p>
                                  </div>
                              @endforeach
                            </div>

                        </div>
                      </div>
                    </div>
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
      $(document).ready(function () {
        var Idkelas = @json($request['kelas'] ?? 0)

        getDataKelas();

        function getDataKelas() {
            const id = $('#sekolah').val();
  
            $.get(`/guru/sekolah/${id}`, function (data, status) {
                if (status != 'success') {
                    swal.fire('Kesalahan sistem','Silahkan hubungi administrator','error')
                    return
                }
                
                let selected;
                const parent = $('#kelas');
                const kelas  = data['data']['kelas'];
                let child = '<option value=""></option>'; 
  
                kelas.forEach(element => {

                    if (element.id == Idkelas) {
                      selected = 'selected';
                    } else {
                      selected = '';
                    }

                    child += `<option value="${element.id}" ${selected}>${element.nama_kelas}</option>`;
                });
                
                parent.html(child).select2({
                    placeholder: '-- Pilih Data --',
                });
            })
        }
  
        $('#sekolah').select2({
          placeholder: '-- Pilih Data --'
        });
  
        $('#sekolah').on('change', function() {
          getDataKelas()
        });
      })
    </script>

    <script>
      $(function () {

          const $container = $('#sosiogram');
          const $svg = $('#lines');

          const reports = @json($reports ?? 0);

          const rect = $container[0].getBoundingClientRect();
          $svg
              .attr('width', rect.width)
              .attr('height', rect.height);

          // Arrow marker
          $svg.append(`
              <defs>
                  <marker id="arrow" markerWidth="10" markerHeight="10"
                      refX="10" refY="3" orient="auto" markerUnits="strokeWidth">
                      <path d="M0,0 L0,6 L9,3 z" fill="#dc3545"/>
                  </marker>
              </defs>
          `);

          $.each(reports, function (_, r) {

              const $from = $('#pelapor-' + r.siswa_id);
              const $to   = $('#terlapor-' + r.id_siswa_pelaku);

              if (!$from.length || !$to.length) return;

              const f = $from[0].getBoundingClientRect();
              const t = $to[0].getBoundingClientRect();

              const x1 = f.right - rect.left;
              const y1 = f.top + f.height / 2 - rect.top;

              const x2 = t.left - rect.left;
              const y2 = t.top + t.height / 2 - rect.top;

              const line = document.createElementNS(
                  'http://www.w3.org/2000/svg',
                  'line'
              );

              $(line).attr({
                  x1, y1, x2, y2,
                  stroke: '#dc3545',
                  'stroke-width': 2,
                  'marker-end': 'url(#arrow)'
              });

              $svg.append(line);
          });

      });
    </script>

@endpush