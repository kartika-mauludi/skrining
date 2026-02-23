<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report Pelaku</title>

    <style>
        @page {
            margin: 15px 20px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #000;
        }

        h1, h2, h3, h4, h5 {
            margin: 0;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        .section {
            margin-bottom: 8px;
        }

        .box-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .border {
            border: 1px solid #000;
        }

        .box {
            border: 1px solid #000;
            padding: 6px;
            margin-bottom: 6px;
        }

        .header-line {
            border-top: 2px solid #000;
            border-bottom: 1px solid #000;
            margin: 6px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 3px;
            vertical-align: top;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000;
        }

        .col-50 {
            width: 50%;
            float: left;
        }

        .col-33 {
            width: 33.333%;
            float: left;
        }

        .clearfix {
            clear: both;
        }

        img {
            max-width: 100%;
        }

        .chart {
            height: 150px;
            text-align: center;
        }
    </style>
</head>
<body onload="window.print()">

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
        <h4><strong>( Sebagai Pelaku )</strong></h4>
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
                @if(!empty($historyImage))
                    <img src="{{ $historyImage }}" style="height: 100%; width: auto">
                @endif
            </div>
        </div>

        <div class="col-50 box">
            <div class="text-center"><strong>Gauge</strong></div>
            <div class="chart">
                @if(!empty($gaugeImage))
                    <img src="{{ $gaugeImage }}" style="height: 100%; width: auto">
                @endif
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

    {{-- KRITERIA --}}
    <div class="box-container">
        <div class="col-50 box">
            <div class="text-center"><strong>Kriteria Perundungan</strong></div>
            <div class="chart">
                @if(!empty($kategoriImage))
                    <img src="{{ $kategoriImage }}" style="height: 100%; width: auto">
                @endif
            </div>
        </div>

        <div class="col-50 box">
            <div class="text-center"><strong>Kriteria Perundungan Cyber</strong></div>
            <div class="chart">
                @if(!empty($cyberImage))
                    <img src="{{ $cyberImage }}" style="height: 100%; width: auto">
                @endif
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

    {{-- KEJADIAN + TOTAL --}}
    <div class="section">
        <table>
            <tr>
                <td width="40%" class="box">
                    <div class="text-center"><strong>Kejadian Perundungan</strong></div>
                    <table class="table-bordered text-center" style="margin-top:5px;">
                        <tr>
                            <th>Sosial Media</th>
                            <th>Game</th>
                        </tr>
                        <tr>
                            <td>{{ $locationCount['sosmed'] ?? 0 }}</td>
                            <td>{{ $locationCount['game'] ?? 0 }}</td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <th>Lainnya</th>
                        </tr>
                        <tr>
                            <td>{{ $locationCount['lingkungan kelas'] ?? 0 }}</td>
                            <td>{{ $locationCount['lainnya'] ?? 0 }}</td>
                        </tr>
                    </table>
                </td>

                <td width="30%" class="box text-center">
                    <strong>Σ Aduan Sebagai Pelaku</strong>
                    <h2 style="margin-top:15px;">{{ $countAsPelaku }}</h2>
                </td>

                <td width="30%" class="box text-center">
                    <strong>Σ Kecenderungan Sikap</strong>
                    <h2 style="margin-top:15px;">{{ $countSikap }}</h2>
                </td>
            </tr>
        </table>
    </div>

    {{-- TEMAN YANG MENGADUKAN --}}
    <div class="box section">
        <strong>Teman yang mengadukan saya:</strong><br>
        Verbal: {{ $reportReasons['verbal']->pluck('korban')->implode(', ') ?? '-' }}<br>
        Fisik: {{ $reportReasons['fisik']->pluck('korban')->implode(', ') ?? '-' }}<br>
        Sosial: {{ $reportReasons['sosial']->pluck('korban')->implode(', ') ?? '-' }}
    </div>

    {{-- ALASAN --}}
    <div class="box section">
        <strong>Alasan teman mengadukan:</strong><br>
        Verbal: {{ $reportReasons['verbal']->pluck('alasan')->implode(', ') ?? '-' }}<br>
        Fisik: {{ $reportReasons['fisik']->pluck('alasan')->implode(', ') ?? '-' }}<br>
        Sosial: {{ $reportReasons['sosial']->pluck('alasan')->implode(', ') ?? '-' }}
    </div>

    {{-- FEEDBACK --}}
    <div class="box">
        <strong>Jika aku melakukan perundungan:</strong>
        <ol style="margin-top:4px;">
            @foreach ($feedbacks as $feedback)
                <li>{!! $feedback->feedback_deskripsi !!}</li>
            @endforeach
        </ol>
    </div>

</body>
</html>
