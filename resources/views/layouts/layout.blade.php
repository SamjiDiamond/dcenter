<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Dynamic Center - Admin Dashboard</title>
    <meta content="Dynamic Center Admin Dashboard" name="description"/>
    <meta content="Samji Diamond @ 5Star Company" name="author"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    <!-- App Icons -->
    <link rel="shortcut icon" href="/assets/images/favicon.ico">

@yield('before-styles')
<!-- App css -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/css/icons.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/css/style.css" rel="stylesheet" type="text/css"/>

    @yield('after-styles')

</head>


<body>

<!-- Loader -->
<div id="preloader">
    <div id="status">
        <div class="spinner">
            <div class="rect1"></div>
            <div class="rect2"></div>
            <div class="rect3"></div>
            <div class="rect4"></div>
            <div class="rect5"></div>
        </div>
    </div>
</div>

<div class="header-bg">
    <!-- Navigation Bar-->
@include('layouts.mynav')
    <!-- End Navigation Bar-->

</div>
<!-- header-bg -->

<div class="wrapper">
    <div class="container-fluid">

        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row align-items-center">

                        <div class="col-md-8">
                            <h4 class="page-title m-0">@yield('title')</h4>
                        </div>
                        <div class="col-md-4">
                            <div class="float-right d-none d-md-block">
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ti-settings mr-1"></i> Settings
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated">
                                        <a class="dropdown-item" href="#">SMS</a>
                                        <a class="dropdown-item" href="#">Email</a>
                                        <a class="dropdown-item" href="#">System</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#">Edit SMS</a>
                                        <a class="dropdown-item" href="#">Edit Email</a>
                                        <a class="dropdown-item" href="#">Edit System</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title end breadcrumb -->
            <div class="p-1">
                            @if(session()->has('message'))

                                <div class="alert alert-success">

                                    <button type="button" class="close" data-dismiss="alert">x</button>
                                    <div style="text-align: center">{{session()->get('message') }}</div>
                                </div>
                            @endif
            </div>
            @yield('content')
         

    </div> <!-- end container-fluid -->
</div>
<!-- end wrapper -->

<!-- Footer -->
<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                © 2020 Dynamic Center <span class="d-none d-md-inline-block"> - Crafted with <i class="mdi mdi-heart text-danger"></i> by 5Star Company.</span>
            </div>
        </div>
    </div>
</footer>
<!-- End Footer -->


<!-- jQuery  -->
@yield('before-scripts')
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/modernizr.min.js"></script>
<script src="/assets/js/waves.js"></script>
<script src="/assets/js/jquery.slimscroll.js"></script>

@yield('after-scripts')
<!-- App js -->
<script src="/assets/js/app.js"></script>

</body>
</html>
