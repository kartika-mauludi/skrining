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
            <form action="{{ route('guru.store') }}" method="POST" id="addDataForm" data-target-table="#table-user">
                @csrf
                <div class="modal-body">
                    <label for=""> NIP </label>
                    <input type="text" name="nip" id="NIP" class="form-control" required oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="Masukkan NIP">
                    <label for=""> Username </label>
                    <input type="text" name="name" id="Username" class="form-control" required placeholder="Masukkan Username">
                    <label for=""> Nama Lengkap </label>
                    <input type="text" name="nama_lengkap" id="Name" class="form-control" required placeholder="Masukkan Nama Lengkap">
                    <label for="">Email</label>
                    <input type="email" name="email" id="Email" class="form-control" required placeholder="Masukkan Email">
                    <label for="">Alamat</label>
                    <input type="text" name="alamat" id="Alamat" class="form-control" placeholder="Masukkan Alamat">
                     <label for="">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" placeholder="Masukkan Tempat Lahir (opsional)">
                    <label for="">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" id="TglLahir" class="form-control" placeholder="Masukkan Tanggal Lahir (opsional)">
                     <label for="">No Wa</label>
                    <input type="text" name="no_tlp" id="NoTlp" class="form-control" oninput="this.value = this.value.replace(/\D/g, '+')" placeholder="Masukkan No WA"> 
                    <label for="">Password</label>
                     <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                         <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
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
                    <label for=""> NIP </label>
                    <input type="text" name="nip" id="editNIP" readonly class="form-control">
                    <label for=""> Username </label>
                    <input type="text" name="name" id="editUsername" readonly class="form-control">
                    <label for=""> Nama Lengkap </label>
                    <input type="text" name="nama_lengkap" id="editName" class="form-control">
                    <label for="">Email</label>
                    <input type="email" name="email" id="editEmail" class="form-control">
                    <label for="">Alamat</label>
                    <input type="text" name="alamat" id="editAlamat" class="form-control">
                     <label for="">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="editTempatLahir" class="form-control">
                    <label for="">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" id="editTglLahir" class="form-control">
                     <label for="">No Wa</label>
                    <input type="text" name="no_tlp" id="editNoTlp" class="form-control" oninput="this.value = this.value.replace(/\D/g, '+')">
                   
                   
                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"> Close </button>
                    <button type="submit" class="btn btn-primary" > Save Changes </button>
                </div>
            </form>
        </div>
    </div>
</div>