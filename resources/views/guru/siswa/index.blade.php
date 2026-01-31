@extends('guru.layout.index')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Siswa</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('guru.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Siswa</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        @if (session('message'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                {{ session('message') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="card-header">
                            <h3 class="card-title"></h3>
                            <button class="btn btn-sm btn-success" id="add"> Tambah </button>
                            <button class="btn btn-sm btn-info" data-toggle="modal"data-target="#importModal">Import</button>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No Absen</th>
                                            <th>Kelas</th>
                                            <th>NIS</th>
                                            <th>Nama Siswa</th>
                                            <th>Tempat Lahir</th>
                                            <th>Tanggal Lahir</th>
                                            <th>Alamat</th>
                                            <th>Nama Wali</th>
                                            <th>No. Telpon Wali</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@include('guru.siswa.modal')
@include('guru.siswa.modal_import')
@endsection

@push('script')
<script>
$(document).ready(function(){
    var token = $('meta[name="csrf-token"]');

    $('#kelas_id').select2({
        placeholder: '-- Pilih Data --',
        dropdownParent: $('#dataModal')
    })

    $('#kelas_id_import').select2({
        placeholder: '-- Pilih Data --',
        dropdownParent: $('#importModal')
    })

    var table = $('#datatable').DataTable({
        processing  : true,
        serverSide  : false,
        ajax: {
            url: "{{ route('guru.siswa.data') }}",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': token.attr('content')
            }
        },
        columns: [{
            data    : 'no_absen',
            render  : (data) => data ? `${data}` : `-`
        },{
            data    : 'kelas.nama_kelas',
            render  : (data) => data ? `${data}` : `-` 
        },{
            data    : 'nis',
            render  : (data) => data ? `${data}` : `-` 
        },{
            data    : 'nama_lengkap',
            render  : (data) => data ? `${data}` : `-` 
        },{
            data    : 'tgl_lahir',
            render  : (data) => data ? `${data}` : `-` 
        },{
            data    : 'tempat_lahir',
            render  : (data) => data ? `${data}` : `-` 
        },{
            data    : 'alamat',
            render  : (data) => data ? `${data}` : `-` 
        },{
            data    : 'nama_wali',
            render  : (data) => data ? `${data}` : `-` 
        },{
            data    : 'no_tlp_wali',
            render  : (data) => data ? `${data}` : `-` 
        },{
        data: 'id',
            render: function(data, type, row){
                let editUrl = "{{ route('guru.siswa.edit', ':id') }}";
                let deleteUrl = "{{ route('guru.siswa.destroy', ':id') }}";

                editUrl = editUrl.replace(':id', data);
                deleteUrl = deleteUrl.replace(':id', data);

                return `
                    <div class="btn-group d-flex gap-2">
                        <button class="btn btn-sm btn-warning edit-btn" data-url="${editUrl}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-url="${deleteUrl}">Hapus</button>
                    </div>
                `;
            }
        }]
    });

    $('#add').on('click', function () {
        let storeUrl = "{{ route('guru.siswa.store', ':id') }}";

        $('#dataForm').prop('action', storeUrl).trigger('reset');
        $('#method').val('POST')
        $('#dataModal').modal('show');
    });

    $('#datatable').on('click', '.edit-btn', function (){
        const url = $(this).data('url');

        $.get(url, function(data, status) {
            if (status != 'success') {
                swal.fire('Kesalahan sistem','Silahkan hubungi administrator','error')
                return
            }

            let updateUrl = "{{ route('guru.siswa.update', ':id') }}";
            updateUrl = updateUrl.replace(':id', data['data']['id']);
        
            $('#method').val('PUT')
            $('#kelas_id').val(data['data']['kelas_id']).change();
            $('#nama_lengkap').val(data['data']['nama_wali']);
            $('#no_absen').val(data['data']['no_absen']);
            $('#nis').val(data['data']['nis']);
            $('#tgl_lahir').val(data['data']['tgl_lahir']);
            $('#tempat_lahir').val(data['data']['tempat_lahir']);
            $('#alamat').val(data['data']['alamat']);
            $('#nama_wali').val(data['data']['nama_wali']);
            $('#no_tlp_wali').val(data['data']['no_tlp_wali']);

            $('#dataForm').prop('action', updateUrl);
            $('#dataModal').modal('show');
        }).fail(function(jqXHR, textStatus, errorThrown) {
            swal.fire('Kesalahan sistem',textStatus,'error')
        });
    });

    $('#datatable').on('click', '.delete-btn', function (){
        const url = $(this).data('url');

        swal.fire({
            title: 'Perhatian',
            text: 'Apakah anda yakin ?',
            icon: 'warning',
            showDenyButton: true,
            confirmButtonText: 'Hapus',
            denyButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-danger',
                denyButton: 'btn btn-secondary',
            },
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token.attr('content')
                    },
                    success: function(response) {
                        swal.fire({
                            title:'Berhasil',
                            text:'Data berhasil dihapus',
                            icon:'success',
                            allowOutsideClick: false
                        })
                        .then((result) => {
                            location.reload();
                        })
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        swal.fire('Kesalahan sistem',textStatus,'error')
                    }
                })
            }
        });
    });

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
                    let no_absen        = row[1] != null ? String(row[1]).trim() : "";
                    let nama_lengkap      = row[2] != null ? String(row[2]).trim() : "";
                    let tempat_lahir    = row[3] != null ? String(row[3]).trim() : "";
                    let tgl_lahir       = parseExcelDate(row[4]);
                    let alamat          = row[5] != null ? String(row[5]).trim() : "";
                    let nama_wali       = row[6] != null ? String(row[6]).trim() : "";
                    let no_tlp_wali     = row[7] != null ? String(row[7]).trim() : "";

                        if ( !nis && !nama_lengkap && !tempat_lahir && !tgl_lahir && !alamat && !nama_wali && !no_tlp_wali) {
                            return;
                        }
                    let errorMessage = "";

                    if (!nis) errorMessage += "NIP kosong ";
                    if (!no_absen) errorMessage += "No Absen kosong ";
                    if (!nama_lengkap) errorMessage += "Nama Siswa kosong ";
                    if (!tempat_lahir) errorMessage += "Tempat Lahir kosong ";
                    if (!tgl_lahir) errorMessage += "Tanggal Lahir kosong ";
                    if (!alamat) errorMessage += "Alamat kosong ";
                    if (!nama_wali) errorMessage += "Nama Wali kosong ";
                    if (!no_tlp_wali) errorMessage += "No. Telpon Wali kosong ";

                    if (errorMessage) isValid = false;
                    tableImportGuru.row.add([
                        `${index + 1}.`,
                        nis,
                        no_absen,
                        nama_lengkap,
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

        const kelas = $('#kelas_id_import').val();
        let tableData = [];
        let isValid = true;

        $("#importPreview tbody tr").each(function () {
            let row = $(this).find("td");

            let data = {
                nis: row.eq(1).text().trim(),
                no_absen: row.eq(2).text().trim(),
                nama_lengkap: row.eq(3).text().trim(),
                tempat_lahir: row.eq(4).text().trim(),
                tgl_lahir: row.eq(5).text().trim(),
                alamat: row.eq(6).text().trim(),
                nama_wali: row.eq(7).text().trim(),
                no_tlp_wali: row.eq(8).text().trim(),
            };

            if (Object.values(data).some(v => !v)) {
                isValid = false;
                return false;
            }

            tableData.push(data);
        });

        if (!kelas || !isValid) {
            Swal.fire(
                "Gagal!",
                "Pastikan semua data sudah benar dan kelas sudah dipilih.",
                "error"
            );
            return;
        }

        let insertedCount = 0;
        let swalClosed = false;

        function closeSwalOnce() {
            if (!swalClosed) {
                Swal.close();
                swalClosed = true;
            }
        }

        // 🔥 OPEN SWEETALERT (ONCE)
        Swal.fire({
            title: "Import Data",
            html: `Menyiapkan import...<br><b>0/${tableData.length}</b>`,
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch(`{{ route('guru.siswa.import') }}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token.attr('content')
            },
            body: JSON.stringify({
                kelas_id: kelas,
                data: tableData
            })
        })
        .then(response => {

            const reader = response.body.getReader();
            const decoder = new TextDecoder();

            function read() {
                reader.read().then(({ done, value }) => {

                    // 🚫 DONE STREAM ≠ DONE PROCESS
                    if (done) return;

                    const lines = decoder.decode(value).trim().split("\n");

                    for (const line of lines) {
                        if (!line) continue;

                        let data;
                        try {
                            data = JSON.parse(line);
                        } catch {
                            continue;
                        }

                        // 🔥 ERROR
                        if (data.type === 'error') {
                            closeSwalOnce();

                            Swal.fire(
                                "Gagal Import!",
                                data.message,
                                "error"
                            );

                            reader.cancel();
                            return;
                        }

                        // 🟡 PROGRESS
                        if (data.type === 'progress') {
                            insertedCount = data.processed;

                            Swal.update({
                                html: `
                                    Proses import sedang berjalan...<br>
                                    <b>${data.progress}</b>
                                `
                            });
                        }

                        // ✅ DONE (ONLY FROM SERVER)
                        if (data.type === 'done') {
                            insertedCount = data.processed;

                            closeSwalOnce();

                            Swal.fire(
                                "Selesai!",
                                `Berhasil mengimport ${insertedCount} dari ${tableData.length} data.`,
                                "success"
                            );

                            $("#importModal").modal("hide");
                            tableImportGuru.clear().draw();
                            $('#datatable').DataTable().ajax.reload();

                            return;
                        }
                    }

                    read();
                });
            }

            read();
        })
        .catch(() => {
            closeSwalOnce();
            Swal.fire("Gagal!", "Terjadi kesalahan jaringan.", "error");
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
})
</script>
@endpush