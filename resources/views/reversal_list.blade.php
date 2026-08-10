@extends('layouts.layout')

@section('title','Reversal')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">

                    <h4 class="mt-0 header-title">Reversal List</h4>

                        <table id="datatable-buttons"  class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Company Name</th>
                                                        <th>Reference No.</th>
                                                        <th>Amount</th>
                                                        <th>Description</th>
                                                        <th>Status</th>
                                                        <th>Old Wallet </th>
                                                        <th>New Wallet</th>
                                                        <th>Created at</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @forelse($transactions as $transaction)
                                                    <tr>
                                                        <th scope="row">{{ $sn++ }}</th>
                                                        <td>{{ $transaction->company->name }}</td>
                                                        <td>{{ $transaction->reference_id }}</td>
                                                        <td>{{ $transaction->amount}}</td>
                                                        <td>{{ $transaction->description }}</td>
                                                        <td>{{ $transaction->status }}</td>
                                                        <td>{{ $transaction->companyWallet->old_wallet }}</td>
                                                        <td>{{ $transaction->companyWallet->new_wallet }}</td>
                                                         <td>{{ $transaction->created_at }}</td>
                                                      
                                                    </tr>

                                                    @empty
                                                    <tr><td colspan="9" class="text-center">No reversal transactions to display.</td></tr>
                                                    @endforelse
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
