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
        canvas {
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
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
            <h5 class="fw-bold mb-1">{{ $kelas->sekolah->nama_sekolah ?? '-' }}</h5>
            <div class="text-small">
              Alamat : {{ $kelas->sekolah->alamat_lengkap }}<br>
               No. Telephone : {{ $kelas->sekolah->no_tlp }} Website : {{ $kelas->sekolah->website }} Email : {{ $kelas->sekolah->email }}
            </div>
        </div>

        <div class="header-line"></div>

        <!-- JUDUL -->
        <div class="text-center mb-3">
            <h5 class="fw-bold">( Hasil Tes Anti Bullying )</h5>
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

        @if($status->contains('korban'))
        <div class="box bg-warning-subtle">
            <strong>Jika aku mengalami perundungan</strong>
            <ol class="mt-2">
              @foreach ($feedbacks as $feedback )
                @if($feedback->status =="korban")
                  <li>{!!  $feedback->feedback_deskripsi !!}</li>
                @endif
              @endforeach
            </ol>
        </div>
        @endif
        @if($status->contains('pelaku'))
         <div class="box bg-danger-subtle">
            <strong>Jika aku melakukan perundungan</strong>
            <ol class="mt-2">
                 @foreach ($feedbacks as $feedback )
                  @if($feedback->status =="pelaku")
                    <li>{!!  $feedback->feedback_deskripsi !!}</li>
                  @endif
                @endforeach
            </ol>
        </div>
        @endif

    </div>
</div>

@endsection