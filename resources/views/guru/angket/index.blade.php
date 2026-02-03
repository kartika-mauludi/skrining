@extends('guru.layout.index')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Angket</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Angket</li>
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
                        </div>
                        
                        <div class="card-body">
                            <table id="datatable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Sekolah</th>
                                        <th>Kelas</th>
                                        <th>Nama Angket</th>
                                        <th>Akses Token</th>
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
    </section>
</div>

@include('guru.angket.modal')
@endsection

@push('script')
<script>
$(document).ready(function(){
    var token = $('meta[name="csrf-token"]');

    $('#sekolah_id').select2({
        placeholder: '-- Pilih Data --',
        dropdownParent: $('#dataModal')
    });

    var table = $('#datatable').DataTable({
        processing  : true,
        serverSide  : false,
        ajax: {
            url: "{{ route('guru.angket.data') }}",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': token.attr('content')
            }
        },
        columns: [{
            data : null, render:(data,type,row,meta)=>{
                return `<div class='text-center'>${meta.row + 1}.</div>`;
            }
        },{
            data    : 'sekolah.nama_sekolah',
            render  : (data) => data ? `${data}` : `-`
        },{
            data    : 'kelas.nama_kelas',
            render  : (data) => data ? `${data}` : `-`
        },{
            data    : 'nama_angket',
            render  : (data) => data ? `${data}` : `-` 
        },{
            data    : 'akses_token',
            render  : (data) => data ? `${data}` : `-` 
        },{
        data: 'id',
            render: function(data, type, row){
                let editUrl = "{{ route('guru.angket.edit', ':id') }}";
                let deleteUrl = "{{ route('guru.angket.destroy', ':id') }}";

                editUrl = editUrl.replace(':id', data);
                deleteUrl = deleteUrl.replace(':id', data);

                return `
                    <div class="btn-group">
                        <button class="btn btn-sm btn-warning edit-btn" data-url="${editUrl}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-url="${deleteUrl}">Hapus</button>
                    </div>
                `;
            }
        }]
    });

    $('#sekolah_id').on('change', function() {
        const id = $(this).val();

        $.get(`/guru/sekolah/${id}`, function (data, status) {
            if (status != 'success') {
                swal.fire('Kesalahan sistem','Silahkan hubungi administrator','error')
                return
            }

            const parent = $('#kelas_id');
            const kelas  = data['data']['kelas'];
            let child = '<option value=""></option>'; 

            kelas.forEach(element => {
                child += `<option value="${element.id}">${element.nama_kelas}</option>`;
            });
            
            parent.html(child).select2({
                placeholder: '-- Pilih Data --',
                dropdownParent: $('#dataModal')
            });
        })
    });

    $('#add').on('click', function () {
        let storeUrl = "{{ route('guru.angket.store') }}";

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

            let updateUrl = "{{ route('guru.angket.update', ':id') }}";
            updateUrl = updateUrl.replace(':id', data['data']['id']);
        
            $('#method').val('PUT')
            $('#nama_kelas').val(data['data']['nama_kelas']);

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

    let soalIndex = 0;

    // ===============================
    // ADD NEW QUESTION
    // ===============================
    $('.addNew').on('click', function () {
        const parent = $('#soal-container');
        const idx = soalIndex++;

        let input = `
        <div class="soal-input border p-2 rounded mt-3" data-index="${idx}">
            <div class="d-flex align-items-start">
                <span class="drag-handle mr-2">☰</span>
                <span role="button" class="text-danger mr-2 rmInput">
                    <i class="fas fa-trash"></i>
                </span>

                <div class="w-100">
                    <textarea
                        name="soal[${idx}][pertanyaan]"
                        class="form-control form-control-sm mb-2"
                        placeholder="Pertanyaan"
                        required></textarea>

                    <div class="d-flex mb-2">
                        <select
                            name="soal[${idx}][tipe]"
                            class="form-control form-control-sm w-25 mr-1 tipe-soal"
                            required>
                            <option value="">Tipe</option>
                            <option value="radio">Radio</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="text">Text</option>
                        </select>

                        <input
                            type="number"
                            name="soal[${idx}][bobot]"
                            class="form-control form-control-sm w-25 bobot"
                            value="1"
                            required>
                    </div>

                    <div class="opsi-container"></div>

                    <button type="button"
                        class="btn btn-sm btn-outline-primary add-opsi mt-1"
                        style="display:none;">
                        + Tambah Opsi
                    </button>

                    <small class="text-muted nilai-info"></small>
                </div>
            </div>
        </div>
        `;

        parent.append(input);
    });

    // ===============================
    // REMOVE QUESTION
    // ===============================
    $(document).on('click', '.rmInput', function () {
        $(this).closest('.soal-input').remove();
    });

    // ===============================
    // CHANGE TYPE
    // ===============================
    $(document).on('change', '.tipe-soal', function () {
        const soal = $(this).closest('.soal-input');
        const idx = soal.data('index');
        const tipe = $(this).val();
        const opsiContainer = soal.find('.opsi-container');
        const btnAdd = soal.find('.add-opsi');

        opsiContainer.empty();
        soal.find('.nilai-info').text('');

        if (tipe === 'radio' || tipe === 'checkbox') {
            for (let i = 0; i < 2; i++) {
                opsiContainer.append(renderOpsi(idx, i));
            }
            btnAdd.show();
            updateNilai(soal);
        } else {
            btnAdd.hide();
        }
    });

    // ===============================
    // ADD OPTION
    // ===============================
    $(document).on('click', '.add-opsi', function () {
        const soal = $(this).closest('.soal-input');
        const idx = soal.data('index');
        const opsiIndex = soal.find('.opsi-item').length;
        soal.find('.opsi-container').append(renderOpsi(idx, opsiIndex));
        updateNilai(soal);
    });

    // ===============================
    // REMOVE OPTION
    // ===============================
    $(document).on('click', '.remove-opsi', function () {
        const soal = $(this).closest('.soal-input');
        if (soal.find('.opsi-item').length <= 2) {
            alert('Minimal 2 opsi');
            return;
        }
        $(this).closest('.opsi-item').remove();
        updateNilai(soal);
    });

    // ===============================
    // UPDATE SCORE
    // ===============================
    $(document).on('input', '.bobot', function () {
        updateNilai($(this).closest('.soal-input'));
    });

    // ===============================
    // FUNCTIONS
    // ===============================
    function renderOpsi(idx, opsiIndex) {

        return `
        <div class="d-flex align-items-center opsi-item mb-1">
            <input type="text"
                name="soal[${idx}][opsi][${opsiIndex}][label]"
                class="form-control form-control-sm mr-1"
                placeholder="Pilihan"
                required>

            <input type="hidden"
                name="soal[${idx}][opsi][${opsiIndex}][nilai]"
                class="nilai-input">

            <span role="button" class="text-danger remove-opsi">
                <i class="fas fa-times"></i>
            </span>
        </div>
        `;
    }

    function updateNilai(soal) {
        const bobot = parseFloat(soal.find('.bobot').val()) || 1;
        const tipe = soal.find('.tipe-soal').val();
        const opsi = soal.find('.opsi-item');

        if (!opsi.length) return;

        let info = '';

        if (tipe === 'radio') {
            const step = opsi.length > 1 ? bobot / (opsi.length - 1) : 0;

            opsi.each(function (i) {
                const nilai = (step * i).toFixed(2);
                $(this).find('.nilai-input').val(nilai);
                info += (i === 0 ? '' : ', ') + nilai;
            });

            info = 'Nilai radio: ' + info;
        }

        if (tipe === 'checkbox') {
            const step = (bobot / opsi.length).toFixed(2);

            opsi.each(function () {
                $(this).find('.nilai-input').val(step);
            });

            info = `Nilai per pilihan: ${step} (maks ${bobot})`;
        }

        soal.find('.nilai-info').text(info);
    }
});
</script>
@endpush