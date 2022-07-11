@extends('layouts.layout')

@section('title','Recharge Cards')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>Warning! {{ session('error') }} </strong>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>Success! {{ session('success') }} </strong>
                        </div>
                    @endif

                    <h4 class="mt-0 header-title">Recharge Card List</h4>

                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>default_id</th>
                            <th>code</th>
                            <th>price</th>
                            <th>status</th>
                            <th>desc</th>
                            <th>identifier</th>
                            {{-- <th>Action</th> --}}
                        </tr>
                        </thead>


                        <tbody>
                        @foreach($recharge as $recharges)
                        <tr>
                            {{-- <td>{{$i++}}</td> --}}
                            <td><img class="d-flex mr-3 rounded-circle" src="assets/images/users/avatar-2.jpg" alt="" height="64">
                            <td>{{$recharges->default_id}}</td>
                            <td>{{$recharges->code}}</td>
                            <td>{{$recharges->price}}</td>
                            <td>{{$recharges->status}}</td>
                            <td>{{$recharges->desc}}</td>
                            <td>{{$recharges->identifier }}</td>
                            {{-- <td>
                                <a type="button" class="btn btn-success waves-effect waves-light" href="/user/{{ $recharges->id }}"><i class="fab fa-wpexplorer"></i> View</a>
                                @can('edit-user')
                                <a href="/user-edit/{{ $recharges->id }}" type="button" class="btn btn-info waves-effect waves-light"><i class="fas fas fa-user-edit"></i>Edit</a>
                                    @endcan
                            </td> --}}
                        </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@stop

@section('after-scripts')
    <!-- Required datatable js -->
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Buttons examples -->
    <script src="plugins/datatables/dataTables.buttons.min.js"></script>
    <script src="plugins/datatables/buttons.bootstrap4.min.js"></script>
    <script src="plugins/datatables/jszip.min.js"></script>
    <script src="plugins/datatables/pdfmake.min.js"></script>
    <script src="plugins/datatables/vfs_fonts.js"></script>
    <script src="plugins/datatables/buttons.html5.min.js"></script>
    <script src="plugins/datatables/buttons.print.min.js"></script>
    <script src="plugins/datatables/buttons.colVis.min.js"></script>
    <!-- Responsive examples -->
    <script src="plugins/datatables/dataTables.responsive.min.js"></script>
    <script src="plugins/datatables/responsive.bootstrap4.min.js"></script>

    <!-- Datatable init js -->
    <script src="assets/pages/datatables.init.js"></script>
@stop

@section('before-styles')
<!-- App Icons -->
<link rel="shortcut icon" href="assets/images/favicon.ico">

<!-- DataTables -->
<link href="plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

<!-- Responsive datatable examples -->
<link href="plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@stop
