<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noarchive">
    <title> Dynamic Center | Landing Page </title>
    <!-- favicon -->
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <!-- bootstrap -->
    <link rel="stylesheet" href="assets1/css/bootstrap.min.css">
    <!-- icofont -->
    <link rel="stylesheet" href="assets1/css/fontawesome.5.7.2.css">
    <!-- flaticon -->
    <link rel="stylesheet" href="assets1/css/flaticon.css">
    <!-- animate.css -->
    <link rel="stylesheet" href="assets1/css/animate.css">
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="assets1/css/owl.carousel.min.css">
    <!-- magnific popup -->
    <link rel="stylesheet" href="assets1/css/magnific-popup.css">
    <!-- stylesheet -->
    <link rel="stylesheet" href="assets1/css/style.css">
    <!-- responsive -->
    <link rel="stylesheet" href="assets1/css/responsive.css">
</head>

<body>

<nav class="navbar navbar-area navbar-expand-lg nav-absolute white nav-style-01">
    <div class="container nav-container">
        <div class="responsive-mobile-menu">
            <div class="logo-wrapper">
                <a href="index.html" class="logo">
                    <img class="img img-thumbnail mt-2" src="images/logo.jpg" alt="logo">
                </a>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#appside_main_menu"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="appside_main_menu">
            <ul class="navbar-nav">
                <li class="current-menu-item">
                    <a href="#">Home</a>
                </li>
                <li><a href="#about">About</a></li>
                <li><a href="#pricing">Pricing</a></li>
                <li><a href="#contact">Contact</a></li>
                <li class="menu-item-has-children">
                    <a href="#">Download</a>
                    <ul class="sub-menu">
                        <li><a href="">Android</a></li>
                        <li><a href="">IOS</a></li>
                        <li><a href="">Window</a></li>
                        <li><a href="">Mac</a></li>
                    </ul>
                </li>
                @if(!\Illuminate\Support\Facades\Auth::user())
                    <li><a href="{{route('login')}}">Login</a></li>
                @endif
            </ul>
        </div>
        <div class="nav-right-content">
            <ul>
                <li class="button-wrapper">
                    @if(\Illuminate\Support\Facades\Auth::user())
                        <a href="{{route('dashboard')}}" class="boxed-btn btn-rounded">Dashboard</a>
                    @else
                        <a href="{{route('register')}}" class="boxed-btn btn-rounded">Register Now</a>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- header area start  -->
<header class="header-area header-bg dark-home-1" id="home">

    <div class="shape-1"><img src="assets1/img/shape/01.png" alt=""></div>
    <div class="shape-2"><img src="assets1/img/shape/02.png" alt=""></div>
    <div class="shape-3"><img src="assets1/img/shape/03.png" alt=""></div>
    <div class="shape-4"><img src="assets1/img/shape/05.png" alt=""></div>

    <div class="header-right-image">
        <img src="assets1/img/mobile-image-4.png" alt="header right image">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="header-inner">
                    <h1 class="title wow fadeInDown">Dynamic Center</h1>
                    <p>We keep you regularly Connected without hassle!</p>
                    <div class="btn-wrapper wow fadeInUp">
                        <a href="#" class="boxed-btn btn-rounded">Download now</a>
                        <a href="#" class="boxed-btn btn-rounded blank">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- header area end  -->

<!-- about us area start -->

<section class="about-us-area dark-bg">
    <div class="shape-1"><img src="assets1/img/shape/04.png" alt=""></div>
    <div class="shape-2"><img src="assets1/img/shape/05.png" alt=""></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="section-title white"><!-- section title -->
                    <span class="subtitle">About Dynamic Center</span>
                    <h3 class="title extra">Bulit for everybody with unlimited unique features</h3>
                    <p class="text-left mb-5">
                        Dynamic Center  is a Nigerian company duly registered with the Corporate Affairs Commission. <br/>

                        Dynamic Center is the best platform for Data, Airtime, TV subscriptions, Funds transfer in the best possible manner! <br/>

                        Do you wish to start data and airtime business online? Dynamic Center Website/App is offers the best possible price <br/>

                        Our platform is well structured with Timely Speed and Accuracy in Delivering superb  services. <br/>

                        Make Cool Cash Everyday As Airtime/Data Reseller in Nigeria <br/>
                    </p>

                    <p class="text-left mb-5">
                        Buy Airtime and Cheap Data online<br/>
                        Instant DSTV subscription, GOTV subscription and Star time subscription.<br/>
                        Get 2-6% discount on airtime recharge<br/>
                        Convert Airtime to Cash<br/>
                        Send Bulk SMS with Virtual top up service<br/>
                        Buy WAEC/NECO scratch card<br/>
                        Buy WAEC/NECO scratch card<br/>
                    </p>

                    <p class="text-left">Carry Out Transactions  Conveniently without hassle. Our product and services are available 24/7</p>
                </div><!-- //. section title -->
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="feature-area">
                    <ul class="feature-list white">
                        <li class="single-feature-list white">
                            <div class="icon icon-bg-1">
                                <i class="flaticon-vector"></i>
                            </div>
                            <div class="content">
                                <h4 class="title"><a href="#">Clean Design</a></h4>
                                <p>Consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolor  tempor incididunt</p>
                            </div>
                        </li>
                        <li class="single-feature-list white">
                            <div class="icon icon-bg-2">
                                <i class="flaticon-responsive"></i>
                            </div>
                            <div class="content">
                                <h4 class="title"><a href="#">Fully Respnosive</a></h4>
                                <p>Consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labor  tempor incididunt</p>
                            </div>
                        </li>
                        <li class="single-feature-list white">
                            <div class="icon icon-bg-3">
                                <i class="flaticon-layers-2"></i>
                            </div>
                            <div class="content">
                                <h4 class="title"><a href="#">Pixel Perfect</a></h4>
                                <p>Consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolor  tempor incididunt</p>
                            </div>
                        </li>
                        <li class="single-feature-list white">
                            <div class="icon icon-bg-4">
                                <i class="flaticon-picture"></i>
                            </div>
                            <div class="content">
                                <h4 class="title"><a href="#">Retina Ready</a></h4>
                                <p>Consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolor  tempor incididunt</p>
                            </div>
                        </li>
                    </ul>
                    <div class="btn-wrapper ">
                        <a href="#" class="boxed-btn btn-rounded gd-bg-1"><i class="flaticon-apple-1"></i> App Store</a>
                        <a href="#" class="boxed-btn btn-rounded gd-bg-2"><i class="flaticon-android-logo"></i> Play Store</a>
                        <a href="#" class="boxed-btn btn-rounded gd-bg-3"><i class="flaticon-windows"></i> Windows</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- about us area end -->

<!-- video area start -->
<section class="video-area dark-bg white" id="about">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="img-with-video">
                    <div class="img-wrap">
                        <img src="assets1/img/video-image.jpg" alt="">
                        <div class="hover">
                            <a href="https://www.youtube.com/watch?v=tdBzJRdy33M" class="video-play-btn mfp-iframe"><i class="fas fa-play"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="right-content-area ">
                    <span class="subtitle">aMazing experience</span>
                    <h3 class="title">Boost your business one step</h3>
                    <p>Innovative solutions with the best.  Incididunt dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolor  tempor incididunt ut labore et dolore </p>
                    <p>Built purse maids cease her ham new seven among and. Pulled coming wooded tended it answer remain me be. So landlord by we unlocked sensible it. Fat cannot use denied excuse son law. Wisdom happen suffer common the appear ham beauty her had. Or belonging zealously existence as by resources. </p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- video area end -->

<!-- counterup area start -->
<section class="counterup-area dark-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="single-counter-item white"><!-- single counter item -->
                    <div class="icon">
                        <i class="flaticon-rating"></i>
                    </div>
                    <div class="content">
                        <span class="count-num">14,567</span>
                        <h4 class="title">Positive Reviews</h4>
                    </div>
                </div><!-- //. single counter item -->
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="single-counter-item white"><!-- single counter item -->
                    <div class="icon">
                        <i class="flaticon-conversation-1"></i>
                    </div>
                    <div class="content">
                        <span class="count-num">567</span>
                        <h4 class="title">Good Comments</h4>
                    </div>
                </div><!-- //. single counter item -->
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="single-counter-item white"><!-- single counter item -->
                    <div class="icon">
                        <i class="flaticon-email"></i>
                    </div>
                    <div class="content">
                        <span class="count-num">36,778</span>
                        <h4 class="title">App Downloads</h4>
                    </div>
                </div><!-- //. single counter item -->
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="single-counter-item white"><!-- single counter item -->
                    <div class="icon">
                        <i class="flaticon-trophy"></i>
                    </div>
                    <div class="content">
                        <span class="count-num">30</span>
                        <h4 class="title">Best Awards</h4>
                    </div>
                </div><!-- //. single counter item -->
            </div>
        </div>
    </div>
</section>
<!-- counterup area end -->

<!-- why choose area start -->
<section class="why-choose-area dark-bg">
    <div class="shape-1"><img src="assets1/img/shape/05.png" alt=""></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="section-title white"><!-- section title -->
                    <span class="subtitle">why choose this</span>
                    <h3 class="title extra">Users love appside to make cool landing page</h3>
                    <p>Consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolor  tempor incididunt ut labore dolore magna.</p>
                </div><!-- //. section title -->
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-12">
                <div class="single-why-us-item white margin-top-60 fadeInUp wow"><!-- single why us item -->
                    <div class="icon gdbg-1">
                        <i class="flaticon-settings-1"></i>
                    </div>
                    <div class="content">
                        <h4 class="title">Easy Customize</h4>
                        <p>Consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore</p>
                    </div>
                </div><!-- //. single why us item -->
                <div class="single-why-us-item white fadeInUp wow"><!-- single why us item -->
                    <div class="icon gdbg-2">
                        <i class="flaticon-checked"></i>
                    </div>
                    <div class="content">
                        <h4 class="title">Fast & Secure</h4>
                        <p>Consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore</p>
                    </div>
                </div><!-- //. single why us item -->
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="center-image">
                    <img src="assets1/img/mobile-img-2.png" alt="mobile image two">
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="single-why-us-item white margin-top-60 fadeInUp wow"><!-- single why us item -->
                    <div class="icon gdbg-3">
                        <i class="flaticon-chat-1"></i>
                    </div>
                    <div class="content">
                        <h4 class="title">Live Chat</h4>
                        <p>Consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore</p>
                    </div>
                </div><!-- //. single why us item -->
                <div class="single-why-us-item white fadeInUp wow"><!-- single why us item -->
                    <div class="icon gdbg-4">
                        <i class="flaticon-cloud"></i>
                    </div>
                    <div class="content">
                        <h4 class="title">Secure Data</h4>
                        <p>Consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore</p>
                    </div>
                </div><!-- //. single why us item -->
            </div>
        </div>
    </div>
</section>
<!-- why choose area end -->

<!-- how it works area start -->
<section class="how-it-work-area dark-bg">
    <div class="shape-1"><img src="assets1/img/shape/06.png" alt=""></div>
    <div class="shape-2"><img src="assets1/img/shape/07.png" alt=""></div>
    <div class="shape-3"><img src="assets1/img/shape/06.png" alt=""></div>
    <div class="shape-4"><img src="assets1/img/shape/07.png" alt=""></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="section-title white"><!-- section title -->
                    <span class="subtitle">Working Process</span>
                    <h3 class="title">How it works?</h3>
                    <p>Consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolor  tempor incididunt ut labore dolore magna.</p>
                </div><!-- //. section title -->
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="how-it-work-tab-nav">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="account-tab" data-toggle="tab" href="#account" role="tab" aria-controls="account" aria-selected="true"><i class="flaticon-checked"></i> Log In Account <span class="number">1</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="settings-tab" data-toggle="tab" href="#settings" role="tab" aria-controls="settings" aria-selected="false"><i class="flaticon-settings-1"></i> Open Settings <span class="number">2</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="chat-tab" data-toggle="tab" href="#chat" role="tab" aria-controls="chat" aria-selected="false"><i class="flaticon-chat-1"></i> Start Your Chat <span class="number">3</span></a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content fadeInUp wow">
                    <div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
                        <div class="how-it-works-tab-content white">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="left-content-area">
                                        <h4 class="title">Login Account</h4>
                                        <p>Innovative solutions with the best.  Incididunt dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolor  tempor incididunt ut labore et dolore </p>
                                        <p>Adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolor  tempor incididunt ut labore et dolore
                                            Innovative solutions with the best.  Incididunt dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolor  tempor incididunt ut labore et dolore </p>
                                        <p>Adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolor  tempor incididunt ut labore et dolore
                                            Innovative solutions with the best.  Incididunt dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolor  tempor incididunt ut labore et dolore </p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="right-content-area">
                                        <div class="img-wrapper">
                                            <img src="assets1/img/how-it-works-image.png" alt="how it works image">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                        <div class="how-it-works-tab-content white">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="left-content-area">
                                        <h4 class="title">Login Account</h4>
                                        <p>Innovative solutions with the best.  Incididunt dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolor  tempor incididunt ut labore et dolore </p>
                                        <p>Adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolor  tempor incididunt ut labore et dolore
                                            Innovative solutions with the best.  Incididunt dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolor  tempor incididunt ut labore et dolore </p>
                                        <p>Adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolor  tempor incididunt ut labore et dolore
                                            Innovative solutions with the best.  Incididunt dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolor  tempor incididunt ut labore et dolore </p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="right-content-area">
                                        <div class="img-wrapper">
                                            <img src="assets1/img/how-it-works-image.png" alt="how it works image">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="chat" role="tabpanel" aria-labelledby="chat-tab">
                        <div class="how-it-works-tab-content white">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="left-content-area">
                                        <h4 class="title">Login Account</h4>
                                        <p>Innovative solutions with the best.  Incididunt dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolor  tempor incididunt ut labore et dolore </p>
                                        <p>Adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolor  tempor incididunt ut labore et dolore
                                            Innovative solutions with the best.  Incididunt dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolor  tempor incididunt ut labore et dolore </p>
                                        <p>Adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolor  tempor incididunt ut labore et dolore
                                            Innovative solutions with the best.  Incididunt dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolor  tempor incididunt ut labore et dolore </p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="right-content-area">
                                        <div class="img-wrapper">
                                            <img src="assets1/img/how-it-works-image.png" alt="how it works image">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- how it works area end -->

<!-- screenshort area start -->
<section class="screenshort-area dark-bg">
    <div class="shape-1"><img src="assets1/img/shape/06.png" alt=""></div>
    <div class="shape-2"><img src="assets1/img/shape/07.png" alt=""></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="section-title white"><!-- section title -->
                    <span class="subtitle">Screenshots</span>
                    <h3 class="title extra">Amazing visual interface</h3>
                    <p>Consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolor  tempor incididunt ut labore dolore magna.</p>
                </div><!-- //. section title -->
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="screenshort-carousel"><!-- screenshort carousel -->
                    <div class="single-screenshort-item"><!-- single screenshort item -->
                        <img src="assets1/img/screenshort/screen-1.jpg" alt="">
                    </div><!-- //.single screenshort item -->
                    <div class="single-screenshort-item"><!-- single screenshort item -->
                        <img src="assets1/img/screenshort/screen-2.jpg" alt="">
                    </div><!-- //.single screenshort item -->
                    <div class="single-screenshort-item"><!-- single screenshort item -->
                        <img src="assets1/img/screenshort/screen-3.jpg" alt="">
                    </div><!-- //.single screenshort item -->
                    <div class="single-screenshort-item"><!-- single screenshort item -->
                        <img src="assets1/img/screenshort/screen-4.jpg" alt="">
                    </div><!-- //.single screenshort item -->
                </div><!-- //. screenshort carousel -->
            </div>
        </div>
    </div>
</section>
<!-- screenshort area end -->

<!-- testimonial area start -->
<section class="testimonial-area dark-bg">
    <div class="shape-1"><img src="assets1/img/shape/06.png" alt=""></div>
    <div class="shape-2"><img src="assets1/img/shape/07.png" alt=""></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="section-title white"><!-- section title -->
                    <span class="subtitle">Testimonial</span>
                    <h3 class="title extra">What People Say</h3>
                    <p>Consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolor  tempor incididunt ut labore dolore magna.</p>
                </div><!-- //. section title -->
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="testimonial-carousel">

                    <div class="single-testimonial-item"><!-- single testimonial item -->
                        <img src="assets1/img/testimonial/01.png" alt="">
                        <div class="hover"><!-- hover -->
                            <div class="hover-inner">
                                <div class="icon"><i class="fas fa-quote-left"></i></div>
                                <p>They  provide innovative solutions with the best.  tempor incididunt utla bore et dolor  tempor incididunt .</p>
                                <div class="author-meta">
                                    <h4 class="name">Riley Cassidy</h4>
                                    <span class="post">Chief executive</span>
                                </div>
                            </div>
                        </div><!-- //. hover -->
                    </div><!-- //. single testimonial item -->
                    <div class="single-testimonial-item"><!-- single testimonial item -->
                        <img src="assets1/img/testimonial/02.png" alt="">
                        <div class="hover"><!-- hover -->
                            <div class="hover-inner">
                                <div class="icon"><i class="fas fa-quote-left"></i></div>
                                <p>They  provide innovative solutions with the best.  tempor incididunt utla bore et dolor  tempor incididunt .</p>
                                <div class="author-meta">
                                    <h4 class="name">Archie Tracey</h4>
                                    <span class="post">Technician</span>
                                </div>
                            </div>
                        </div><!-- //. hover -->
                    </div><!-- //. single testimonial item -->
                    <div class="single-testimonial-item"><!-- single testimonial item -->
                        <img src="assets1/img/testimonial/03.png" alt="">
                        <div class="hover"><!-- hover -->
                            <div class="hover-inner">
                                <div class="icon"><i class="fas fa-quote-left"></i></div>
                                <p>They  provide innovative solutions with the best.  tempor incididunt utla bore et dolor  tempor incididunt .</p>
                                <div class="author-meta">
                                    <h4 class="name">Brodie Hopley</h4>
                                    <span class="post">Chief Elevator</span>
                                </div>
                            </div>
                        </div><!-- //. hover -->
                    </div><!-- //. single testimonial item -->

                </div>
            </div>
        </div>
    </div>
</section>
<!-- testimonial area end -->

<!-- price plan area start -->
<section class="pricing-plan-area dark-bg" id="pricing">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="section-title white"><!-- section title -->
                    <span class="subtitle">Pricing plans</span>
                    <h3 class="title extra">Choose your pricing</h3>
                    <p>Our product Pricing model is unique! <br />
                        We offer the best possible price in Nigeria</p>
                </div><!-- //. section title -->
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="single-price-plan-01 white slideInUp wow"><!-- single price plan one -->
                    <div class="price-header">
                        <h4 class="name">Primary Plan</h4>
                        <div class="price-wrap">
                            <span class="price">$250</span>
                            <span class="month">/Mo</span>
                        </div>
                    </div>
                    <div class="price-body">
                        <ul>
                            <li>5 Analyzer</li>
                            <li>3 Month Support</li>
                            <li>10 Sessions</li>
                            <li>No Risk Garrunty</li>
                        </ul>
                    </div>
                    <div class="price-footer">
                        <a href="#" class="boxed-btn btn-rounded gd-bg-2">Get Started</a>
                    </div>
                </div><!-- //. single price plan one -->
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="single-price-plan-01 white slideInUp wow"><!-- single price plan one -->
                    <div class="price-header">
                        <h4 class="name">Basic Plan</h4>
                        <div class="price-wrap">
                            <span class="price">$350</span>
                            <span class="month">/Mo</span>
                        </div>
                    </div>
                    <div class="price-body">
                        <ul>
                            <li>5 Analyzer</li>
                            <li>3 Month Support</li>
                            <li>10 Sessions</li>
                            <li>No Risk Garrunty</li>
                        </ul>
                    </div>
                    <div class="price-footer">
                        <a href="#" class="boxed-btn btn-rounded gd-bg-2">Get Started</a>
                    </div>
                </div><!-- //. single price plan one -->
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="single-price-plan-01 white slideInUp wow"><!-- single price plan one -->
                    <div class="price-header">
                        <h4 class="name">Advance Plan</h4>
                        <div class="price-wrap">
                            <span class="price">$150</span>
                            <span class="month">/Mo</span>
                        </div>
                    </div>
                    <div class="price-body">
                        <ul>
                            <li>5 Analyzer</li>
                            <li>3 Month Support</li>
                            <li>10 Sessions</li>
                            <li>No Risk Garrunty</li>
                        </ul>
                    </div>
                    <div class="price-footer">
                        <a href="#" class="boxed-btn btn-rounded gd-bg-2">Get Started</a>
                    </div>
                </div><!-- //. single price plan one -->
            </div>
        </div>
    </div>
</section>
<!-- price plan area end -->

<!-- footer area start -->
<footer class="footer-area">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget about_widget">
                        <a href="index.html" class="footer-logo"><img class="img img-thumbnail" src="assets/images/logo_small.png" alt="logo"></a>
                        <p>Within coming figure sex things are. Pretended concluded did repulsive education smallness yet yet described. Had country man his pressed shewing. </p>
                        <ul class="social-icon">
                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                            <li><a href="#"><i class="fab fa-pinterest-p"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget nav_menus_widget">
                        <h4 class="widget-title">Useful Links</h4>
                        <ul>
                            <li><a href="index.html"><i class="fas fa-chevron-right"></i> Home</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> About Us</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> Service</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> Blog</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> Contact</a></li>
                            @if (Route::has('login'))
                                @auth
                                    <li><a href="{{ url('/home') }}"><i class="fas fa-chevron-right"></i> Home</a></li>
                                @else
                                    <li><a href="{{ route('login') }}"><i class="fas fa-chevron-right"></i> Login</a></li>

                                    @if (Route::has('register'))
                                        <li><a href="{{ route('register') }}"><i class="fas fa-chevron-right"></i> Register</a></li>
                                    @endif
                                @endauth
                            @endif

                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget nav_menus_widget">
                        <h4 class="widget-title">Need Help?</h4>
                        <ul>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> Faqs</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> Privacy</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> Policy</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> Support</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> Temrs</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget nav_menus_widget">
                        <h4 class="widget-title">Download</h4>
                        <ul>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> For IOS</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> For Android</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> For Mac</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> For Window</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> For Linax</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-area"><!-- copyright area -->
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="copyright-inner"><!-- copyright inner wrapper -->
                        <div class="left-content-area"><!-- left content area -->
                            &copy; Copyrights 2019 Dynamic Center All rights reserved.
                        </div><!-- //. left content aera -->
                        <div class="right-content-area"><!-- right content area -->
                            Designed by <strong>5Star Company</strong>
                        </div><!-- //. right content area -->
                    </div><!-- //.copyright inner wrapper -->
                </div>
            </div>
        </div>
    </div><!-- //. copyright area -->
</footer>
<!-- footer area end -->

<!-- preloader area start -->
<div class="preloader-wrapper" id="preloader">
    <div class="preloader" >
        <div class="sk-circle">
            <div class="sk-circle1 sk-child"></div>
            <div class="sk-circle2 sk-child"></div>
            <div class="sk-circle3 sk-child"></div>
            <div class="sk-circle4 sk-child"></div>
            <div class="sk-circle5 sk-child"></div>
            <div class="sk-circle6 sk-child"></div>
            <div class="sk-circle7 sk-child"></div>
            <div class="sk-circle8 sk-child"></div>
            <div class="sk-circle9 sk-child"></div>
            <div class="sk-circle10 sk-child"></div>
            <div class="sk-circle11 sk-child"></div>
            <div class="sk-circle12 sk-child"></div>
        </div>
    </div>
</div>

<!-- preloader area end -->

<!-- back to top area start -->
<div class="back-to-top">
    <i class="fas fa-angle-up"></i>
</div>
<!-- back to top area end -->

<!-- jquery -->
<script src="assets1/js/jquery.js"></script>
<!-- popper -->
<script src="assets1/js/popper.min.js"></script>
<!-- bootstrap -->
<script src="assets1/js/bootstrap.min.js"></script>
<!-- owl carousel -->
<script src="assets1/js/owl.carousel.min.js"></script>
<!-- magnific popup -->
<script src="assets1/js/jquery.magnific-popup.js"></script>
<!-- contact js-->
<script src="assets1/js/contact.js"></script>
<!-- wow js-->
<script src="assets1/js/wow.min.js"></script>
<!-- way points js-->
<script src="assets1/js/waypoints.min.js"></script>
<!-- counterup js-->
<script src="assets1/js/jquery.counterup.min.js"></script>
<!-- main -->
<script src="assets1/js/main.js"></script>
</body>

</html>
