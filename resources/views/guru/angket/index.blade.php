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
                        
                        <div class="card-body">
                            <table id="datatable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Angket</th>
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
@endsection

@push('script')
<script>
$(document).ready(function(){
    var token = $('meta[name="csrf-token"]');

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
            data    : 'nama_angket',
            render  : (data) => data ? `${data}` : `-` 
        },{
            data: 'id',
            render: function(data, type, row){
                let soalUrl = "{{ route('guru.soal.index') }}?angket_id=" + data;

                return `
                    <div class="btn-group">
                        <a class="btn btn-sm btn-info" href="${soalUrl}">Soal</a>
                    </div>
                `;
            }
        }]
    });
});
</script>
@endpush