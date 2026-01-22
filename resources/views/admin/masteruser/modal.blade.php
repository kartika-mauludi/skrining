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
            <form action="{{ route('masteruser.store') }}" method="POST" id="addDataForm" data-target-table="#table-user">
                @csrf
                <div class="modal-body">
                    <label for=""> Nama </label>
                    <input type="text" name="name" class="form-control" required>
                    <label for="">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                     <label for="">Password</label>
                     <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <label for="">Role</label>
                    <select name="role" id=""  class="form-control form-control-sm select2" style="width: 100%" multiple required>
                        <option value="super_admin">Super Admin</option>
                        <option value="guru">Guru</option>

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
            <form id="EditDataForm" data-target-table="#table-user">
                @csrf
                  @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="id" id="editId">
                    <label for=""> Nama </label>
                    <input type="text" name="name" id="editName" class="form-control">
                    <label for="">Email</label>
                    <input type="email" name="email" id="editEmail" class="form-control">
                     <label for="">Password</label>
                     <div class="input-group mb-0">
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                   <small class="form-text mb-2 text-muted">
                        Isi password jika ingin ubah password
                    </small>

                    <label for="">Role</label>
                   <select name="role" id=""  class="form-control form-control-sm select2" style="width: 100%" multiple required>
                        <option value="super_admin">Super Admin</option>
                        <option value="guru">Guru</option>

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