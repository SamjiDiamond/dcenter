@extends('layouts.layout')

@section('title','Recharge Card List')
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

                    @if($apiError)
                     <div class="alert alert-danger">{{ $apiError }}</div>
                    @endif

                    <h4 class="mt-0 header-title">Reversal List</h4>

                        <table id="datatable-buttons"  class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Plan</th>
                                                        <th>Amount</th>
                                                        <th>MTN</th>
                                                        <th>NINE MOBILE</th>
                                                        <th>AIRTEL</th>
                                                        <th>GLO</th>
                                                        <th>Status</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        {{-- @dd($rechargeCardLists) --}}
                                                        @if($rechargeCardLists)
                                                    @forelse($rechargeCardLists as $key => $rechargeCardList)
                                                    <tr>
                                                        <th scope="row">{{$sn++}}</th>
                                                        <td>{{$rechargeCardList['plan']}}</td>
                                                        <td>{{$rechargeCardList['amount']}}</td>
                                                        <td>{{$rechargeCardList['mtn']}}</td>
                                                        <td>{{$rechargeCardList['ninemobile']}}</td>
                                                        <td>{{$rechargeCardList['airtel']}}</td>
                                                        <td>{{$rechargeCardList['glo']}}</td>
                                                        <td>{{$rechargeCardList['status']}}</td>
                                                    
                                                    </tr>

                                                    @empty
                                                    <tr><td colspan="9" class="text-center">No Recharge card list to display.</td></tr>
                                                    @endforelse
                                                    @endif
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
