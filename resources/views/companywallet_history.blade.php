@extends('layouts.layout')

@section('title','Company Transactions')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">

                    <h4 class="mt-0 header-title">Wallet History <span class="badge badge-info">Total: {{$transCount}}</span></h4>

                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <td>#</td>
                            <th>Payment ID</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Old Wallet</th>
                            <th>New Wallet</th>
                            <th>Date</th>
                            <th>Company Name</th>
{{--                            <th>Action</th>--}}
                        </tr>
                        </thead>


                        <tbody>
                        @foreach($trans as $tran)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$tran->paymentid}}</td>
                            <td>{{$tran->amount}}</td>
                            <td>{{$tran->description}}</td>
                            <td>{{$tran->status}}</td>
                            <td>{{$tran->old_wallet}}</td>
                            <td>{{$tran->new_wallet}}</td>
                            <td>{{$tran->created_at}}</td>
                            <td>{{$tran->company->name}}</td>
{{--                            <td> <button type="button" class="btn btn-info waves-effect waves-light"><i class="fas fas fa-user-edit"></i>Edit</button> <button type="button" class="btn btn-outline-danger waves-effect waves-light"><i class="fas fa-user-alt-slash"></i>Suspend</button></td>--}}
                        </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{$trans->links()}}

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
