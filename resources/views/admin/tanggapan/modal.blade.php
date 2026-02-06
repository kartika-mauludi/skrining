<!-- tambah data -->

<div class="modal fade" id="addData" tabindex="-1" role="dialog" aria-labelledby="adddata" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"> Tambah Data </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form action="{{ route('tanggapan.store') }}" method="POST" id="addDataForm" data-target-table="#tbl-tanggapan">
                @csrf
                <div class="modal-body">
                    <label for=""> Tanggpan </label>
                    <textarea name="feedback_deskripsi" id="addTanggapan"class="form-control summernote" required></textarea>
                     <label for="">Status</label>
                    <select name="status" id="eddStatus"  class="form-control form-control-sm" style="width: 100%" required>
                        <option value="">Pilih Status</option>
                        <option value="korban">Korban</option>
                        <option value="pelaku">Pelaku</option>
                        <option value="netral">Netral</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"> Close </button>
                    <button type="submit" class="btn btn-primary" > Save Changes </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- edit data -->
<div class="modal fade" id="editData" tabindex="-1" role="dialog" aria-labelledby="editData" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"> Ubah Data </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form id="EditDataForm" data-target-table="#tbl-tanggapan">
                @csrf
                  @method('PUT')
                <div class="modal-body">
                   <label for=""> Tanggpan </label>
                    <textarea type="text" name="feedback_deskripsi" id="editNamaTanggapan"class="form-control summernote" required></textarea>
                     <label for="">Status</label>
                    <select name="status" id="editStatus"  class="form-control form-control-sm" style="width: 100%" required>
                        <option value="korban">Korban</option>
                        <option value="pelaku">Pelaku</option>
                        <option value="netral">Netral</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"> Close </button>
                    <button type="submit" class="btn btn-primary" > Save Changes </button>
                </div>
            </form>
        </div>
    </div>
</div>