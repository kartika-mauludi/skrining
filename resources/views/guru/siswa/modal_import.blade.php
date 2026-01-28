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
                        <a href="" class="btn btn-sm btn-info">Template</a>
                    </div>
                </div>
                <div class="table-responsive mh-65vh">
                    <table id="importPreview" class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="fit text-center" style="max-width: 1px !important;">No.</th>
                                <th>No. Absen</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
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

@push('script')
<script>
    $(document).ready(() => {
        var token = $('meta[name="csrf-token"]');

        let tableImportGuru = $("#importPreview").DataTable({
            paging: false,
            searching: false,
            info: false,
            lengthChange: false,
            // pageLength: 10,
            ordering: false,
            deferRender: true,
            createdRow: function (row, data, dataIndex) {
                $(row).find('td').eq(0).addClass('fit text-center').css('max-width', '1px !important');
                $(row).find('td').eq(1).addClass('fit text-center').css('max-width', 'fit-content !important');
            }
        });

        $("#fileImport").change(function (e) {
            let file = e.target.files[0];
            if (!file) return;
            let reader = new FileReader();            
            
            reader.onload = function (event) {
                tableImportGuru.clear().draw();
                showLoading();
                let data = new Uint8Array(event.target.result);
                let workbook = XLSX.read(data, { type: "array" });
                let sheetName = workbook.SheetNames[0];
                let sheet = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName], { header: 1 });

                let isValid = true;

                if (sheet.length > 1) {
                    sheet.slice(1).forEach((row, index) => {
                        let nis             = row[0] != null ? String(row[0]).trim() : "";
                        let nama_siswa      = row[1] != null ? String(row[1]).trim() : "";
                        let tempat_lahir    = row[2] != null ? String(row[2]).trim() : "";
                        let tgl_lahir       = parseExcelDate(row[3]);
                        let alamat          = row[4] != null ? String(row[4]).trim() : "";
                        let nama_wali       = row[5] != null ? String(row[5]).trim() : "";
                        let no_tlp_wali     = row[6] != null ? String(row[6]).trim() : "";

                         if ( !nis && !nama_siswa && !tempat_lahir && !tgl_lahir && !alamat && !nama_wali && !no_tlp_wali) {
                              return;
                            }
                        let errorMessage = "";

                        if (!nis) errorMessage += "NIP kosong ";
                        if (!nama_siswa) errorMessage += "Nama Siswa kosong ";
                        if (!tempat_lahir) errorMessage += "Tempat Lahir kosong ";
                        if (!tgl_lahir) errorMessage += "Tanggal Lahir kosong ";
                        if (!alamat) errorMessage += "Alamat kosong ";
                        if (!nama_wali) errorMessage += "Nama Wali kosong ";
                        if (!no_tlp_wali) errorMessage += "No. Telpon Wali kosong ";

                        if (errorMessage) isValid = false;
                        tableImportGuru.row.add([
                            `${index + 1}.`,
                            nis,
                            nama_siswa,
                            tempat_lahir,
                            tgl_lahir,
                            alamat,
                            nama_wali,
                            no_tlp_wali,
                            errorMessage
                                ? `<span class="text-danger">${errorMessage}</span>`
                                : `<span class="text-success">OK</span>`
                        ]).draw(false);

                    });

                    $("#btnUpload").prop("disabled", !isValid);
                } else {
                    $("#btnUpload").prop("disabled", true);
                }

                closeLoading();
            };
            reader.readAsArrayBuffer(file);
        });

        $("#btnUpload").click(function () {
            let tableData = [];
            let isValid = true;

            $("#importPreview tbody tr").each(function () {
                let row         = $(this).find("td");
                let nis         = row.eq(1).text().trim();
                let nama_siswa  = row.eq(2).text().trim();
                let tempat_lahir= row.eq(3).text().trim();
                let tgl_lahir   = row.eq(4).text().trim();
                let alamat      = row.eq(5).text().trim();
                let nama_wali   = row.eq(6).text().trim();
                let no_tlp_wali = row.eq(7).text().trim();


                if (!nis || !nama_siswa || !tempat_lahir || !tgl_lahir || !alamat || !nama_wali || !no_tlp_wali) {
                    isValid = false;
                    return false;
                }

                tableData.push({ nis, nama_siswa, tempat_lahir, tgl_lahir, alamat, nama_wali, no_tlp_wali });
            });

            if (!isValid) {
                Swal.fire("Gagal!", "Pastikan semua data sudah benar sebelum mengunggah.", "error");
                return;
            }

            Swal.fire({
                title: "Mengimport Data...",
                html: `Proses import sedang berjalan...<br><b>0/${tableData.length}</b> data diunggah.`,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            let insertedCount = 0;

            fetch(`{{ route(('guru.siswa.import')) }}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token
                },
                body: JSON.stringify({ data: tableData })
            }).then(response => {
                const reader = response.body.getReader();
                const decoder = new TextDecoder();
                let dataTable = $('#datatable').DataTable()
                function read() {
                    reader.read().then(({ done, value }) => {
                        if (done) {
                            $("#fileImport").val(null);
                            Swal.fire("Selesai!", `Berhasil mengimport ${insertedCount} dari ${tableData.length} data.`, "success");
                            $("#importModal").modal("hide");
                            tableImportGuru.clear().draw();
                            dataTable.clear().draw();
                            dataTable.ajax.url("{{ route('guru.siswa.data') }}").load();
                            return;
                        }

                        let text = decoder.decode(value);
                        let lines = text.trim().split("\n");

                        lines.forEach(line => {
                            try {
                                let data = JSON.parse(line);
                                insertedCount = data.procesed;

                                Swal.update({
                                    html: `Proses import sedang berjalan...<br><b>${data.progress}</b> data diunggah.`
                                });
                            } catch (e) {
                                console.error("Error parsing JSON:", line);
                            }
                        });

                        read();
                    });
                }

                read();
            }).catch(error => {
                Swal.fire("Gagal!", "Terjadi kesalahan saat mengimport data.", "error");
            });
        });

        $("#btnBatal").on("click", function () {
            resetImportGuru();
            $("#importModal").modal("hide");
        });

        $("#importModal").on("hidden.bs.modal", function () {
            resetImportGuru();
        });

        function resetImportGuru() {
            // clear datatable
            tableImportGuru.clear().draw();

            // reset input file
            $("#fileImport").val(null);

            // disable tombol upload
            $("#btnUpload").prop("disabled", true);

            // reset flag validasi
            isValid = true;
        }

       function parseExcelDate(value) {
            if (!value) return "";

            // Excel serial number
            if (typeof value === "number") {
                const excelEpoch = new Date(1899, 11, 30);
                const date = new Date(excelEpoch.getTime() + value * 86400000);
                return date.toISOString().split("T")[0]; // yyyy-mm-dd
            }

            // String
            if (typeof value === "string") {
                value = value.trim();

                // sudah yyyy-mm-dd
                if (/^\d{4}-\d{2}-\d{2}$/.test(value)) {
                    return value;
                }

                // dd/mm/yyyy atau d/m/yyyy
                if (/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(value)) {
                    const [d, m, y] = value.split("/");
                    return `${y}-${m.padStart(2, "0")}-${d.padStart(2, "0")}`;
                }

                // dd-mm-yyyy
                if (/^\d{1,2}-\d{1,2}-\d{4}$/.test(value)) {
                    const [d, m, y] = value.split("-");
                    return `${y}-${m.padStart(2, "0")}-${d.padStart(2, "0")}`;
                }
            }

            return "";
        }
    });
</script>
@endpush
