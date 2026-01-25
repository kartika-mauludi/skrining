<!-- Modal Import Website -->
<div class="modal fade" id="importGuruModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Import Guru</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-10">
                        <input type="file" id="excelFileInputGuru" class="form-control" accept=".xls, .xlsx">
                    </div>
                    <div class="col">
                        <a href="" class="btn btn-sm btn-info">Template</a>
                    </div>
                </div>
                <div class="table-responsive mh-65vh">
                    <table id="importPreviewTableGuru" class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="fit text-center" style="max-width: 1px !important;">No.</th>
                                <th>NIP</th>
                                <th>Usernmae</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Alamat</th>
                                <th>Tempat Lahir</th>
                                <th>Tanggal Lahir</th>
                                <th>No WA</th>
                                <th class="text-center" style="max-width: fit-content !important;">Validasi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btnUploadDataGuru" class="btn btn-success" disabled>Upload Data</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    $(document).ready(() => {
        let tableImportGuru = $("#importPreviewTableGuru").DataTable({
            paging: false,
            searching: false,
            info: false,
            lengthChange: false,
            // pageLength: 10,
            ordering: false,
            deferRender: true,
            createdRow: function (row, data, dataIndex) {
                $(row).find('td').eq(0).addClass('fit text-center').css('max-width', '1px !important');
                $(row).find('td').eq(9).addClass('fit text-center').css('max-width', 'fit-content !important');
            }
        });



        $("#excelFileInputGuru").change(function (e) {
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
                        let nip             = row[0] != null ? String(row[0]).trim() : "";
                        let username        = row[1] != null ? String(row[1]).trim() : "";
                        let nama_lengkap    = row[2] != null ? String(row[2]).trim() : "";
                        let email           = row[3] != null ? row[3].trim() : "";
                        let alamat          = row[4] != null ? String(row[4]).trim() : "";
                        let tempat_lahir    = row[5] != null ? String(row[5]).trim() : "";
                        let tgl_lahir       = row[6] != null ? String(row[6]).trim() : "";
                        let no_wa           = row[7] != null ? String(row[7]).trim() : "";

                        let errorMessage = "";

                        if (!nip) errorMessage += "NIP kosong ";
                        if (!username) errorMessage += "Username kosong ";
                        if (!nama_lengkap) errorMessage += "Nama Lengkap kosong ";
                        if (!email) {
                            errorMessage += "Email kosong. ";
                        } else if (!isValidEmail(email)) {
                            errorMessage += "Email tidak valid. ";
                        }
                        if (!alamat) errorMessage += "Alamat kosong ";
                        if (!tempat_lahir) errorMessage += "Tempat Lahir kosong ";
                        if (!tgl_lahir) errorMessage += "Tanggal Lahir kosong ";
                        if (!no_wa) errorMessage += "No WA kosong ";

                        if (errorMessage) isValid = false;
                        tableImportGuru.row.add([
                            $('<td>', {
                                class: 'fit text-center',
                                style: 'max-width: 1px !important;',
                                text: `${index + 1}.`
                            })[0].outerHTML,
                            $('<td>', { html: nip })[0].outerHTML,
                            $('<td>', { html: username})[0].outerHTML,
                            $('<td>', { html: email})[0].outerHTML,
                            $('<td>', { html: alamat})[0].outerHTML,
                            $('<td>', { html: tempat_lahir})[0].outerHTML,
                            $('<td>', { html: tgl_lahir})[0].outerHTML,
                            $('<td>', { html: no_wa})[0].outerHTML,
                            $('<td>', {
                                class: 'fit text-center',
                                style: 'max-width: 1px !important;',
                                html: errorMessage ? `<span class="text-danger">${errorMessage}</span>` : `<span class="text-success">OK</span>`
                            })[0].outerHTML
                        ]).draw(false);

                    });

                    $("#btnUploadDataGuru").prop("disabled", !isValid);
                } else {
                    $("#btnUploadDataGuru").prop("disabled", true);
                }

                closeLoading();
            };
            reader.readAsArrayBuffer(file);
        });

        $("#btnUploadDataGuru").click(function () {
            let tableData = [];
            let isValid = true;

            $("#importPreviewTableGuru tbody tr").each(function () {
                let row         = $(this).find("td");
                let nip         = row.eq(1).text().trim();
                let username    = row.eq(2).text().trim();
                let email       = row.eq(3).text().trim();
                let alamat      = row.eq(4).text().trim();
                let tempat_lahir= row.eq(5).text().trim();
                let tgl_lahir   = row.eq(6).text().trim();
                let no_wa       = row.eq(7).text().trim();


                if (!nip || !username || !email || !isValidEmail(email) || !alamat || !tempat_lahir || !tgl_lahir || !no_wa) {
                    isValid = false;
                    return false;
                }

                tableData.push({ nip, username });
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

            fetch(`{{ route(('admin.guru.import')) }}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": '{{ csrf_token() }}'
                },
                body: JSON.stringify({ data: tableData })
            }).then(response => {
                const reader = response.body.getReader();
                const decoder = new TextDecoder();

                function read() {
                    reader.read().then(({ done, value }) => {
                        if (done) {
                            $("#excelFileInputGuru").val(null);
                            Swal.fire("Selesai!", `Berhasil mengimport ${insertedCount} dari ${tableData.length} data.`, "success");
                            $("#importGuruModal").modal("hide");
                            tableImportGuru.clear().draw();
                            websiteTable.ajax.reload();
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

        function isValidEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        function isValidUrl(str) {
            try {
                let url = new URL(str);

                if (!/^https?:$/.test(url.protocol)) {
                    return false;
                }

                if (!url.hostname.includes(".")) {
                    return false;
                }

                let decodedQuery = decodeURIComponent(url.search);
                if (/<|>/g.test(decodedQuery)) {
                    return false;
                }

                return true;
            } catch (error) {
                return false;
            }
        }
    });
</script>
@endpush
