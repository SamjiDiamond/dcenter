@extends('layouts.layout')

@section('title','SMS Configurations Settings')
@section('content')
    <div class="row">
        <div class="col-lg-12">

            <div class="card m-b-30">
                <div class="card-body">

                    <h4 class="mt-0 header-title">SMS Charge Configuration</h4>
                    <p class="text-muted m-b-30"> </p>

                    <h6 class="text-muted">SMS Charge Type: </h6>
                    <span class="text-muted m-b-15">
                        <code>per message</code>
                    </span>

                    <div class="m-t-20">
                        <h6 class="text-muted">SMS Cost:</h6>
                        <span class="text-muted m-b-15">
                            <code>#5</code>
                        </span>
                    </div>

                </div>
            </div>

            <div class="card m-b-30">
                <div class="card-body">

                    <h4 class="mt-0 header-title">Birthday SMS Configuration</h4>
                    <p class="text-muted m-b-30"> </p>

                    <h6 class="text-muted">Charge customer: </h6>
                    <span class="text-muted m-b-15">
                        <code>true</code>
                    </span>

                    <div class="m-t-20">
                        <h6 class="text-muted">SMS Message:</h6>
                        <span class="text-muted m-b-15">
                            <code>Happy Birthday!! to our amiable customer, without you we are nothing. Happy Birthday Samuel.</code>
                        </span>
                    </div>

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
