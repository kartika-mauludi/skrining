<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Angket Sosiometri Nobull</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f2f2f2;
        }

        .header-title {
            background: black;
            color: white;
            font-weight: bold;
            font-size: 32px;
            letter-spacing: 2px;
            padding: 10px;
        }

        .section-box {
            border: 1px solid #ccc;
            padding: 15px;
            background: white;
        }

        .line-input {
            border: none;
            border-bottom: 1px solid black;
            width: 100%;
        }

        .line-input:focus {
            outline: none;
            box-shadow: none;
        }

        .question-box {
            border: 1px solid #ccc;
            min-height: 80px;
        }

        .small-note {
            font-size: 14px;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000 !important;
        }

        @media print {

            body {
                background: white !important;
            }

            .container {
                width: 100% !important;
                max-width: 100% !important;
            }

            .row {
                display: flex !important;
                flex-wrap: nowrap !important;
            }

            .col-md-8 {
                width: 65% !important;
            }

            .col-md-4 {
                width: 35% !important;
            }

            .section-box {
                border: 0px solid black !important;
            }

            .header-title {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            input {
                border: none !important;
                border-bottom: 1px solid black !important;
            }

            table {
                page-break-inside: avoid;
            }

          }
    </style>
</head>
<body>

<div class="container my-4">

    <!-- Judul -->
    <div class="text-center">
        <h5 class="fw-bold">ANGKET</h5>
        <div class="header-title">{{ $angket->nama_angket }}</div>
    </div>

    <div class="row mt-4">

        <!-- Kiri -->
        <div class="col-md-8">

            <div class="section-box">

                <h6 class="fw-bold">PETUNJUK :</h6>
                <p class="small-note mb-1">
                    * Jawablah pertanyaan-pertanyaan berikut ini dengan jujur.<br>
                    Abaikan / kosongkan jika tidak ada jawaban.
                </p>
                <p class="small-note">
                    * Jawaban diisi No urut / No absen saja teman kelas yang ada pada tabel disamping
                </p>

                <p class="fw-bold text-center">
                    KAMI MENJAMIN KERAHASIAAN JAWABAN, TIDAK PERLU RAGU DALAM MENJAWAB
                </p>

                <!-- Identitas -->
                <div class="row mb-2">
                    <div class="col-2">Nomor Absen</div>
                    <div class="col-9"><input type="text" class="line-input"></div>
                </div>

                <div class="row mb-2">
                    <div class="col-2">Nama</div>
                    <div class="col-9">: <input type="text" class="line-input"></div>
                </div>

                <div class="row mb-4">
                    <div class="col-2">Kelas</div>
                    <div class="col-9">: <input type="text" class="line-input"></div>
                </div>

                <!-- Pertanyaan -->
                <h6 class="fw-bold">
                    A. Tentang kejadian padaku
                    <span class="fst-italic">(Jawab jika ada salah satu)</span>
                </h6>
@foreach ($soals->where('tipe_soal','=','text') as $soal )
         <!-- Pertanyaan 1 -->
                <div class="mb-3">
                    <p>
                     {{ $loop->iteration }}.  {{ $soal->soal }}
                    </p>

                    <div class="row">
                        <div class="col-md-9">
                            <label>Alasan (singkat)</label>
                            <div class="col-12"><input type="text" class="line-input"></div>
                        </div>
                        <div class="col-md-3">
                            <label>Jawab:</label>
                            <input class="form-control border-3"></input>
                        </div>
                    </div>
                </div>
@endforeach

  <!-- Pertanyaan -->
<h6 class="fw-bold">
    B. Tentang Diriku
</h6>

@foreach ($soals->where('tipe_soal', 'range') as $soal )
                <!-- Pertanyaan 1 -->
                <div class="mb-3">
                    <p>
                    {{ $loop->iteration }}.  {{ $soal->soal }}
                    </p>
                    <div class="row">
                        <div class="col-md-12">
                             <div class="row text-center">
                                <div class="col-3">
                                    <input type="radio">
                                    <div>Tidak pernah</div>
                                </div>
                                <div class="col-3">
                                    <input type="radio">
                                    <div>Jarang</div>
                                </div>
                                <div class="col-3">
                                    <input type="radio">
                                    <div>Sering</div>
                                </div>
                                <div class="col-3">
                                    <input type="radio">
                                    <div>Selalu</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
@endforeach     
    </div>
</div>

        <!-- Kanan (Tabel Nama) -->
        <div class="col-md-4">
            <div class="section-box">
                <table class="table table-bordered text-center">
                    <thead class="table-secondary">
                        <tr>
                            <th>Nomor Absen</th>
                            <th>Nama</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $siswas as $siswa )
                           <tr><td>{{ $siswa->no_absen }}</td><td>{{ $siswa->nama_lengkap }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

</body>
<script>
window.onload = function () {

    setTimeout(function () {
        window.print();
    }, 500);

    window.onafterprint = function () {
        window.close();
    };

};
</script>
</html>