<!-- tambah data -->

<div class="modal fade" id="dataModal" tabindex="-1" role="dialog" aria-labelledby="adddata" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"> Tambah Data </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form action="" method="POST" id="dataForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="method" value="POST">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_angket" class="form-label">Nama Angket</label>
                        <input type="text" name="nama_angket" id="nama_angket" class="form-control form-control-sm" required>
                    </div>
                    <div class="form-group">
                        <label for="sekolah_id" class="form-label">Sekolah</label>
                        <select name="sekolah_id" id="sekolah_id" class="form-control form-control-sm" style="width: 100% !important" required>
                            <option value=""></option>
                            @foreach ($sekolahs as $sekolah)
                                <option value="{{ $sekolah->id }}">{{ $sekolah->nama_sekolah }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kelas_id" class="form-label">Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="form-control form-control-sm" style="width: 100% !important" required></select>
                    </div>
                    <hr>
                    <p class="fw-semibold">Daftar Pertanyaan</p>

                    <div id="soal-container" class="mt-3"></div>
                    
                    <button type="button" class="btn btn-sm btn-primary addNew">
                        + Tambah Pertanyaan
                    </button>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>