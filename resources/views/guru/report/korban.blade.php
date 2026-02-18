@extends('guru.layout.index')

@section('content')
<style>
    body {
        background: #f2f2f2ff;
        font-size: 14px;
    }
    .sheet {
        background: #ffffffff;
        padding: 20px;
        border: 1px solid #000;
    }
    .header-line {
        border-top: 2px solid #000;
        border-bottom: 1px solid #000;
        margin: 10px 0;
    }
    .box {
        border: 1px solid #000;
        padding: 10px;
        height: 100%;
    }
    .box-title {
        font-weight: bold;
        text-align: center;
        margin-bottom: 5px;
    }
    .text-small {
        font-size: 12px;
    }
    canvas {
    -moz-user-select: none;
    -webkit-user-select: none;
    -ms-user-select: none;
    }
</style>

<div class="content-wrapper" style="background-color:rgba(162, 246, 186, 0.7);">
    <section class="content-header" >
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Report Siswa Sebagai Korban</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('guru.report') }}">Report Siswa</a></li>
                        <li class="breadcrumb-item active">Report Siswa Sebagai Korban</li>
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
                      <div class="card-header">
                        <a href="{{ route('guru.report') }}" class="btn btn-sm btn-secondary">Kembali</a>
                        <button type="button" id="btnExport" class="btn btn-sm btn-primary">Cetak</button>

                        <form action="{{ route('guru.report.korban.print', $siswa->id) }}" id="formPrint" method="post"  target="_blank">
                            @csrf

                            <input type="hidden" name="history_image" id="history_image">
                            <input type="hidden" name="kategori_image" id="kategori_image">
                            <input type="hidden" name="cyber_image" id="cyber_image">
                            <input type="hidden" name="gauge_image" id="gauge_image">
                        </form>

                      </div>
                      <div class="card-body">
                        <div class="sheet">
                            <!-- HEADER -->
                            <div class="text-center">
                                <h6 class="fw-bold mb-1">Dinas Pendidikan Provinsi Nusantara</h6>
                                <h6 class="fw-bold mb-1"></h6>
                                <h5 class="fw-bold mb-1">{{ $kelas->sekolah->nama_sekolah }}</h5>
                                <div class="text-small">
                                    Alamat : {{ $kelas->sekolah->alamat_lengkap }}<br>
                                    No. Telephone : {{ $kelas->sekolah->no_tlp }} Website : {{ $kelas->sekolah->website }} Email : {{ $kelas->sekolah->email }}
                                </div>
                            </div>

                            <div class="header-line"></div>

                            <!-- JUDUL -->
                            <div class="text-center mb-3">
                                <h5 class="fw-bold">( Sebagai Korban )</h5>
                            </div>

                            <!-- IDENTITAS -->
                            <div class="row mb-3">
                                <div class="col-3 text-center">
                                    <img src="https://cdn-icons-png.flaticon.com/512/4140/4140048.png"
                                        class="img-fluid" width="100">
                                </div>
                                <div class="col-9">
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td width="120">Kelas</td>
                                            <td>: {{ $kelas->nama_kelas }}</td>
                                        </tr>
                                        <tr>
                                            <td>No Induk</td>
                                            <td>: {{ $siswa->nis }}</td>
                                        </tr>
                                        <tr>
                                            <td>Nama</td>
                                            <td>: {{ $siswa->nama_lengkap }}</td>
                                        </tr>
                                        <tr>
                                            <td>Jenis Kelamin</td>
                                            <td>: {{ $siswa->jk }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- SKOR -->
                            <div class="row mb-3">
                                <div class="col-md-8 text-center">
                                    <div class="card">
                                        <div class="card-header border-0">
                                            <div class="d-flex justify-content-between">
                                            <h6 class="card-title">History</h6>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="position-relative mb-4">
                                            <canvas id="history-chart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                  </div>

                                  <div class="col-md-4">
                                    <div id="canvas-holder" style="width:100%">
                                        <canvas id="gauge-chart"></canvas>
                                    </div>
                                </div>
                              
                            </div>

                            <!-- DETAIL -->
                            <div class="row mb-3">
                                <div class="col-md-6 text-center">
                                    <div class="card">
                                        <div class="card-header border-0">
                                            <div class="d-flex justify-content-between">
                                            <h6 class="card-title">Kriteria Perundungan</h6>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="position-relative mb-4">
                                            <canvas id="kriteria-chart" height="100"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                  </div>
                                <div class="col-md-6 text-center">
                                    <div class="card">
                                        <div class="card-header border-0">
                                            <div class="d-flex justify-content-between">
                                            <h6 class="card-title">Kriteria Perundungan Cyber</h6>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="position-relative mb-4">
                                            <canvas id="cyber-chart" height="100"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                  </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="box">
                                    <div class="box-title">Kejadian Perundungan</div>
                                    <table class="table table-bordered text-center mb-0">
                                        <tr>
                                            <th>Sosial Media</th>
                                            <th>Game</th>
                                            <th>Kelas</th>
                                            <th>Lain lain</th>
                                          
                                        </tr>
                                        <tr>
                                            <td>{{ $locationCount['sosmed'] }}</td>
                                            <td>{{ $locationCount['game'] }}</td>
                                            <td>{{ $locationCount['lingkungan kelas'] }}</td>
                                            <td>{{ $locationCount['lainnya'] }}</td>
                                        </tr>
                                      
                                    </table>
                                </div>
                            </div>

                            <div class="box mb-3">
                                <strong>Teman yang saya adukan:</strong><br>
                                Verbal: <span class="text-muted">{{ $reportReasons['verbal']->pluck('pelaku')->implode(', ') }}</span><br>
                                Fisik:  <span class="text-muted">{{ $reportReasons['fisik']->pluck('pelaku')->implode(', ') }}</span><br>
                                Sosial: <span class="text-muted">{{ $reportReasons['sosial']->pluck('pelaku')->implode(', ') }}</span><br>
                            </div>

                            <div class="box mb-3">
                                <strong>Alasan saya mengadukan:</strong><br>
                                Verbal: <span class="text-muted">{{ $reportReasons['verbal']->pluck('alasan')->implode(', ') }}</span><br>
                                Fisik:  <span class="text-muted">{{ $reportReasons['fisik']->pluck('alasan')->implode(', ') }}</span><br>
                                Sosial: <span class="text-muted">{{ $reportReasons['sosial']->pluck('alasan')->implode(', ') }}</span><br>
                            </div>

                            <div class="box">
                                <strong>Jika aku mengalami perundungan</strong>
                                <ol class="mt-2">
                                    @foreach ($feedbacks as $feedback)
                                        <li>{!! $feedback->feedback_deskripsi !!}</li>
                                    @endforeach
                                </ol>
                            </div>

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

document.addEventListener("DOMContentLoaded", function () {  
    const history = document.getElementById('history-chart').getContext('2d');
    const kategori = document.getElementById('kriteria-chart').getContext('2d'); 
    const cyber = document.getElementById('cyber-chart').getContext('2d');
    const gaugeChart = document.getElementById("gauge-chart").getContext("2d");

    const chartData = @json($skorKorbanAll);
    const chartData1 = @json($skorKorban);
    const chartData2 = @json($skorKorbanCyber);
    
    const colors = [
        '#3b82f6', // biru
        '#8b5cf6', // ungu
        '#ec4899'  // pink
    ];
    
    new Chart(history, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: chartData.datasets.map((ds, index) => ({
                label: ds.label,
                data: ds.data,
                borderColor: colors[index % colors.length],
                backgroundColor: colors[index % colors.length] + '33', // transparan
                pointBackgroundColor: colors[index % colors.length],
                borderWidth: 2,
                tension: 0.5,
                fill: false
            }))
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            annotation: {
                drawTime: 'beforeDatasetsDraw',
                annotations: [
                {
                    type: 'box',
                    yScaleID: 'y-axis-0',
                    yMin: 0,
                    yMax: 20,
                    backgroundColor: 'rgba(36, 198, 74, 0.2)',
                    borderWidth: 0
                },
                {
                    type: 'box',
                    yScaleID: 'y-axis-0',
                    yMin: 20,
                    yMax: 50,
                    backgroundColor: 'rgba(214, 242, 90, 0.2)',
                    borderWidth: 0
                },
                {
                    type: 'box',
                    yScaleID: 'y-axis-0',
                    yMin: 50,
                    yMax: 100,
                    backgroundColor: 'rgba(230, 34, 54, 0.2)',
                    borderWidth: 0
                }]
            },
            scales: {
                yAxes: [{
                    id: 'y-axis-0',
                    ticks: {
                        min: 0,
                        max: 100,
                        padding: 10,
                        callback: function(value) {
                            if (value === 20) return 'Aman';
                            if (value === 50) return 'Hati-hati';
                            if (value === 100) return 'Bahaya';
                            return '';
                        }
                    },
                    afterBuildTicks: function(scale) {
                        scale.ticks = [20, 50, 100];
                    }
                    }],
                xAxes: [{
                    gridLines: {
                        display: false
                    }
                }]
            }
        },
    });

    new Chart(kategori, {
        type: 'bar',
        data: {
            labels: chartData1.labels,
            datasets: chartData1.datasets.map((ds, index) => ({
                label: ds.label,
                data: ds.data,
                backgroundColor: colors[index % colors.length] + '33', // transparan
                borderColor: colors[index % colors.length],
                borderWidth: 1
            }))
        },
        options: {
            responsive: true,
            legend: {
                display: false
            },
            scales: {
                x: { offset: true },
                y: { beginAtZero: true }
            }
        }
    });

    new Chart(cyber, {
        type: 'bar',
        data: {
            labels: chartData2.labels,
            datasets: chartData2.datasets.map((ds, index) => ({
                label: ds.label,
                data: ds.data,
                backgroundColor: colors[index % colors.length] + '33', // transparan
                borderColor: colors[index % colors.length],
                borderWidth: 1
            }))
        },
        options: {
            responsive: true,
            legend: {
                display: false
            },
            scales: {
                x: { offset: true },
                y: { beginAtZero: true }
            }
        }
    });

    new Chart(gaugeChart, {
        type: "gauge",
        data: {
            labels: ["Aman", "Hati Hati", "Bahaya"],
            datasets: [
            {
                data: [20, 50, 100],
                minValue: 0,
                maxValue: 100,
                value: @json($gaugeMeter),
                backgroundColor: ["#09e63cff", "#d4f544ff", "#cb3600ff"],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            layout: {
                padding: {
                    bottom: 35
                }
            },
            needle: {
                radiusPercentage: 2,
                widthPercentage: 2.2,
                lengthPercentage: 50,
                color: "#FF6112"
            },
            valueLabel: {
                display: true,
                formatter: (value) => {
                    return Math.round(value);
                },
                color: 'rgba(255, 255, 255, 1)',
                backgroundColor: 'rgba(0, 0, 0, 0.7)',
                borderRadius: 5,
                padding: {
                    top: 10,
                    bottom: 10,
                    left: 12,
                    right: 12
                }
            },
            plugins: {
                datalabels: {
                    display: true,
                    formatter:  function (value, context) {
                        return context.chart.data.labels[context.dataIndex];
                    },
                    color: function (context) {
                        return context.dataset.backgroundColor;
                    },
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    borderRadius: 5,
                    padding: {
                        top: 3,
                        bottom: 3,
                        left: 5,
                        right: 5
                    },
                    font: {
                        size: 12,
                        weight: 600
                    }
                }
            }
        }
    });

    function generateHighResChart(type, data, options, width = 1400, height = 700) {
        const tempCanvas = document.createElement('canvas');
        tempCanvas.width = width;
        tempCanvas.height = height;

        const ctx = tempCanvas.getContext('2d');

        const tempChart = new Chart(ctx, {
            type: type,
            data: data,
            options: Object.assign({}, options, {
                responsive: false,
                animation: false,
                devicePixelRatio: 2
            })
        });

        const image = tempChart.toBase64Image('image/png', 1.0);
        tempChart.destroy();

        return image;
    }

    $('#btnExport').on('click', function () {

        const charts = Object.values(Chart.instances);

        charts.forEach(function(chart) {
            chart.options.devicePixelRatio = 2;
            chart.update();
        });

        setTimeout(function() {

            $('#history_image').val(Chart.instances[0].toBase64Image());
            $('#kategori_image').val(Chart.instances[1].toBase64Image());
            $('#cyber_image').val(Chart.instances[2].toBase64Image());
            $('#gauge_image').val(Chart.instances[3].toBase64Image());

            charts.forEach(function(chart) {
                chart.options.devicePixelRatio = 1;
                chart.update();
            });

            $('#formPrint').trigger('submit');

        }, 300);
    });


});

</script>

@endpush