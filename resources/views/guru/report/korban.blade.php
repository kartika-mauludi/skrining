@extends('guru.layout.index')

@section('content')
<style>
    body {
        background: #f2f2f2;
        font-size: 14px;
    }
    .sheet {
        background: #fff;
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

<div class="content-wrapper">
    <section class="content-header">
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
                                            <canvas id="history-chart" height="100"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                  </div>
                              

                                <!-- <div class="col-md-3">
                                    <div class="box text-center">
                                        <div class="box-title">Σ</div>
                                        <h1 class="fw-bold">0</h1>
                                        <div>Pelaku yang diadukan</div>
                                    </div>
                                </div> -->

                                  <div class="col-md-4">
                                    <!-- <div class="box text-center" style="background-color: #37F713;">
                                        <div class="box-title">Status</div>
                                        <h1 class="fw-bold">Aman</h1> -->
                                        <!-- <div>Pelaku yang diadukan</div> -->
                                    <!-- </div> -->
                                    <div id="canvas-holder" style="width:100%">
                                        <canvas id="chart"></canvas>
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
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                        </tr>
                                      
                                    </table>
                                </div>
                            </div>

                            <div class="box mb-3">
                                <strong>Teman yang saya adukan:</strong><br>
                                Verbal:<br>
                                Fisik:<br>
                                Sosial:<br>
                            </div>

                            <div class="box mb-3">
                                <strong>Alasan saya mengadukan:</strong><br>
                                Verbal:<br>
                                Fisik:<br>
                                Sosial:<br>
                            </div>

                            <div class="box">
                                <strong>Jika aku mengalami perundungan</strong>
                                <ol class="mt-2">
                                    <li>Tetaplah bersikap tenang, misalnya dengan ambil nafas dalam-dalam selama 1 menit kemudian hembuskan keluar.</li>
                                    <li>Sembunyikan kemarahan atau kesedihanmu di depan perundung.</li>
                                    <li>Berdiri tegak, angkat kepalamu, pandang pelaku dengan tegas, hadapi pelaku dengan tenang atau tinggalkan perundung.</li>
                                    <li>Tanyakan permasalahan atau tolak permintaan pelaku dengan sopan.</li>
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
 if (window['chartjs-plugin-annotation']) {
    Chart.plugins.register(window['chartjs-plugin-annotation']);
  }

   console.log(Chart.plugins.getAll().map(p => p.id));
 
  const history = document.getElementById('history-chart').getContext('2d');
  new Chart(history, {
  type: 'line',
  data: {
    labels: ['Visual', 'Verbal', 'Sosial', 'Impersonation', 'Visual Sexual','Written verbal', 'Online Exclusion'],
    datasets: [{
      label: 'Tes 1',
      data: [100, 45, 30, 78, 60,20, 10],
      borderColor: '#007bff',
      pointBackgroundColor: '#007bff',
      pointBorderColor: '#007bff',
      pointRadius: 3,
      borderWidth: 2,
      lineTension: 0.4
    },{
      label: 'Tes 2',
      data: [90, 40, 40, 68, 20, 40, 90],
      borderColor: '#e01919ff',
      pointBackgroundColor: '#e01919ff',
      pointBorderColor: '#e01919ff',
      pointRadius: 3,
      borderWidth: 2,
      lineTension: 0.4
    }]
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
      yMin: 30,
      yMax: 30,
      backgroundColor: 'rgba(36, 198, 74, 1)',
      borderWidth: 0
    },
    {
      type: 'box',
      yScaleID: 'y-axis-0',
      yMin: 30,
      yMax: 70,
      backgroundColor: 'rgba(214, 242, 90, 1)',
      borderWidth: 0
    },
    {
      type: 'box',
      yScaleID: 'y-axis-0',
      yMin: 70,
      yMax: 100,
      backgroundColor: 'rgba(230, 34, 54, 1)',
      borderWidth: 0
    }
  ]
},

    scales: {
      yAxes: [{
        id: 'y-axis-0',
        ticks: {
            min: 0,
            max: 100,
            padding: 10,
            callback: function(value) {
            if (value === 30) return 'Aman';
            if (value === 70) return 'Hati-hati';
            if (value === 100) return 'Bahaya';
            return '';
            }
        },
        afterBuildTicks: function(scale) {
            scale.ticks = [30, 70, 100];
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


function KategoriConfig() {
  return {
    type: 'bar',
    data: {
      labels: ['Verbal', 'Fisik', 'Sosial'],
      datasets: [{
        label: 'My First Dataset',
        data: [65, 59, 80, 81, 56, 55, 40],
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(255, 159, 64, 0.2)',
          'rgba(255, 205, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(153, 102, 255, 0.2)',
          'rgba(201, 203, 207, 0.2)'
        ],
        borderColor: [
          'rgb(255, 99, 132)',
          'rgb(255, 159, 64)',
          'rgb(255, 205, 86)',
          'rgb(75, 192, 192)',
          'rgb(54, 162, 235)',
          'rgb(153, 102, 255)',
          'rgb(201, 203, 207)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
        legend: {
          display: false
        },
      scales: {
        x: { offset: true },
        y: {
            beginAtZero: true,
            min: 0,
            max: 200,
            ticks: {
                stepSize: 50,
                callback: function(value) {
                if (value <= 120) return 'Aman';
                if (value <= 160) return 'Hati-hati';
                return 'Bahaya';
                }
            }
            }
      }
    }
  };
}

function CyberConfig() {
  return {
    type: 'bar',
    data: {
      labels: ['Impersonation', 'Visual Sexual', 'Written Verbal', 'Online Exclusion'],
      datasets: [{
        label: 'My First Dataset',
        data: [65, 59, 80, 81, 56],
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(255, 159, 64, 0.2)',
          'rgba(255, 205, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(153, 102, 255, 0.2)',
          'rgba(201, 203, 207, 0.2)'
        ],
        borderColor: [
          'rgb(255, 99, 132)',
          'rgb(255, 159, 64)',
          'rgb(255, 205, 86)',
          'rgb(75, 192, 192)',
          'rgb(54, 162, 235)',
          'rgb(153, 102, 255)',
          'rgb(201, 203, 207)'
        ],
        borderWidth: 1
      }]
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
  };
}
const kategori = document.getElementById('kriteria-chart').getContext('2d'); 
const cyber = document.getElementById('cyber-chart').getContext('2d');

new Chart(kategori, KategoriConfig());
new Chart(cyber, CyberConfig());

});


var config = {
  type: "gauge",
  data: {
    datasets: [
      {
        data: [20, 40, 50],
        minValue: 10,
        value: 30,
        backgroundColor: ["#09e63cff", "#d4f544ff", "#cb3600ff"],
        borderWidth: 1
      }
    ]
  },
  options: {
    responsive: true,
    layout: {
      padding: {
        bottom: 30
      }
    },
    needle: {
      radiusPercentage: 2,
      widthPercentage: 2.2,
      lengthPercentage: 50,
      color: "#FF6112"
    }
  }
};

window.onload = function () {
  var ctx = document.getElementById("chart").getContext("2d");
  window.myGauge = new Chart(ctx, config);
};


</script>



@endpush