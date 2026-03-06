<!-- tambah data -->

<div class="modal fade" id="dataModal" tabindex="-1" role="dialog" aria-labelledby="adddata" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
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
                        <label for="kelas_id" class="form-label">Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="form-control form-control-sm" style="width: 100%" required>
                            <option value=""></option>
                            @foreach ($kelas as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="no_absen" class="form-label">No. Absen</label>
                        <input type="number" name="no_absen" id="no_absen" class="form-control form-control-sm" required>
                    </div>
                    <div class="form-group">
                        <label for="nis" class="form-label">NIS</label>
                        <input type="text" name="nis" id="nis" class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                        <label for="nama_lengkap" class="form-label">Nama Siswa</label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control form-control-sm" required>
                    </div>
                    <div class="form-group">
                        <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control form-control-sm" >
                    </div>
                    <div class="form-group">
                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control form-control-sm" >
                    </div>
                    <div class="form-group">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control form-control-sm"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="nama_wali" class="form-label">Nama Wali</label>
                        <input type="text" name="nama_wali" id="nama_wali" class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                        <label for="no_tlp_wali" class="form-label">No. Telpon Wali</label>
                        <input type="text" name="no_tlp_wali" id="no_tlp_wali" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>