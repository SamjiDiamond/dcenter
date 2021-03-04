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

    <div class="row">
        <div class="col-xl-9">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Sales Report</h4>
                    <div class="row">
                        <div class="col-lg-8">
                            <div id="morris-line-example" class="morris-chart" style="height: 300px"></div>
                        </div>
                        <div class="col-lg-4">
                            <div>
                                <h5 class="font-14 mb-5">Yearly Sales Report</h5>

                                <div>
                                    <h5 class="mb-3">2018 : $19523</h5>
                                    <p class="text-muted mb-4">At vero eos et accusamus et iusto odio dignissimos ducimus atque</p>
                                    <a href="#" class="btn btn-primary btn-sm">Read more <i class="mdi mdi-chevron-right"></i></a>
                                </div>
                            </div>
                        </div>
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

    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-4">Latest SMS Log</h4>
                    <div class="latest-massage">
                        <a href="#" class="latest-message-list">
                            <div class="border-bottom position-relative">
                                <div class="float-left user mr-3">
                                    <h5 class="bg-primary text-center rounded-circle text-dark mt-0">v</h5>
                                </div>
                                <div class="message-time">
                                    <p class="m-0 text-muted">Just Now</p>
                                </div>
                                <div class="massage-desc">
                                    <h5 class="font-14 mt-0 text-dark">Victor Zamora</h5>
                                    <p class="text-muted">Hey! there I'm available...</p>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="latest-message-list">
                            <div class="border-bottom mt-3 position-relative">
                                <div class="float-left user mr-3">
                                    <h5 class="bg-success text-center rounded-circle text-dark mt-0">p</h5>
                                </div>
                                <div class="message-time">
                                    <p class="m-0 text-muted">2 Min. ago</p>
                                </div>
                                <div class="massage-desc">
                                    <h5 class="font-14 mt-0 text-dark">Patrick Beeler</h5>
                                    <p class="text-muted">I've finished it! See you so...</p>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="latest-message-list">
                            <div class="border-bottom mt-3 position-relative">
                                <div class="float-left user mr-3">
                                    <img src="assets/images/users/avatar-3.jpg" alt="" class="rounded-circle mb-3">
                                </div>
                                <div class="message-time">
                                    <p class="m-0 text-muted">6 Min. ago</p>
                                </div>
                                <div class="massage-desc">
                                    <h5 class="font-14 mt-0 text-dark">Ralph Ramirez</h5>
                                    <p class="text-muted">This theme is awesome!</p>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="latest-message-list">
                            <div class="border-bottom mt-3 position-relative">
                                <div class="float-left user mr-3">
                                    <h5 class="bg-pink text-center rounded-circle text-dark mt-0">b</h5>
                                </div>
                                <div class="message-time">
                                    <p class="m-0 text-muted">01:34 pm</p>
                                </div>
                                <div class="massage-desc">
                                    <h5 class="font-14 mt-0 text-dark">Bryan Lacy</h5>
                                    <p class="text-muted">I've finished it! See you so...</p>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="latest-message-list">
                            <div class="mt-3 position-relative">
                                <div class="float-left user mr-3">
                                    <img src="assets/images/users/avatar-4.jpg" alt="" class="rounded-circle mb-3">
                                </div>
                                <div class="message-time">
                                    <p class="m-0 text-muted">02:05 pm</p>
                                </div>
                                <div class="massage-desc">
                                    <h5 class="font-14 mt-0 text-dark">James Sorrells</h5>
                                    <p class="text-muted">Hey! there I'm available...</p>
                                </div>
                            </div>
                        </a>
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
                        <li class="feed-item">
                            <div class="feed-item-list">
                                <span class="date text-white-50">Jan 10</span>
                                <span class="activity-text">Responded to need “Volunteer Activities”</span>
                            </div>
                        </li>
                        <li class="feed-item">
                            <div class="feed-item-list">
                                <span class="date text-white-50">Jan 09</span>
                                <span class="activity-text">Added an interest “Volunteer Activities”</span>
                            </div>
                        </li>
                        <li class="feed-item">
                            <div class="feed-item-list">
                                <span class="date text-white-50">Jan 08</span>
                                <span class="activity-text">Joined the group “Boardsmanship Forum”</span>
                            </div>
                        </li>
                        <li class="feed-item">
                            <div class="feed-item-list">
                                <span class="date text-white-50">Jan 07</span>
                                <span class="activity-text">Responded to need “In-Kind Opportunity”</span>
                            </div>
                        </li>
                    </ol>

                </div>
            </div>
        </div>
        <!-- end col -->

        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-4">Social Source</h4>
                    <div class="text-center">
                        <div class="social-source-icon lg-icon mb-3">
                            <i class="mdi mdi-facebook h2 bg-primary"></i>
                        </div>
                        <h5 class="font-16"><a href="#" class="text-dark">Facebook - <span class="text-muted">125 sales</span> </a></h5>
                        <p class="text-muted">Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis tincidunt.</p>
                        <a href="#" class="text-primary font-14">Learn more <i class="mdi mdi-chevron-right"></i></a>
                    </div>
                    <div class="row mt-5">
                        <div class="col-md-4">
                            <div class="social-source text-center mt-3">
                                <div class="social-source-icon mb-2">
                                    <i class="mdi mdi-facebook h5 bg-primary"></i>
                                </div>
                                <p class="font-14 text-muted mb-2">125 sales</p>
                                <h6>Facebook</h6>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="social-source text-center mt-3">
                                <div class="social-source-icon mb-2">
                                    <i class="mdi mdi-twitter h5 bg-info"></i>
                                </div>
                                <p class="font-14 text-muted mb-2">112 sales</p>
                                <h6>Twitter</h6>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="social-source text-center mt-3">
                                <div class="social-source-icon mb-2">
                                    <i class="mdi mdi-instagram h5 bg-pink"></i>
                                </div>
                                <p class="font-14 text-muted mb-2">104 sales</p>
                                <h6>Instagram</h6>

                            </div>
                        </div>
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
