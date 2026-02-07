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
            <form action="" method="POST" id="dataForm">
                @csrf
                <input type="hidden" name="_method" id="method" value="POST">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="feedback_deskripsi" class="form-label">Tanggapan</label>
                        <textarea name="feedback_deskripsi" id="addTanggapan"class="form-control summernote" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control form-control-sm" required>
                            <option value=""></option>
                            <option value="korban">Korban</option>
                            <option value="pelaku">Pelaku</option>
                            <option value="netral">Netral</option>
                        </select>
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