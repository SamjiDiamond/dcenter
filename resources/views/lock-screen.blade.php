<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dynamic Center Lockscreen</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Facebook Opengraph integration: https://developers.facebook.com/docs/sharing/opengraph -->
  <meta property="og:title" content="">
  <meta property="og:image" content="">
  <meta property="og:url" content="">
  <meta property="og:site_name" content="">
  <meta property="og:description" content="">

  <!-- Twitter Cards integration: https://dev.twitter.com/cards/  -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:site" content="">
  <meta name="twitter:title" content="">
  <meta name="twitter:description" content="">
  <meta name="twitter:image" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Raleway:400,500,700|Roboto:400,900" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/lock-screen-style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Bell
  * Updated: Jul 27 2023 with Bootstrap v5.3.1
  * Template URL: https://bootstrapmade.com/bell-free-bootstrap-4-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="hero">
    <div class="container text-center">
      <div class="row">
        <div class="col-md-12">
          <a class="hero-brand" href="/" title="Home"><img alt="dynamic center Logo" src="assets/images/logo_small.png"></a>
        </div>
      </div>

      <div class="col-md-6 mx-auto m-1">
        <h1>
         Dynamic Center
        </h1>

        @if(session('error'))
        <p class="text-danger">{{session('error')}}</p>
        @endif
       
        <form action="{{route('lock.screen.login')}}" method="post">
          @csrf
          <div class="form-group m-1">
            <input type="password" class="form-control text-center p-4" style="border-radius: 20px" name="password" placeholder="Enter your password to continue"/>
            @error('password')
              <div class="text-danger">{{ $message }}</div>
             @enderror
          </div>
          <div class="form-group">
            <input type="submit" class="btn btn-full" value="Login" />
          </div>
        </form>
       
      </div>
    </div>

  </section><!-- End Hero -->


  <!-- ======= Footer ======= -->
  <footer class="site-footer">
    <div class="bottom">
      <div class="container">
          <div class="">
            <p class="text-center" style="text-align: center">
               All Rights Reserved Copyright &copy; .<a href="/"><strong>Dynamic Center</strong>.</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>