@extends('layouts.layout')

@section('title','Deposit Monitoring')
@section('content')
    @component('partials.collapsible-filter', ['filterTitle' => 'Bank Transfer Deposit Search'])
        @slot('form')
            <form action="{{route('bank.tranfer.deposit')}}" method="GET">
                <p class="card-text">Customer Id: <input type="text" name="customer_id" value="{{old('customer_id') ?? request('customer_id')}}" id ="customerId"  class="form-control" required data-parsley-minlength="2"/></p>
                <p class="card-text">Transaction Id: <input type="text" name="transaction_id" value="{{old('transaction_id') ?? request('transaction_id')}}" id="transactionId" class="form-control" required data-parsley-minlength="2"/></p>
                {{-- <p class="card-text">Date: <input class="form-control" type="date" value="{{now()->toDateString()}}" id="example-date-input"> --}}

                <div class="form-group mb-0">
                    <label>Date Range</label>
                    <div>
                        <div class="input-daterange" id="date-range">
                            <div class="form-group m-2">
                                <label for="">Start Date</label>
                            <input class="form-control" type="date" name="start_date" value="2011-08-19" id="example-date-input">
                            </div>

                            <div class="form-group m-2">
                                <label for="">End Date</label>
                                <input class="form-control" type="date" name="end_date" value="{{now()->toDateString()}}" id="example-date-input">
                            </div>


                            </div>
                        </div>
                    </div>

                    <p></p>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">Search</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" id="resetId">Reset</button>
                    <a class="btn btn-primary waves-effect waves-light" onclick="print();">Print</a>
                </form>
        @endslot

        <div class="card">
            <div class="card-body">
                <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Initial Deposit</th>
                        <th>Lodgement</th>
                        <th>Amount</th>
                        <th>Balance</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Deposit Date</th>
                        <th>Created At</th>
                    </tr>
                    </thead>


                    <tbody>
                    @if(isset($deposits))
                    @foreach($deposits as $deposit)
                        <tr>
                            <td>{{$sn++}}</td>
                            <td>{{$deposit->user->last_name}} {{$deposit->user->first_name}}</td>
                            <td>{{$initialDeposit}}</td>
                            <td>{{$deposit->logement}}</td>
                            <td>{{$deposit->amount}}</td>
                            <td>{{$deposit->balance}}</td>
                            <td>{{$deposit->phone}}</td>
                            <td>{{$deposit->address}}</td>
                            <td>{{$deposit->dateDate}}</td>
                            <td>{{$deposit->created_at}}</td>
                        </tr>
                    @endforeach
                        @endif
                    </tbody>
                </table>

            </div>
        </div>
    @endcomponent
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
            $("#customerId").val('');
            $("#transactionId").val('');

        });

    </script>

    @include('partials.collapsible-filter-scripts')
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
