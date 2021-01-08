@extends('layouts.layout')

@section('title','Subscriptions')
@section('content')
    <div class="row">
        <div class="col-md-3">

            <!-- Simple card -->
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="card-title font-16 mt-0">Company Subscriptions</h4>
                    <table class="table table-striped table-bordered dt-responsive nowrap">
                        <tbody>
                        @foreach($companys as $company)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$company->name}}</td>
                                        <td><a type="button" class="btn btn-success waves-effect waves-light" href="/user/{{ $company->id }}" style="margin: 5px"><i class="fab fa-wpexplorer"></i> View Invoice</a> <button type="button" class="btn btn-info waves-effect waves-light" style="margin: 5px"><i class="fas fas fa-user-edit"></i>Change Plan</button>
                                            <form method="POST" action="/subscription-cancel">
                                                @csrf
                                                <input type="hidden" class="form-control" required data-parsley-minlength="2" value="{{$company->id}}" name="companyid"/>

                                                <button type="submit" class="btn btn-outline-danger waves-effect waves-light" style="margin: 5px"><i class="fas fa-user-alt-slash"></i>Cancel</button>
                                            </form>
                                        </td>
                                    </tr>

                            @endforeach
                        </tbody>
                    </table>

                    <p></p>
                    <a class="btn btn-primary waves-effect waves-light" onclick="print();">Print</a>
                </div>
            </div>

        </div><!-- end col -->

        <div class="col-9">
            <div class="card m-b-30">
                <div class="card-body">

                    <h4 class="mt-0 header-title">Subscription List</h4>

                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Start date</th>
                            <th>Company</th>
{{--                            <th>Action</th>--}}
                        </tr>
                        </thead>


                        <tbody>

                            @foreach($invoices as $invoice)
                                <tr>
                                    <td>{{$i++}}</td>
{{--                                    <td>{{$data->last_name}} {{$data->first_name}}</td>--}}
                                    <td>{{ $invoice->total() }}</td>
{{--                                    <td>{{$data->stripe_plan}}</td>--}}
{{--                                    <td>{{$data->stripe_status}}</td>--}}
                                    <td>{{ $invoice->date()->toFormattedDateString() }}</td>
{{--                                    <td>{{$data->company}}</td>--}}
{{--                                    <td><a type="button" class="btn btn-success waves-effect waves-light" href="/user/invoice/{{ $data->id }}"><i class="fab fa-wpexplorer"></i> View</a> <button type="button" class="btn btn-info waves-effect waves-light"><i class="fas fas fa-user-edit"></i>Edit</button> <button type="button" class="btn btn-outline-danger waves-effect waves-light"><i class="fas fa-user-alt-slash"></i>Cancel</button></td>--}}
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
