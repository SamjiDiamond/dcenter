@extends('layouts.layout')

@section('title','Service Change')
@section('content')
    <div class="row">
        <div class="col-md-3">

            <!-- Simple card -->
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="card-title font-16 mt-0">Service Charge Search</h4>

                    <div class="form-group mb-0">
                        <label>Date Range</label>
                        <div>
                            <div class="input-daterange input-group" id="date-range">
                                <input type="text" class="form-control" name="start" placeholder="Start Date" />
                                <input type="text" class="form-control" name="end" placeholder="End Date" />
                            </div>
                        </div>
                    </div>

                    <p></p>
                    <a href="#" class="btn btn-primary waves-effect waves-light">Search</a>
                    <a href="#" class="btn btn-primary waves-effect waves-light">Reset</a>
                    <a class="btn btn-primary waves-effect waves-light" onclick="print();">Print</a>
                </div>
            </div>

        </div><!-- end col -->

        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>


                        <tbody>
                        @if($i??'')
                        @foreach($users as $user)
                            <tr>
                                <td>{{$i++}}</td>
                                <td><img class="d-flex mr-3 rounded-circle" src="assets/images/users/avatar-2.jpg" alt="" height="64">
                                    {{$user->last_name}} {{$user->first_name}}</td>
                                <td>{{$user->role}}</td>
                                <td>{{$user->phoneno}}</td>
                                <td>{{$user->created_at}}</td>
                                <td>{{$user->wallet}}</td>
                                <td>{{$user->wallet}}</td>
                                <td> <button type="button" class="btn btn-info waves-effect waves-light"><i class="fas fas fa-user-edit"></i>Edit</button> <button type="button" class="btn btn-outline-danger waves-effect waves-light"><i class="fas fa-user-alt-slash"></i>Suspend</button></td>
                            </tr>
                        @endforeach
                            @endif


                        </tbody>
                    </table>

                </div>
            </div>
        </div>
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
