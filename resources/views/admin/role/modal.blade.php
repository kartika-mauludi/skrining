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
            <form action="{{ route('role.store') }}" method="POST" id="addrole" data-target-table="#table-user">
                @csrf
                <div class="modal-body">
                    <label for=""> Role </label>
                    <input type="text" name="role" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"> Close </button>
                    <button type="submit" class="btn btn-primary" > Save Changes </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- update data -->

<div class="modal fade" id="editData" tabindex="-1" role="dialog" aria-labelledby="editData" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"> Edit Data </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form id="formeditrole" data-target-table="#table-user">
                @csrf
                 @method('PUT')
                <input type="hidden" id="editID">
                <div class="modal-body">
                    <label for=""> Role </label>
                    <input type="text" name="role" id="role" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"> Close </button>
                    <button type="submit" class="btn btn-primary" > Save Changes </button>
                </div>
            </form>
        </div>
    </div>
</div>