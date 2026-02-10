<!-- tambah data -->

<div class="modal fade addDataModal" id="addData" tabindex="-1" role="dialog" aria-labelledby="adddata" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"> Tambah Data </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form action="{{ route('guru.soal.store') }}" method="POST" id="addDataForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="method" value="POST">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_angket" class="form-label">Nama Angket</label>
                        <input type="text" name="" id="nama_angket" readonly 
                        class="form-control form-control-sm" value="{{ $angket->nama_angket }}">
                        <input type="hidden" name="angket_id" value="{{ $angket->id }}">
                    </div>
                    <hr>
                    <p class="fw-semibold">Daftar Pertanyaan</p>

                    <div id="soal-container" class="mt-3"></div>
                    
                    <button type="button" class="btn btn-sm btn-primary addSoal">
                        + Tambah Pertanyaan
                    </button>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


