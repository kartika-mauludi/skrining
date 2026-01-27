<!-- tambah data -->

<div class="modal fade" id="addData" tabindex="-1" role="dialog" aria-labelledby="adddata" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"> Tambah Soal </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form action="{{ route('angketsoal.store') }}" method="POST" id="addDataForm" data-target-table="#table-user">
                @csrf
                <div class="modal-body">
                    <label for=""> Urut </label>
                    <input type="text" name="nama_angket" class="form-control" required>
                </div>
                 <div class="modal-body">
                    <label for=""> Soal </label>
                    <input type="text" name="soal" class="form-control" required>
                </div>
                <div class="modal-body">
                    <label for=""> Tipe Soal </label>
                    <input type="text" name="tipe_soal" class="form-control" required>
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
            <form id="EditDataForm" data-target-table="#table-user">
                @csrf
                  @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="id" id="editId">
                    <label for=""> Nama Angket </label>
                    <input type="text" name="nama_angket" id="editNamaAngket" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"> Close </button>
                    <button type="submit" class="btn btn-primary" > Save Changes </button>
                </div>
            </form>
        </div>
    </div>
</div>