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
                        <h5 class="font-14 text-muted mb-4">{{ __('Confirm Password') }} for {{Auth::user()->first_name}} {{Auth::user()->last_name}}</h5>
                        <p class="text-muted mb-4">{{ __('Please confirm your password before continuing.') }}</p>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="p-2">
                                <div class="text-center">
                                    <a href="index.html" class="logo logo-admin"><img src="assets/images/logo.png" height="28" alt="logo"></a>
                                </div>
                            </div>

                            <div class="p-2">
                                <form class="form-horizontal m-t-20"  method="POST" action="{{ route('password.confirm') }}">
                                        @csrf

                                    <div class="user-thumb text-center m-b-30">
                                        <img src="assets/images/users/avatar-4.jpg" class="rounded-circle img-thumbnail mx-auto d-block" alt="thumbnail">
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input id="password" type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group text-center row m-t-20">
                                        <div class="col-12">
                                            <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">{{ __('Confirm Password') }}</button>
                                        </div>
                                    </div>

                                    <div class="form-group m-t-10 mb-0 row">
                                        <div class="col-12 m-t-20 text-center">
                                            <a href="/home" class="text-muted">Return Home</a>
                                        </div>

                                        <div class="col-12 m-t-20 text-center">
                                            <a class="btn btn-link" href="/forgot-password" style="color: red">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
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
