{{-- HEADER SEKOLAH --}}
<div class="text-center section">
    <h4><strong>Dinas Pendidikan Provinsi Nusantara</strong></h4>
    <h4><strong>{{ $kelas->sekolah->nama_sekolah }}</strong></h4>
    <div>
        {{ $kelas->sekolah->alamat_lengkap }} <br>
        Telp: {{ $kelas->sekolah->no_tlp }} |
        Website: {{ $kelas->sekolah->website }} |
        Email: {{ $kelas->sekolah->email }}
    </div>
</div>

<div class="header-line"></div>

<div class="text-center section">
    <h4><strong>( Sebagai Korban )</strong></h4>
</div>

{{-- IDENTITAS --}}
<table class="section">
    <tr>
        <td width="35%" class="text-right">
            <img src="{{ asset('assets-landing/img/user_icon.jpg') }}" width="70">
        </td>
        <td width="5%">
        </td>
        <td width="55%">
            <table>
                <tr>
                    <td width="100">Kelas</td>
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
        </td>
    </tr>
</table>

{{-- HISTORY + GAUGE --}}
<div class="box-container">
    <div class="col-50 box">
        <div class="text-center"><strong>History</strong></div>
        <div class="chart">
            <canvas id="history-chart-{{ $siswa->id }}"></canvas>
        </div>
    </div>

    <div class="col-50 box">
        <div class="text-center"><strong>Gauge</strong></div>
        <div class="chart">
            <canvas id="gauge-chart-{{ $siswa->id }}"></canvas>
        </div>
    </div>
</div>

<div class="clearfix"></div>

{{-- KRITERIA --}}
<div class="box-container">
    <div class="col-50 box">
        <div class="text-center"><strong>Kriteria Perundungan</strong></div>
        <div class="chart">
            <canvas id="kriteria-chart-{{ $siswa->id }}"></canvas>
        </div>
    </div>

    <div class="col-50 box">
        <div class="text-center"><strong>Kriteria Perundungan Cyber</strong></div>
        <div class="chart">
            <canvas id="cyber-chart-{{ $siswa->id }}"></canvas>
        </div>
    </div>

    <div class="clearfix"></div>
</div>

{{-- LOKASI --}}
<div class="box section">
    <div class="text-center"><strong>Kejadian Perundungan</strong></div>
    <table class="table-bordered text-center">
        <tr>
            <th>Sosial Media</th>
            <th>Game</th>
            <th>Kelas</th>
            <th>Lainnya</th>
        </tr>
        <tr>
            <td>{{ $locationCount['sosmed'] ?? 0 }}</td>
            <td>{{ $locationCount['game'] ?? 0 }}</td>
            <td>{{ $locationCount['lingkungan kelas'] ?? 0 }}</td>
            <td>{{ $locationCount['lainnya'] ?? 0 }}</td>
        </tr>
    </table>
</div>

{{-- PELAKU --}}
<div class="box section">
    <strong>Teman yang saya adukan:</strong><br>
    Verbal: {{ $reportReasons['verbal']->pluck('pelaku')->implode(', ') ?? '-' }}<br>
    Fisik: {{ $reportReasons['fisik']->pluck('pelaku')->implode(', ') ?? '-' }}<br>
    Sosial: {{ $reportReasons['sosial']->pluck('pelaku')->implode(', ') ?? '-' }}
</div>

{{-- ALASAN --}}
<div class="box section">
    <strong>Alasan saya mengadukan:</strong><br>
    Verbal: {{ $reportReasons['verbal']->pluck('alasan')->implode(', ') ?? '-' }}<br>
    Fisik: {{ $reportReasons['fisik']->pluck('alasan')->implode(', ') ?? '-' }}<br>
    Sosial: {{ $reportReasons['sosial']->pluck('alasan')->implode(', ') ?? '-' }}
</div>

{{-- FEEDBACK --}}
<div class="box">
    <strong>Jika aku mengalami perundungan:</strong>
    <ol style="margin-top:4px;">
        @foreach ($feedbacks as $feedback)
            <li>{!! $feedback->feedback_deskripsi !!}</li>
        @endforeach
    </ol>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@0.5.7/chartjs-plugin-annotation.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-gauge@0.3.0/dist/chartjs-gauge.min.js"></script>
<script src="https://unpkg.com/chartjs-plugin-datalabels@0.7.0/dist/chartjs-plugin-datalabels.js"></script>
<script>
    var colors = [
        '#3b82f6', // biru
        '#8b5cf6', // ungu
        '#ec4899'  // pink
    ];
    
    new Chart(document.getElementById('history-chart-{{ $siswa->id }}').getContext('2d'), {
        type: 'line',
        data: {
            labels: @json($skorKorbanAll).labels,
            datasets: @json($skorKorbanAll).datasets.map((ds, index) => ({
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
            maintainAspectRatio: true,
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

    new Chart(document.getElementById('kriteria-chart-{{ $siswa->id }}').getContext('2d'), {
        type: 'bar',
        data: {
            labels: @json($skorKorban).labels,
            datasets: @json($skorKorban).datasets.map((ds, index) => ({
                label: ds.label,
                data: ds.data,
                backgroundColor: colors[index % colors.length] + '33', // transparan
                borderColor: colors[index % colors.length],
                borderWidth: 1
            }))
        },
        options: {
            maintainAspectRatio: true,
            legend: {
                display: false
            },
            scales: {
                x: { offset: true },
                y: { beginAtZero: true }
            }
        }
    });

    new Chart(document.getElementById('cyber-chart-{{ $siswa->id }}').getContext('2d'), {
        type: 'bar',
        data: {
            labels: @json($skorKorbanCyber).labels,
            datasets: @json($skorKorbanCyber).datasets.map((ds, index) => ({
                label: ds.label,
                data: ds.data,
                backgroundColor: colors[index % colors.length] + '33', // transparan
                borderColor: colors[index % colors.length],
                borderWidth: 1
            }))
        },
        options: {
            maintainAspectRatio: true,
            legend: {
                display: false
            },
            scales: {
                x: { offset: true },
                y: { beginAtZero: true }
            }
        }
    });

    new Chart(document.getElementById("gauge-chart-{{ $siswa->id }}").getContext("2d"), {
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
            maintainAspectRatio: true,
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
</script>