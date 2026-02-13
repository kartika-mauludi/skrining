<!-- Modal Import Website -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Import</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-10">
                        <input type="file" id="excelFileInput" class="form-control" accept=".xls, .xlsx">
                    </div>
                    <div class="col">
                        <a href="{{ asset('admin/file/template_feedback.xlsx') }}" target="_blank" class="btn btn-sm btn-info">Template</a>
                    </div>
                </div>
                <div class="table-responsive mh-65vh">
                    <table id="importPreviewTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="fit text-center" style="max-width: 1px !important;">No.</th>
                                <th>Soal</th>
                                <th>Tipe Soal</th>
                                <th>Ruang Lingkup</th>
                                <th>Indikator/tipe_soal</th>
                                <th>Indikasi Bully</th>
                                <th class="text-center" style="max-width: fit-content !important;">Validasi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btnUploadData" class="btn btn-success" disabled>Upload Data</button>
                <button type="button" class="btn btn-secondary" id="btnBatal" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    let tableImport = null;
    let isValid = true;
    $(document).ready(() => {
        let tableImport = $("#importPreviewTable").DataTable({
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

        $("#excelFileInput").change(function (e) {
            let file = e.target.files[0];
            if (!file) return;
            let reader = new FileReader();            
            
            reader.onload = function (event) {
                if ($.fn.DataTable.isDataTable('#importPreviewTable')) {
                tableImport.clear().draw();
                }
                showLoading();
                let data = new Uint8Array(event.target.result);
                let workbook = XLSX.read(data, { type: "array" });
                let sheetName = workbook.SheetNames[0];
                let sheet = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName], { header: 1 });

                let isValid = true;

                if (sheet.length > 1) {
                    sheet.slice(1).forEach((row, index) => {
                        let soal            = row[0] != null ? String(row[0]).trim() : "";
                        let tipe_soal       = row[1] != null ? String(row[1]).trim() : "";
                        let ruang_lingkup   = row[2] != null ? String(row[2]).trim() : "";
                        let indikator       = row[3] != null ? String(row[3]).trim() : "";
                        let indikasi        = row[4] != null ? String(row[4]).trim() : "";

                        
                         if ( !soal && !tipe_soal && !ruang_lingkup && !indikator && !indikasi) {
                              return;
                            }
                        let errorMessage = "";

                        if (!soal) errorMessage += "soal kosong ";
                        if (!tipe_soal) errorMessage += "tipe soal kosong ";
                        if (!ruang_lingkup) errorMessage += "Ruang Lingkup kosong ";
                        if (!indikator) errorMessage += "indikator atau status kosong ";
                        if (!indikasi) errorMessage += "indikasi tau parameter kosong ";


                        if (errorMessage) isValid = false;
                        tableImport.row.add([
                            `${index + 1}.`,
                            soal,
                            tipe_soal,
                            ruang_lingkup,
                            indikator,
                            indikasi,
                            errorMessage
                                ? `<span class="text-danger">${errorMessage}</span>`
                                : `<span class="text-success">OK</span>`
                        ]).draw(false);

                    });

                    $("#btnUploadData").prop("disabled", !isValid);
                } else {
                    $("#btnUploadData").prop("disabled", true);
                }

                closeLoading();
            };
            reader.readAsArrayBuffer(file);
        });

        $("#btnUploadData").click(function () {
            let tableData = [];
            let isValid = true;

            $("#importPreviewTable tbody tr").each(function () {
                let row                 = $(this).find("td");
                let soal                = row.eq(1).text().trim();
                let tipe_soal           = row.eq(2).text().trim();
                let ruang_lingkup       = row.eq(3).text().trim();
                let indikator           = row.eq(4).text().trim();
                let indikasi            = row.eq(5).text().trim();
                let angketId            = {{ $angket->id ?? 'null' }};


                if (!soal || !tipe_soal || !ruang_lingkup || !indikator || !indikasi ) {
                    isValid = false;
                    return false;
                }

                tableData.push({ soal, tipe_soal, ruang_lingkup, indikator, indikasi, angketId });
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
            fetch(`{{ route(('admin.angketSoal.import')) }}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": '{{ csrf_token() }}'
                },
                body: JSON.stringify({ data: tableData })
            }).then( response => {
                const reader = response.body.getReader();
                const decoder = new TextDecoder();
                let table = $('#tbl-angketSoal').DataTable()
                function read() {
                    reader.read().then(({ done, value }) => {
                        if (done) {
                            $("#excelFileInput").val(null);
                            Swal.fire("Selesai!", `Berhasil mengimport ${insertedCount} dari ${tableData.length} data.`, "success");
                            $("#importModal").modal("hide");
                            if ($.fn.DataTable.isDataTable('#importPreviewTable')) {
                            tableImport.clear().draw();
                            }
                            table.clear().draw();
                            table.ajax.url("{{ route('admin.angketSoal.data') }}").load();
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
            resetImport();
            $("#importModal").modal("hide");
        });

        $("#importModal").on("hidden.bs.modal", function () {
            resetImport();
        });

        function resetImport() {
            // clear datatable
            if ($.fn.DataTable.isDataTable('#importPreviewTable')) {
            tableImport.clear().draw();
            }           

            // reset input file
            $("#excelFileInput").val(null);

            // disable tombol upload
            $("#btnUploadData").prop("disabled", true);

            // reset flag validasi
            isValid = true;
        }

      

            // String
          

    });
</script>
@endpush
