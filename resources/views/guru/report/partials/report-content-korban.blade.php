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