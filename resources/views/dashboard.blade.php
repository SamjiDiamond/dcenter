@extends('layouts.layout')

@section('title','Dashboard')
@section('content')
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary mini-stat">
                <div class="p-3 mini-stat-desc">
                    <div class="clearfix">
                        <h6 class="text-uppercase mt-0 float-left text-white-50">Transactions</h6>
                        <h4 class="mb-3 mt-0 float-right"><span class="badge badge-dark font-12">Today</span> {{$today_order}}</h4>
                    </div>
                </div>
                <div class="p-3">
                    <div class="float-right">
                        <a href="#" class="text-white-50"><i class="mdi mdi-cube-outline h5"></i></a>
                    </div>
                    <p class="font-14 m-0">This Month : {{$month_order}}</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-info mini-stat">
                <div class="p-3 mini-stat-desc">
                    <div class="clearfix">
                        <h6 class="text-uppercase mt-0 float-left text-white-50">Users</h6>
                        <h4 class="mb-3 mt-0 float-right"><span class="badge badge-dark font-12">Today</span> {{$today_user}}</h4>
                    </div>
                </div>
                <div class="p-3">
                    <div class="float-right">
                        <a href="#" class="text-white-50"><i class="mdi mdi-buffer h5"></i></a>
                    </div>
                    <p class="font-14 m-0">This Month : {{$month_user}}</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-pink mini-stat">
                <div class="p-3 mini-stat-desc">
                    <div class="clearfix">
                        <h6 class="text-uppercase mt-0 float-left text-white-50">Deposit</h6>
                        <h4 class="mb-3 mt-0 float-right"><span class="badge badge-dark font-12">Today</span> {{$today_funding}}</h4>
                    </div>
                </div>
                <div class="p-3">
                    <div class="float-right">
                        <a href="#" class="text-white-50"><i class="mdi mdi-tag-text-outline h5"></i></a>
                    </div>
                    <p class="font-14 m-0">This Month : {{$month_funding}}</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-success mini-stat">
                <div class="p-3 mini-stat-desc">
                    <div class="clearfix">
                        <h6 class="text-uppercase mt-0 float-left text-white-50">Product Sold</h6>
                        <h4 class="mb-3 mt-0 float-right"><span class="badge badge-dark font-12">Today</span> {{$today_consume}}</h4>
                    </div>
                </div>
                <div class="p-3">
                    <div class="float-right">
                        <a href="#" class="text-white-50"><i class="mdi mdi-briefcase-check h5"></i></a>
                    </div>
                    <p class="font-14 m-0">This Month : {{$month_funding}}</p>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

{{--    <div class="row">--}}
{{--        <div class="col-xl-9">--}}
{{--            <div class="card">--}}
{{--                <div class="card-body">--}}
{{--                    <h4 class="mt-0 header-title">Sales Report</h4>--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-lg-8">--}}
{{--                            <div id="morris-line-example" class="morris-chart" style="height: 300px"></div>--}}
{{--                        </div>--}}
{{--                        <div class="col-lg-4">--}}
{{--                            <div>--}}
{{--                                <h5 class="font-14 mb-5">Yearly Sales Report</h5>--}}

{{--                                <div>--}}
{{--                                    <h5 class="mb-3">2018 : $19523</h5>--}}
{{--                                    <p class="text-muted mb-4">At vero eos et accusamus et iusto odio dignissimos ducimus atque</p>--}}
{{--                                    <a href="#" class="btn btn-primary btn-sm">Read more <i class="mdi mdi-chevron-right"></i></a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-xl-3">--}}
{{--            <div class="card">--}}
{{--                <div class="card-body">--}}
{{--                    <h4 class="mt-0 header-title">Sales Analytics</h4>--}}
{{--                    <div id="morris-donut-example" class="morris-chart" style="height: 300px"></div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    <!-- end row -->

    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-4">Latest FAQS</h4>
                    <div class="latest-massage">
                        @foreach($faqs as $faq)
                        <a href="#" class="latest-message-list">
                            <div class="border-bottom position-relative">
                                <div class="float-left user mr-3">
                                    <h5 class="bg-primary text-center rounded-circle text-dark mt-0">v</h5>
                                </div>
                                <div class="message-time">
                                    <p class="m-0 text-muted">{{\Carbon\Carbon::parse($faq->created_at)->toDateString()}}</p>
                                </div>
                                <div class="massage-desc">
                                    <h5 class="font-14 mt-0 text-dark">{{$faq->title}}</h5>
                                    <p class="text-muted">{{$faq->content}}</p>
                                </div>
                            </div>
                        </a>
                        @endforeach

                    </div>

                </div>
            </div>

        </div>
        <!-- end col -->

        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-4">Recent Activity</h4>
                    <ol class="activity-feed mb-0">

                        @foreach($audits as $audit)
                        <li class="feed-item">
                            <div class="feed-item-list">
                                <span class="date text-white-50">{{\Carbon\Carbon::parse($audit->created_at)->toFormattedDateString()}}</span>
                                <span class="activity-text">{{$audit->type}} {{$audit->action}}</span>
                            </div>
                        </li>
                        @endforeach
                    </ol>

                </div>
            </div>
        </div>
        <!-- end col -->

        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-4">Latest Users</h4>
                    <div class="latest-massage">
                        @foreach($users as $user)
                            <a href="#" class="latest-message-list">
                            <div class="border-bottom position-relative">
                                <div class="float-left user mr-3">
                                    <h5 class="bg-primary text-center rounded-circle text-dark mt-0">v</h5>
{{--                                    <img src="assets/images/users/avatar-3.jpg" alt="" class="rounded-circle mb-3">--}}
                                </div>
                                <div class="message-time">
                                    <p class="m-0 text-muted">{{\Carbon\Carbon::parse($user->created_at)->toTimeString()}}</p>
                                </div>
                                <div class="massage-desc">
                                    <h5 class="font-14 mt-0 text-dark">{{$user->first_name}} {{$user->last_name}}</h5>
                                    <p class="text-muted">{{$user->phoneno}}</p>
                                </div>
                            </div>
                        </a>
                        @endforeach

                    </div>

                </div>
            </div>

        </div>
        <!-- end col -->
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-xl-9">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-4">Latest Transaction</h4>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th scope="col">ID No.</th>
                                <th scope="col">Name</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Description</th>
                                <th scope="col">Status</th>
                                <th scope="col">Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($transactions as $tran)
                            <tr>
                                <th scope="row">#{{$tran->reference_id}}</th>
                                <td>{{$tran->user->first_name}} {{$tran->user->last_name}}</td>
                                <td>&#x20A6;{{number_format($tran->amount)}}</td>
                                <td>{{$tran->description}}</td>
                                @if($tran->status=="successful")
                                <td>
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                                @elseif($tran->status=="pending")
                                <td>
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 60%;" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                                @else
                                <td>
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 1%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                                @endif
                                <td>{{\Carbon\Carbon::parse($tran->created_at)->toFormattedDateString()}}</td>
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
                    <h4 class="mt-0 header-title mb-4">Document Verification</h4>
                    <table class="table table-striped mb-0">
                        <tbody>
                        @foreach($verifications as $ver)
                        <tr>
                            <td><i class="far fa-file-pdf text-primary h2"></i></td>
                            <td>
                                <h6 class="mt-0">{{\Carbon\Carbon::parse($ver->created_at)->toFormattedDateString()}}</h6>
                                <p class="text-muted mb-0">{{$ver->user->first_name}} {{$ver->user->first_name}}</p></td>
                            <td>
                                <a href="#" class="btn btn-primary btn-sm">
                                    <i class="fas fa-download"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
@stop

@section('after-scripts')
    <!--Morris Chart-->
    <script src="plugins/morris/morris.min.js"></script>
    <script src="plugins/raphael/raphael.min.js"></script>

    <!-- dashboard js -->
    <script src="assets/pages/dashboard.int.js"></script>
@stop

@section('before-styles')
    <!-- morris css -->
    <link rel="stylesheet" href="plugins/morris/morris.css">
@stop
