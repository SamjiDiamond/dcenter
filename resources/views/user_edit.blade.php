@extends('layouts.layouts')

@section('title',$user->last_name." ".$user->first_name)
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

                    <h4 class="mt-0 header-title">Edit User Information</h4>
                    <p class="text-muted m-b-30 font-14">Modify user details <code
                            class="highlighter-rouge">carefully</code>.</p>

                    <form method="POST" action="/user-update/{{$user->id}}">
                        @csrf
                    <div class="form-group row">
                        <label for="example-text-input" class="col-sm-2 col-form-label">Last Name</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" value="{{$user->last_name}}" id="last_name" name="last_name">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="example-text-input" class="col-sm-2 col-form-label">First Name</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" value="{{$user->first_name}}" id="first_name" name="first_name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-search-input" class="col-sm-2 col-form-label">Address</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="search" value="{{$user->address}}" id="address" name="address">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-email-input" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="email" value="{{$user->email}}" id="email" name="email">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-tel-input" class="col-sm-2 col-form-label">Telephone</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="tel" value="{{$user->phoneno}}" id="phoneno" name="phoneno">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-date-input" class="col-sm-2 col-form-label">Date of Birth</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="date" value="{{$user->dob}}" id="dob" name="dob">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Gender</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="gender">

                                @if($user->gender=="male")
                                    <option value="male" selected>male</option>
                                    <option value="female">female</option>
                                @elseif($user->gender=="female")
                                    <option value="female" selected>female</option>
                                    <option value="male">male</option>
                                @else
                                    <option selected>Select gender</option>
                                    <option value="male">male</option>
                                    <option value="female">female</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Email Notification</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="notification_email">
                                @if($user->notification_email == "true")
                                    <option value="true" selected>true</option>
                                    <option value="false">false</option>
                                @else
                                    <option value="false" selected>false</option>
                                    <option value="true">true</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">SMS Notification</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="notification_sms">
                                @if($user->notification_sms == "true")
                                    <option value="true" selected>true</option>
                                    <option value="false">false</option>
                                @else
                                    <option value="false" selected>false</option>
                                    <option value="true">true</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="form-group m-b-0">
                        <div>
                            @can('user-edit')
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    Update
                                </button>
                            @endcan
                            <button type="reset" class="btn btn-secondary waves-effect m-l-5">
                                Cancel
                            </button>
                        </div>
                    </div>

                    </form>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@stop

@section('after-scripts')
@stop

@section('before-styles')
<!-- App Icons -->
<link rel="shortcut icon" href="assets/images/favicon.ico">

@stop
