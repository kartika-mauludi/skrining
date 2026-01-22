@extends('admin.layout.index')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Master Peringkat</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
              <li class="breadcrumb-item active">Master Peringkat</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
<!-- peringkat  -->
          <div class="col-7">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Table Peringkat</h3>
                <div class="text-right">
                  <button class="btn btn-success p-1" id="add"  data-toggle="modal" data-target="#addData"> Tambah </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="tbl-rank" class="table table-bordered table-striped">
                 <thead>
                  <tr>
                    <th>No</th>
                    <th>Name Rank</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
          
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>No</th>
                    <th>Name Rank</th>
                    <th>Action</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
        </div>
<!-- periode -->
         <div class="col-5">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Table Periode</h3>
                <div class="text-right">
                  <button class="btn btn-success p-1" id="addPeriode"  data-toggle="modal" data-target="#addDataPeriode"> Tambah </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="tbl-periode" class="table table-bordered table-striped">
                 <thead>
                  <tr>
                    <th>No</th>
                    <th>Periode</th>
                    <th>Edisi</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
          
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>No</th>
                    <th>Periode</th>
                    <th>Edisi</th>
                    <th>Action</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
        </div>
      </div>
    </div>
</section>
</div>

 @include('admin.masterperingkat.modal')
@endsection

@include('admin.masterperingkat.js');
