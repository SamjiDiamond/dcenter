@extends('layouts.myapp')

@section('content')
    <div class="account-pages">

        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div>
                        <div >
                            <a href="index.html" class="logo logo-admin"><img src="assets/images/logo.png" height="28" alt="logo"></a>
                        </div>
                        <h5 class="font-14 text-muted mb-4">WELCOME TO DYNAMIC CENTER</h5>
                        <p class="text-muted mb-4">A home for data, airtime, tv subscription and a lot. You can easily manage your customers from here.</p>

                        <h5 class="font-14 text-muted mb-4">Terms :</h5>
                        <div>
                            <p><i class="mdi mdi-arrow-right text-primary mr-2"></i>At solmen va esser necessi far uniform paroles.</p>
                            <p><i class="mdi mdi-arrow-right text-primary mr-2"></i>Donec sapien ut libero venenatis faucibus.</p>
                            <p><i class="mdi mdi-arrow-right text-primary mr-2"></i>Nemo enim ipsam voluptatem quia voluptas sit .</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="p-2">
                                <h4 class="text-muted float-right font-18 mt-4">Sign In</h4>
                                <div>
                                    <a href="index.html" class="logo logo-admin"><img src="assets/images/logo.png" height="28" alt="logo"></a>
                                </div>
                            </div>

                            <div class="p-2">
                                <form class="form-horizontal m-t-20" method="POST" action="{{ route('login') }}">
                                    @csrf

                                    @error('email')
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <strong>Warning! {{ $message }} </strong>
                                    </div>
                                    @enderror

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus type="email" placeholder="Email Address">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" type="password" required="" placeholder="Password" >
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="remember">{{ __('Remember Me') }}</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group text-center row m-t-20">
                                        <div class="col-12">
                                            <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">Log In</button>
                                        </div>
                                    </div>

                                    <div class="form-group m-t-10 mb-0 row">
                                        @if (Route::has('password.request'))
                                            <div class="col-sm-7 m-t-20">
                                                <a href="{{ route('password.request') }}" class="text-muted"><i class="mdi mdi-lock"></i> {{ __('Forgot Your Password?') }}</a>
                                            </div>
                                        @endif

                                        <div class="col-sm-5 m-t-20">
                                            <a href="{{ route('register') }}" class="text-muted"><i class="mdi mdi-account-circle"></i> Create an account</a>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
    </div>
@endsection
