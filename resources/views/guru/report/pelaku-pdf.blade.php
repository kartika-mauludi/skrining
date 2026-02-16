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

        img {
            max-width: 100%;
        }

        .chart-img {
            height: 140px;
        }

        .gauge-img {
            height: 130px;
        }

    </style>
</head>
<body>

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
            <td width="20%" class="text-center">
                <img src="https://cdn-icons-png.flaticon.com/512/4140/4140048.png" width="70">
            </td>
            <td width="80%">
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
    <div class="section">
        <table>
            <tr>
                <td width="70%" class="box text-center">
                    <strong>History</strong>
                    <div style="margin-top:5px;">
                        @if(!empty($historyImage))
                            <img src="{{ $historyImage }}" class="chart-img">
                        @endif
                    </div>
                </td>

                <td width="30%" class="box text-center">
                    <strong>Status</strong>
                    <div style="margin-top:5px;">
                        @if(!empty($gaugeImage))
                            <img src="{{ $gaugeImage }}" class="gauge-img">
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- KRITERIA --}}
    <div class="section">
        <table>
            <tr>
                <td width="50%" class="box text-center">
                    <strong>Kriteria Perundungan</strong>
                    <div style="margin-top:5px;">
                        @if(!empty($kategoriImage))
                            <img src="{{ $kategoriImage }}" class="chart-img">
                        @endif
                    </div>
                </td>

                <td width="50%" class="box text-center">
                    <strong>Kriteria Perundungan Cyber</strong>
                    <div style="margin-top:5px;">
                        @if(!empty($cyberImage))
                            <img src="{{ $cyberImage }}" class="chart-img">
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

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
