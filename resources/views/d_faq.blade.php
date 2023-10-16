@extends('layouts.layout')

@section('title','FAQ')
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

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>Success! {{ session('success') }} </strong>
                        </div>
                    @endif

                    <h4 class="mt-0 header-title">Faq List</h4>

                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                          
                            <th>Topic</th>
                            <th>Content</th>
                            <th>Posted By</th>
                            <th>Company Id</th>
                            
                        </tr>
                        </thead>


                        <tbody>
                        @foreach($vfaq as $vfaqs)
                        <tr>
                            {{-- <td>{{$i++}}</td> --}}

                            <td>{{$vfaqs->title}}</td>
                            <td>{{$vfaqs->content}}</td>
                            <td>{{$vfaqs->posted_by}}</td>
                            <td>{{$vfaqs->company_id}}</td>
                            
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
