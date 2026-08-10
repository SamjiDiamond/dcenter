@extends('layouts.layout')

@section('title','User')
@section('content')
    <div class="row">
        <div class="col-md-6">
        <p>Upload Profile Picture</p>
            <form action="{{route('user.uploadImage')}}" method="post" enctype="multipart/form-data" class="">
                @csrf
                <input  type="file" id="formFileDisabled" name="image"/>
                <input type="submit" value="upload" class="btn btn-primary" />
            </form>
            <!-- Simple card -->
            <div class="card m-b-30">
                <img class="card-img-top img-fluid" src= "{{URL::to('/')}}/public/images/{{auth()->user()->image }}" alt="profile image">
                <div class="card-body">
                    <h4 class="card-title font-16 mt-0">{{$user->last_name}} {{$user->first_name}}</h4>
                    <p class="card-text">Wallet Balance: {{$user->wallet}}</p>
                    @can('user-edit')
                    <a href="/user-edit/{{ $user->uuid }}" class="btn btn-primary waves-effect waves-light">Edit</a>
                    @endcan


                        @if($user->company_id == auth()->user()->company_id || auth()->user()->company_id == 1)
                            @if($user->status == "active")
                                @can('user-disable')
                                    <a href="/user-disable/{{ $user->uuid }}" type="button" class="btn btn-outline-warning waves-effect waves-light" style="margin: 5px">Disable</a>
                                @endcan
                            @else
                                @can('user-enable')
                                    <a href="/user-enable/{{ $user->uuid }}" type="button" class="btn btn-outline-warning waves-effect waves-light" style="margin: 5px">Enable</a>
                                @endcan
                            @endif
                        @endif

                </div>
            </div>

        </div><!-- end col -->

        <div class="col-6">

            <div class="card m-b-30">
                <div class="card-body">

                    <h4 class="mt-0 header-title">Customer Information</h4>
                    {{--<p class="text-muted m-b-30">Align terms and descriptions
                        horizontally by using our grid system’s predefined classes (or semantic
                        mixins). For longer terms, you can optionally add a <code
                            class="highlighter-rouge">.text-truncate</code> class to
                        truncate the text with an ellipsis.</p>
--}}
                    <p></p>
                    <dl class="row mb-0">
                        <dt class="col-sm-3">Last Name:</dt>
                        <dd class="col-sm-9">{{$user->last_name}}</dd>

                        <dt class="col-sm-3">First Name:</dt>
                        <dd class="col-sm-9">{{$user->first_name}}</dd>

                        <dt class="col-sm-3">Customer Ref:</dt>
                        <dd class="col-sm-9">
                            <b>{{ $user->reference }}</b>
                            <small class="d-block text-muted">{{$user->uuid}}</small>
                        </dd>
{{--                        <dd class="col-sm-9 offset-sm-3">Donec id elit non mi porta gravida at eget metus.</dd>--}}

                        <dt class="col-sm-3">Status</dt>
                        <dd class="col-sm-9">@include('partials.status-badge', ['status' => $user->status])</dd>

                        <dt class="col-sm-3">Company</dt>
                        <dd class="col-sm-9">{{$user->company->name}}</dd>

                        <dt class="col-sm-3">Account Type</dt>
                        <dd class="col-sm-9">{{$user->account_type}}</dd>

                        <dt class="col-sm-3">Date Created: </dt>
                        <dd class="col-sm-9">{{$user->created_at}}</dd>

                        <dt class="col-sm-3">Last Login: </dt>
                        <dd class="col-sm-9">{{$user->updated_at}}</dd>

                    </dl>

                </div>
            </div>

        </div> <!-- end col -->
    </div> <!-- end row -->

    <div class="row">
        <div class="col-xl-9">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-4">Latest Transaction</h4>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive">
                            <thead>
                            <tr>
                                <th scope="col">ID No.</th>
                                <th scope="col">Date</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Status</th>
                                <th scope="col">Balance</th>
                                <th scope="col">I.P</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($transactions as $transaction)
                            <tr>
                                <th scope="row">{{$i++}}</th>
                                <td>{{$transaction->date}}</td>
                                <td>{{$transaction->amount}}</td>
                                <td>{{$transaction->description}}</td>
                                <td>
                                    <div class="progress" style="height: 5px;">
                                        @if($transaction->status=='delivered' || $transaction->status=='successful')
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                            @else
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 20%;" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                                            @endif
                                    </div>
                                </td>
                                <td>#{{$transaction->f_wallet}}</td>
                                <td>{{$transaction->ip_address}}</td>
                            </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Sales Analytics</h4>
                    <div id="morris-donut-example" class="morris-chart" style="height: 300px"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
@stop


@section('after-scripts')
    <!-- Required datatable js -->
    <script src="../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../plugins/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Buttons examples -->
    <script src="../plugins/datatables/dataTables.buttons.min.js"></script>
    <script src="../plugins/datatables/buttons.bootstrap4.min.js"></script>
    <script src="../plugins/datatables/jszip.min.js"></script>
    <script src="../plugins/datatables/pdfmake.min.js"></script>
    <script src="../plugins/datatables/vfs_fonts.js"></script>
    <script src="../plugins/datatables/buttons.html5.min.js"></script>
    <script src="../plugins/datatables/buttons.print.min.js"></script>
    <script src="../plugins/datatables/buttons.colVis.min.js"></script>
    <!-- Responsive examples -->
    <script src="../plugins/datatables/dataTables.responsive.min.js"></script>
    <script src="../plugins/datatables/responsive.bootstrap4.min.js"></script>

    <!-- Datatable init js -->
    <script src="../assets/pages/datatables.init.js"></script>

    <!--Morris Chart-->
    <script src="../plugins/morris/morris.min.js"></script>
    <script src="../plugins/raphael/raphael.min.js"></script>

    <script>
    !function ($) {
    "use strict";

    var Dashboard = function () {
    };

    //creates Donut chart
    Dashboard.prototype.createDonutChart = function(element, data, colors) {
    Morris.Donut({
    element: element,
    data: data,
    resize: true,
    labelColor: '#fff',
    backgroundColor: '#13274e',
    colors: colors
    });
    },

    Dashboard.prototype.init = function () {

    //creating donut chart
    var $donutData = [
    {label: "Data Sales", value:  {{ $data }}},
    {label: "Airtime Sales", value: {{ $airtime }}},
    {label: "TV Subscription Sales", value: {{ $tv }}},
    {label: "Electricity Sales", value: {{ $electricity }}},
    {label: "Transfer", value: {{ $transfer }}}
    ];
    this.createDonutChart('morris-donut-example', $donutData, ['#4bbbce', '#5985ee', '#46cd93', '#926203', '#339437' ]);

    },

    //init
    $.Dashboard = new Dashboard, $.Dashboard.Constructor = Dashboard
    }(window.jQuery),

    //initializing
    function ($) {
    "use strict";
    $.Dashboard.init();
    }(window.jQuery);
    </script>
@stop

@section('before-styles')
    <!-- App Icons -->
    <link rel="shortcut icon" href="../assets/images/favicon.ico">

    <!-- DataTables -->
    <link href="../plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="../plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- morris css -->
    <link rel="stylesheet" href="../plugins/morris/morris.css">
@stop
