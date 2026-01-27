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
                        <label for="nama_sekolah" class="form-label">Nama Sekolah</label>
                        <input type="text" name="nama_sekolah" id="nama_sekolah" class="form-control form-control-sm" required>
                    </div>
                    <div class="form-group">
                        <label for="no_tlp" class="form-label">No. Telpon</label>
                        <input type="text" name="no_tlp" id="no_tlp" class="form-control form-control-sm" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat_lengkap" class="form-label">Alamat</label>
                        <textarea name="alamat_lengkap" id="alamat_lengkap" class="form-control form-control-sm"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="logo" class="form-label">Logo</label>
                        <input type="file" name="logo" id="logo" class="form-control form-control-sm" accept="image/*">
                        <img id="preview" src="#" alt="Image Preview" style="display:none; width: 150px;">
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