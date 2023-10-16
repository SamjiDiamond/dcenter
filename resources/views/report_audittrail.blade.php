@extends('layouts.layout')

@section('title','Audit Trail')
@section('content')
    <div class="row">
        <div class="col-md-3">

            <!-- Simple card -->
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="card-title font-16 mt-0">Audit trail Search</h4>
                    <form action="{{route('audit.trail.index')}}" method="GET">
                    <p class="card-text">Admin Id: <input type="text" id="adminId" value="{{request('adminId') ?? old('adminId')}}" name="adminId" class="form-control" required data-parsley-minlength="2"/></p>
                    <p class="card-text">Company Id: <input type="text" id="companyId" value="{{request('companyId') ?? old('companyId')}}" name="companyId" class="form-control" required data-parsley-minlength="2"/></p>
                    <p class="card-text">Date: <input class="form-control" type="date" name="date" value="{{now()->toDateString()}}" id="dateId">

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
                            <th>Action Initiator</th>
                            {{-- <th>Subject</th> --}}
                            <th>Action</th>
                            <th>Action Date</th>
                            <th>Data Type</th>
                            <th>Action</th>
                        </tr>
                        </thead>


                        <tbody>
                        @if(isset($auditTrails))
                        @foreach($auditTrails as $auditTrail)
                            <tr>
                                <td>{{$sn++}}</td>
                                <td><img class="d-flex mr-3 rounded-circle" src="assets/images/users/avatar-2.jpg" alt="" height="64">
                                    {{$auditTrail->admin->last_name}} {{$auditTrail->admin->first_name}}</td>
                                {{-- <td>{{$auditTrail->admin->role}}</td> --}}
                                <td>{{$auditTrail->action}}</td>
                                <td>{{$auditTrail->created_at}}</td>
                                <td>{{$auditTrail->type}}</td>
                                <td> <button type="button" class="btn btn-info waves-effect waves-light">
                                    <i class="fas fas fa-user-edit"></i>Edit</button> 
                                    <button type="button" class="btn btn-outline-danger waves-effect waves-light"><i class="fas fa-user-alt-slash"></i>Suspend</button>
                                </td>
                            </tr>
                        @endforeach
                        @else
                          
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
            $("#adminId").val('');
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
