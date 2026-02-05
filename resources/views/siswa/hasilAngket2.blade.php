@extends('siswa.layout.index')

@section('content')


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>NOBUL - Sebagai Korban</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
    </style>
</head>
<body>

<div class="container my-4">
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
              <div class="col-md-5 text-center">
                     <div class="card">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h6 class="card-title">History</h6>
                </div>
              </div>
              <div class="card-body">
                <div class="position-relative mb-4">
                  <canvas id="visitors-chart" height="100"></canvas>
                </div>

              </div>
            </div>
                </div>
            <div class="col-md-4">
                <div class="box">
                    <div class="box-title">Perlakuan Perundungan</div>
                    <table class="table table-bordered text-center mb-0">
                        <tr>
                            <th>Sosial Media</th>
                            <th>Game</th>
                           
                        </tr>
                        <tr>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <th>Lain lain</th>
                        </tr>
                        <tr>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- <div class="col-md-3">
                <div class="box text-center">
                    <div class="box-title">Σ</div>
                    <h1 class="fw-bold">0</h1>
                    <div>Pelaku yang diadukan</div>
                </div>
            </div> -->

              <div class="col-md-3">
                <div class="box text-center" style="background-color: #37F713;">
                    <div class="box-title">Status</div>
                    <h1 class="fw-bold">Aman</h1>
                    <!-- <div>Pelaku yang diadukan</div> -->
                </div>
            </div>

          
        </div>

        <!-- DETAIL -->
        <div class="box mb-3">
            <strong>Tempat Perundungan :</strong>
            <div class="row mt-2">
                <div class="col">Sosial Media: <strong>0</strong></div>
                <div class="col">Game: <strong>0</strong></div>
                <div class="col text-danger">Kelas: <strong>0</strong></div>
                <div class="col text-danger">Lain-lain: <strong>0</strong></div>
            </div>
        </div>
<!-- 
        <div class="box mb-3">
            <strong>Teman yang saya adukan:</strong><br>
            Verbal:<br>
            Fisik:<br>
            Sosial:<br>
            Cyber:
        </div>

        <div class="box mb-3">
            <strong>Alasan saya mengadukan:</strong><br>
            Verbal:<br>
            Fisik:<br>
            Sosial:<br>
            Cyber:
        </div> -->

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

@endsection

@push('script')
<script>
document.addEventListener("DOMContentLoaded", function () {

  const ctx = document.getElementById('visitors-chart').getContext('2d');

  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Tes 1', ' Tes 2', 'Tes 3', 'Tes 4'],
      datasets: [
        {
          label: 'This Week',
          data: [100, 120, 170, 165],
          borderColor: '#007bff',
          backgroundColor: 'transparent',
          pointBackgroundColor: '#007bff',
          pointBorderColor: '#007bff',
          pointRadius: 4,
          borderWidth: 3,
          tension: 0.4
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
       legend: {
          display: false, // legend kita bikin manual di bawah chart
          position: 'bottom'
        },
      plugins: {
       
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            drawBorder: false
          }
        },
        x: {
          grid: {
            display: false
          }
        }
      }
    }
  });

});

</script>



@endpush