@extends('layouts.myapp')

@section('content')
    <div class="account-pages">


        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div>
                        <div >
                            <a href="#" class="logo logo-admin"><img src="assets/images/logo.png" height="28" alt="logo"></a>
                        </div>
                        <h5 class="font-14 text-muted mb-4">WELCOME TO DYNAMIC CENTRE
                            <br/>
                            <span class="text-muted mb-4">...business is life</span>
                        </h5>
                        <p class="font-15 text-muted mb-4">We offers fully automated and fast services with realtime notification feature. With our sleek interface users can carry out transactions effectively and efficiently without unnecessary distractions or complexities.</p>

                        <h5 class="font-14 text-muted mb-4">How To Get Started :</h5>
                        <div>
                            <p><i class="mdi mdi-arrow-right text-primary mr-2"></i>Create an account</p>
                            <p><i class="mdi mdi-arrow-right text-primary mr-2"></i>Subscribe to plan of your choice</p>
                            <p><i class="mdi mdi-arrow-right text-primary mr-2"></i>Get listed on our platform</p>
                            <p><i class="mdi mdi-arrow-right text-primary mr-2"></i>Enjoy Secured and Fast Transactions</p>
                            <p><i class="mdi mdi-arrow-right text-primary mr-2"></i>Manage Users & Transactions easily</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="p-2">
                                <h4 class="text-muted float-right font-18 mt-4">Register</h4>
                                <div>
                                    <a href="index.html" class="logo logo-admin"><img src="assets/images/logo.png" height="28" alt="logo"></a>
                                </div>
                            </div>

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

                            @error('processing')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="p-2">
                                <form class="form-horizontal m-t-20" method="POST" action="{{ route('register') }}">
                                    @csrf

                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" type="email" placeholder="Personal Email Address">
                                        </div>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input class="form-control @error('phoneno') is-invalid @enderror" name="phoneno" value="{{ old('phoneno') }}" required autocomplete="phone" type="tel" placeholder="Personal Phone number" min="11" max="11">
                                        </div>
                                        @error('phoneno')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required  type="text" placeholder="First Name">
                                        </div>
                                        @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required  type="text" placeholder="Last Name">
                                        </div>
                                        @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input class="form-control @error('company_name') is-invalid @enderror" name="company_name" value="{{ old('company_name') }}" required autocomplete="company name" type="text" placeholder="Company Name">
                                        </div>
                                        @error('company_name')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input class="form-control @error('company_email') is-invalid @enderror" name="company_email" value="{{ old('company_email') }}" required autocomplete="company email" type="email" placeholder="Company Email">
                                        </div>
                                        @error('company_email')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input class="form-control @error('bank_account') is-invalid @enderror" name="bank_account" value="{{ old('bank_account') }}" required autocomplete="bank_account" type="text" placeholder="Company Account Number" min="10" max="10" maxlength="10">
                                        </div>
                                        @error('bank_account')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>


                                    <div class="form-group">
                                        <div>
                                            <select class="form-control" name="bank_code">
                                                <option value="">Select Company Bank Account</option>
                                                @foreach($banks as $bank)
                                                    <option value="{{$bank['code']}}">{{$bank['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input class="form-control @error('bvn') is-invalid @enderror" name="bvn" value="{{ old('bvn') }}" required autocomplete="bvn" type="text" placeholder="Enter bvn" min="11" max="11" maxlength="11">
                                        </div>
                                        @error('bvn')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" type="password" placeholder="Password">
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input class="form-control" type="password" name="password_confirmation" autocomplete="new-password" required="" placeholder="Confirm Password">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                <label class="custom-control-label font-weight-normal" for="customCheck1">I accept <a href="#" class="text-primary">Terms and Conditions</a></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group text-center row m-t-20">
                                        <div class="col-12">
                                            <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">{{ __('Register') }}</button>
                                        </div>
                                    </div>

                                    <div class="form-group m-t-10 mb-0 row">
                                        <div class="col-12 m-t-20 text-center">
                                            <a href="{{ route('login') }}" class="text-muted">Already have account?</a>
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
