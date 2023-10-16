@extends('layouts.layout')

@section('title','New Account Report')
@section('content')
    <div class="row">
        <div class="col-md-3">

            <!-- Simple card -->
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="card-title font-16 mt-0">New Account Report Search</h4>
                    <form action="{{route('account.index')}}" method="GET">
                        <p class="card-text">Introducer: <input type="text" name="introducer" value="{{request()->query('introducer') ?? ''}}" class="form-control" required data-parsley-minlength="2" id="introducerId"/></p>
                        <p class="card-text">Company: <input type="text" name="company" value="{{request('company') ?? ''}}" class="form-control" required data-parsley-minlength="2" id="companyId"/></p>
                        <p class="card-text">From Date: <input class="form-control" type="date" name="fromDate" value="{{ request()->query('fromDate') ?? now()->toDateString()}}" id="example-date-input">
                        <p class="card-text">To Date: <input class="form-control" type="date" name="toDate" value="{{request()->query('toDate') ?? now()->toDateString()}}" id="example-date-input">
    
                        <p></p>
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Search</button>
                        <button type="button" class="btn btn-primary waves-effect waves-light" id="resetId">Reset</button>
                        <a class="btn btn-primary waves-effect waves-light" onclick="print();">Print</a>
                    </form>
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
                            <th>Phoneno</th>
                            <th>Email</th>
                            <th>Wallet</th>
                            <!--<th>Gender</th>-->
                            <th>Action</th>
                        </tr>
                        </thead>


                        <tbody>
                        @if(isset($accounts))
                        @forelse($accounts as $account)
                            <tr>
                                <td>{{$sn++}}</td>
                                <td><img class="d-flex mr-3 rounded-circle" src="assets/images/users/avatar-2.jpg" alt="" height="64">
                                    {{$account->last_name}} {{$account->first_name}}</td>
                                <td>{{$account->phoneno}}</td>
                                <td>{{$account->email}}</td>
                                <td>400</td>
                                <!--<td>{{$account->gender}}</td>-->
                                <td> <button type="button" class="btn btn-info waves-effect waves-light"><i class="fas fas fa-user-edit"></i>Edit</button> <button type="button" class="btn btn-outline-danger waves-effect waves-light"><i class="fas fa-user-alt-slash"></i>Suspend</button></td>
                            </tr>
                        @empty
                        <tr><td colspan="6" class="text-center">Empty</td></tr>
                        @endforelse
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
    
    <script>
    
    $("#resetId").on('click', function(){
        $("#introducerId").val('');
        $("#companyId").val('');
      
    });
        
    </script>
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
