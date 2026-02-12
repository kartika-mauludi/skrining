@extends('admin.layout.index')

@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Log Login</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Log Login User</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="logTable">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Email</th>
                            <th>User ID</th>
                            <th>IP Address</th>
                            <th>User Agent</th>
                            <th>Status</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $log->email ?? '-' }}</td>
                            <td>{{ $log->user_id ?? '-' }}</td>
                            <td>{{ $log->ip_address }}</td>
                            <td style="max-width:250px; white-space:normal;">
                                {{ $log->user_agent }}
                            </td>
                            <td>
                                @if($log->status == 'success')
                                    <span class="badge bg-success">Success</span>
                                @else
                                    <span class="badge bg-danger">Failed</span>
                                @endif
                            </td>
                            <td>{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
    </section>
    <!-- /.content -->
  </div>

@endsection

@push('script')
<script>
  $(document).ready(function () {
    $('#logTable').DataTable({
        order: [[6, 'desc']]
    });
});
</script>
@endpush

