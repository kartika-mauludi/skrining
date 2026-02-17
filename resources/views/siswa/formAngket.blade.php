@extends('siswa.layout.index')

@section('content')


@push('style')
<style>

/* STICKY SISWA */

.active-siswa {
    background-color: #cce5ff;
    font-weight: bold;
}

.range-radio {
    position: relative;
    margin-left: 0;
}


/*  */

</style>
@endpush
<section class="content flex-grow-1">
  <div class="container-fluid">
    @if(empty($kelas))
     <div class="row justify-content-center vh-100">
          <div class="col-md-5 h-75 my-auto">
            <div class="card card-primary">
              <div class="card-body">
                <form action="">
                  <div class="form-group">
                    <label for="token">Masukkan Token untuk tes </label>
                    <input type="text" class="form-control border-dark" name="token" id="token" required value="{{ old('token') }}" placeholder="Masukkan Token">
                    <input type="hidden" name="angketId" id="angketId" value="{{ request('angketId') }}">
                    <button type="submit" class="btn btn-primary mt-3"> Masuk</button>
                  </div>
                </form>
                  @if(session('error'))
                    <div class="alert alert-danger mt-3">
                      {{ session('error') }}
                    </div>
                  @endif
              </div>
            </div>
          </div>
        </div>
    @endif
@if(!empty($kelas))
    <div class="col-md-12 mt-3">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">{{ $angket->nama_angket }}</h3>
        </div>
      </div>
     
    </div>
    <form action="{{ route('siswa.formAngket.store') }}" method="POST" id="formAngket">
      @if(session('error'))
        <script src="{{ asset('admin/assets-admin/dist/js/sweetalert.min.js') }}"></script>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}"
            });
        </script>
      @endif
    <div class="row p-3">
      <div class="col-lg-8 col-md-7 col-sm-12 order-1">
        <div class="row py-1 align-items-start px-3">
          <div class="col-md-12">
            <div class="card card-primary">
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <label for="absen">No Urut Absen</label>
                  <select name="no_absen" id="no_absen" class="form-control form-control-sm w-100 mr-1" required>
                    <option value="">Pilih No Absen</option> 
                    @foreach ($siswas as $siswa)
                      @if(!$siswaSudahIsi->contains($siswa->id))
                      <option value="{{ $siswa->no_absen }}"> {{ $siswa->no_absen }} - {{ $siswa->nama_lengkap }}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- siswa_id -->
        <input type="hidden" name="siswa_id" id="siswa_id" value="">
        <input type="hidden" name="angket" id="" value="{{ $angket->id }}">
        <input type="hidden" name="token" id="" value="{{ $token }}">

        <!--  -->
        <div class="row pb-1 align-items-start px-3">
          <div class="col-md-12">
              <div class="card card-primary">
                <div class="card-body">
                  <div class="form-group">
                    <label for="kelas">Kelas</label>
                    <input type="text" class="form-control" id="kelas" readonly required value="{{ old('kelas') }}">
                  </div>
                </div>
              </div>
          </div>
        </div>
        <div class="row pb-1 align-items-start px-3">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-body">
                <div class="form-group">
                  <label for="no_Induk">No Induk</label>
                  <input type="number" readonly class="form-control" name="nis" id="no_induk" required value="{{ old('nis') }}">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row pb-1 align-items-start px-3">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-body">
                <div class="form-group">
                  <label for="Nama">Nama</label>
                  <input type="text" class="form-control" id="nama" readonly value="{{ old('nama') }}"> 
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row pb-1 align-items-start px-3">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Jenis Kelamin</label>
                  <input type="text" class="form-control" id="jk" readonly>
                </div>
              </div>
            </div>
          </div>
        </div>
        @foreach ( $angketsoals as $soal )
        @if($soal->tipe_soal === 'range')
        <div class="row pb-1 align-items-start px-3">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-body">
                <div class="form-group">
                  <label for="range">{{ $loop->iteration }}. {{ $soal->soal }}</label>
                  <div class="d-flex justify-content-between px-2 ">
                    @for ($i = 0; $i <= 3; $i++)
                      <div class="text-center mx-5 mt-3">
                        <input
                          type="radio"
                          class=""
                          id="soal-{{ $soal->id }}-{{ $i }}"
                          name="jawaban[{{ $soal->id }}]"
                          value="{{ $i }}"required
                        >
                        <label
                          class="form-check-label d-block"
                          for="soal-{{ $soal->id }}-{{ $i }}">
                       @php $a = ['tidak pernah','jarang','sering','selalu']@endphp {{ $a[$i] }}
                        </label>
                      </div>
                    @endfor
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        @elseif($soal->tipe_soal === "text")
        <div class="row pb-1 align-items-start px-3">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-body">
                <label for="text">{{ $loop->iteration }}.{!! $soal->soal !!}</label>
                 <div id="jawaban-wrapper-{{ $soal->id }}">
                    <div class="input-group jawaban-item mt-1">
                      <input type="text" class="form-control" id="" data-id="" name="alasan[{{ $soal->id }}][]" placeholder="Alasan">
                        <select name="jawaban[{{ $soal->id }}][]" id="" class="mr-1 select-pelaku">
                          <option value="">Pilih No Absen</option> 
                          <option value="tidak_ada">Tidak ada</option>
                            @foreach ($siswas as $siswa)
                              <option value="{{ $siswa->no_absen }}"> {{ $siswa->no_absen }} - {{ $siswa->nama_lengkap }}</option>
                            @endforeach
                        </select>
                       <div class="input-group-append">
                          <button type="button" class="btn btn-danger btn-remove">
                            ✕
                          </button>
                      </div>
                    </div>
                  </div>
                   <button type="button" class="btn btn-sm btn-success mt-2 btn-tambah" data-soal="{{ $soal->id }}">
                      + Tambah Jawaban
                    </button>
              </div>
            </div>
          </div>
        </div>
        @elseif($soal->tipe_soal === "keterangan")
        <div class="row pb-1 align-items-start px-3">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-body">
                <label for="text">{!!  $soal->soal !!}</label>
              </div>
            </div>
          </div>
        </div>
        @endif
        @endforeach
        <button type="submit" class="btn btn-primary" > Simpan Jawaban </button>
      </div>
      </form>
      <div class="col-lg-4 col-md-5 col-sm-12 order-md-1 px-3">
        <div style="height: 250px" class="card card-primary overflow-auto">
          <div class="card-header">
            <h3 class="card-title">Daftar Siswa</h3>
          </div>
          <div class="card-body p-0">
            <table class="table table-sm table-striped mb-0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nama</th>
                </tr>
              </thead>
              <tbody>
                @foreach ( $siswas as $siswa )
                <tr class="active-siswa">
                  <td>{{ $siswa->no_absen }}</td>
                  <td>{{ $siswa->nama_lengkap }}</td>
                </tr>
                @endforeach 
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    @endif
  </div>
@endsection

@push('script')

<script>
  
  let siswaAktif = null;
  let sedangGantiSiswa = false;
  let jawabanSiswa = {};
  let siswaList = @json($siswas);
  let kelas = @json($kelas);
  let siswaMap = {};
    siswaList.forEach(s => {
      siswaMap[s.no_absen] = s;
  });

    $('#no_absen').on('change', function () {
      const no_absen = $(this).val();
      sedangGantiSiswa = true;
      // jika kosong → clear form
      if (siswaAktif) {
        simpanJawaban();
      }
      if (!no_absen) {
          siswaAktif = null;
          $('#formAngket')[0].reset();
          return;
      }
        siswaAktif = no_absen;
        restoreJawaban(no_absen);
      // isi form
      isiFormSiswa(no_absen);
       filterNoAbsenAktif(no_absen);
      setTimeout(() => sedangGantiSiswa = false, 100);
  });

  $('#formAngket').on('change input', 'input, textarea, select', function () {
    if (sedangGantiSiswa) return;
    simpanJawaban();
});

function filterNoAbsenAktif(noAbsenAktif) {
    $('.select-pelaku option').each(function () {
        const val = $(this).val();

        // reset dulu
        $(this).prop('disabled', false).show();

        // sembunyikan no_absen aktif
        if (val && val == noAbsenAktif) {
            $(this).prop('disabled', true).hide();
        }
    });
}


function isiFormSiswa(no_absen) {
    const siswa = siswaMap[no_absen];
    if (!siswa) return; 
    const jawaban = jawabanSiswa[no_absen] || {};
    $('#siswa_id').val(siswa.id);
    $('#nama').val(siswa.nama_lengkap);
    $('#no_induk').val(siswa.nis);
    $('#kelas').val(kelas.nama_kelas);
    $('#jk').val(siswa.jk);
}

function simpanJawaban() {
    if (!siswaAktif) return;

    jawabanSiswa[siswaAktif] = {
        kelas: $('#kelas').val(),
    };

    console.log('Autosave:', jawabanSiswa);
}

function restoreJawaban(no_absen) {
    const data = jawabanSiswa[no_absen];
    if (!data) return;

    Object.entries(data).forEach(([soalId, value]) => {
        const $input = $(`[name="jawaban[${soalId}]"]`);

        if ($input.is(':radio')) {
            $input.filter(`[value="${value}"]`).prop('checked', true);
        } else {
            $input.val(value);
        }
    });
}

// cloning 
$(document).on('click', '.btn-tambah', function () {
    const soalId = $(this).data('soal');
    const wrapper = $('#jawaban-wrapper-' + soalId);

    const clone = wrapper.find('.jawaban-item:first').clone();

    // reset value
    clone.find('input').val('');
    clone.find('select').val('');

    wrapper.append(clone);
});

// hapus baris
$(document).on('click', '.btn-remove', function () {
    const item = $(this).closest('.jawaban-item');
    const wrapper = item.parent();
    if (wrapper.children('.jawaban-item').length > 1) {
        item.remove();
    }
});

</script>
@endpush