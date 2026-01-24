<!-- Modal Import Website -->
<div class="modal fade" id="importWebsiteModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Import Website</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-10">
                        <input type="file" id="excelFileInputWebsite" class="form-control" accept=".xls, .xlsx">
                    </div>
                    <div class="col">
                        <a href="{{ asset('template/template_database.xlsx') }}" class="btn btn-sm btn-info">Template</a>
                    </div>
                </div>
                <div class="table-responsive mh-65vh">
                    <table id="importPreviewTableWebsite" class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="fit text-center" style="max-width: 1px !important;">No.</th>
                                <th>Judul</th>
                                <th>Link</th>
                                <th class="text-center" style="max-width: fit-content !important;">Validasi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btnUploadDataWebsite" class="btn btn-success" disabled>Upload Data</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    $(document).ready(() => {
        let tableImportWebsite = $("#importPreviewTableWebsite").DataTable({
            paging: false,
            searching: false,
            info: false,
            lengthChange: false,
            // pageLength: 10,
            ordering: false,
            deferRender: true,
            createdRow: function (row, data, dataIndex) {
                $(row).find('td').eq(0).addClass('fit text-center').css('max-width', '1px !important');
                $(row).find('td').eq(3).addClass('fit text-center').css('max-width', 'fit-content !important');
            }
        });



        $("#excelFileInputWebsite").change(function (e) {
            let file = e.target.files[0];
            if (!file) return;
            let reader = new FileReader();            
            
            reader.onload = function (event) {
                tableImportWebsite.clear().draw();
                showLoading();
                let data = new Uint8Array(event.target.result);
                let workbook = XLSX.read(data, { type: "array" });
                let sheetName = workbook.SheetNames[0];
                let sheet = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName], { header: 1 });

                let isValid = true;

                if (sheet.length > 1) {
                    sheet.slice(1).forEach((row, index) => {
                        let title = row[0] ? row[0].trim() : "";
                        let url = row[1] ? row[1].trim() : "";
                        let errorMessage = "";

                        if (!title) errorMessage += "Judul kosong. ";
                        if (!url) {
                            errorMessage += "URL kosong. ";
                        } else if (!isValidUrl(url)) {
                            errorMessage += "URL tidak valid. ";
                        }

                        if (errorMessage) isValid = false;

                        tableImportWebsite.row.add([
                            $('<td>', {
                                class: 'fit text-center',
                                style: 'max-width: 1px !important;',
                                text: `${index + 1}.`
                            })[0].outerHTML,
                            $('<td>', {
                                html: title
                            })[0].outerHTML,
                            $('<td>', {
                                html: url ? `<a href="${url}" target="_blank">${url}</a>` : ""
                            })[0].outerHTML,
                            $('<td>', {
                                class: 'fit text-center',
                                style: 'max-width: 1px !important;',
                                html: errorMessage ? `<span class="text-danger">${errorMessage}</span>` : `<span class="text-success">OK</span>`
                            })[0].outerHTML
                        ]).draw(false);


                    });

                    $("#btnUploadDataWebsite").prop("disabled", !isValid);
                } else {
                    $("#btnUploadDataWebsite").prop("disabled", true);
                }

                closeLoading();
            };
            reader.readAsArrayBuffer(file);
        });

        $("#btnUploadDataWebsite").click(function () {
            let tableData = [];
            let isValid = true;

            $("#importPreviewTableWebsite tbody tr").each(function () {
                let row = $(this).find("td");
                let title = row.eq(1).text().trim();
                let url = row.eq(2).find("a").attr("href");

                if (!title || !url || !isValidUrl(url)) {
                    isValid = false;
                    return false;
                }

                tableData.push({ title, url });
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

            fetch(`/universities/{{ $university->id }}/websites/import`, {
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
                            $("#excelFileInputWebsite").val(null);
                            Swal.fire("Selesai!", `Berhasil mengimport ${insertedCount} dari ${tableData.length} data.`, "success");
                            $("#importWebsiteModal").modal("hide");
                            tableImportWebsite.clear().draw();
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
