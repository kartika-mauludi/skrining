<!-- Modal Import Website -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Import Siswa</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-10">
                        <input type="file" id="fileImport" class="form-control" accept=".xls, .xlsx">
                    </div>
                    <div class="col">
                        <a href="{{ asset('admin/file/template_siswa.xlsx') }}" class="btn btn-sm btn-info">Template</a>
                    </div>
                </div>
                <div class="table-responsive mh-65vh">
                    <div class="form-group">
                        <label for="kelas_id" class="form-label">Kelas</label>
                        <select name="kelas_id" id="kelas_id_import" class="form-control form-control-sm" style="width: 100% !important" required>
                            <option value=""></option>
                            @foreach ($kelas as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <table id="importPreview" class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="fit text- center" style="max-width: 1px !important;">No.</th>
                                <th>NIS</th>
                                <th>No. Absen</th>
                                <th>Nama Siswa</th>
                                <th>Jenis Kelamin</th>
                                <th>Tempat Lahir</th>
                                <th>Tanggal Lahir</th>
                                <th>Alamat</th>
                                <th>Nama Wali</th>
                                <th>No. Telpon Wali</th>
                                <th class="text-center" style="max-width: fit-content !important;">Validasi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btnUpload" class="btn btn-success" disabled>Upload Data</button>
                <button type="button" class="btn btn-secondary" id="btnBatal" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
