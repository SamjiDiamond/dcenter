@extends('layouts.layout')

@section('title','Service Charge')
@section('content')
    <div class="row">
        <div class="col-md-3">

            <!-- Simple card -->
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="card-title font-16 mt-0">Service Charge Search</h4>

                    <form action="{{route('service.charge.index')}}" method="get">
                        <div class="form-group mb-0">
                            <div class="input-daterange" id="date-range">
                                <div class="form-group m-2">
                                    <label for="">Start Date</label>
                                <input class="form-control" type="date" name="start_date" value="{{request('start_date') ?? '2012-01-01'}}" id="example-date-input">
                                </div>
    
                                <div class="form-group m-2">
                                    <label for="">End Date</label>
                                    <input class="form-control" type="date" name="end_date" value="{{request('end_date') ?? now()->toDateString()}}" id="example-date-input">
                                </div>
                                
                            </div>
                        </div>
    
                        <p></p>
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Search</button>
                        {{-- <a href="#" class="btn btn-primary waves-effect waves-light">Reset</a> --}}
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
                            <th>Amount</th>
                            <th>User</th>
                            <th>Date</th>
                        </tr>
                        </thead>


                        <tbody>
                        @if(isset($serviceCharges))
                        @foreach($serviceCharges as $serviceCharge)
                            <tr>
                                <td>{{$sn++}}</td>
                                <td>{{$serviceCharge->name}}</td>
                                <td>{{$serviceCharge->amount}}</td>
                                <td>
                                    {{$serviceCharge->user->last_name}} {{$serviceCharge->user->first_name}}
                                </td>
                                <td>{{$serviceCharge->created_at}}</td>
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
