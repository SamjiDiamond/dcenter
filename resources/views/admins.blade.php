@extends('layouts.layout')

@section('title','Admins')
@section('content')
    <div class="row">
        <div class="col-xl-12">
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

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>Success! {{ session('success') }} </strong>
                        </div>
                @endif

                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <li>{{ $error }}</li>
                            </div>
                    @endforeach

                <!-- Nav tabs -->
                    <ul class="nav nav-pills nav-justified" role="tablist">
                        @if ($edit ?? '')
                            <li class="nav-item waves-effect waves-light">
                                <a class="nav-link active" data-toggle="tab" href="#edit-1" role="tab">
                                    <span class="d-none d-md-block">Edit Admin</span><span class="d-block d-md-none"><i class="mdi mdi-home-variant h5"></i></span>
                                </a>
                            </li>
                            <li class="nav-item waves-effect waves-light">
                                <a class="nav-link" data-toggle="tab" href="#home-1" role="tab">
                                    <span class="d-none d-md-block">Admins</span><span class="d-block d-md-none"><i class="mdi mdi-home-variant h5"></i></span>
                                </a>
                            </li>
                        @else
                            <li class="nav-item waves-effect waves-light">
                                <a class="nav-link active" data-toggle="tab" href="#home-1" role="tab">
                                    <span class="d-none d-md-block">Admins</span><span class="d-block d-md-none"><i class="mdi mdi-home-variant h5"></i></span>
                                </a>
                            </li>

                        @endif

                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" data-toggle="tab" href="#profile-1" role="tab">
                                <span class="d-none d-md-block">Create Admin</span><span class="d-block d-md-none"><i class="mdi mdi-account h5"></i></span>
                            </a>
                        </li>

                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        @if ($edit ?? '')
                            <div class="tab-pane p-3" id="home-1" role="tabpanel">
                                @else
                                    <div class="tab-pane active p-3" id="home-1" role="tabpanel">
                                        @endif
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card m-b-30">
                                                    <div class="card-body">

                                                        <h4 class="mt-0 header-title">Admins</h4>
                                                        <p class="text-muted m-b-30 font-14">Manage Admins</p>

                                                        <div class="table-responsive">
                                                            <table class="table table-striped mb-0">
                                                                <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Name</th>
                                                                    <th>Email</th>
                                                                    <th>Phone no</th>
                                                                    <th>Role</th>
                                                                    <th>Company</th>
                                                                    <th>Status</th>
                                                                    <th>Created at</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($users as $user)
                                                                <tr>
                                                                    <th scope="row">{{$i++}}</th>
                                                                    <td>{{$user->last_name . " " . $user->first_name}}</td>
                                                                    <td>{{$user->email}}</td>
                                                                    <td>{{$user->phoneno}}</td>
                                                                    <td>{{$user->role}}</td>
                                                                    <td>{{$user->company}}</td>
                                                                    <td>{{$user->status}}</td>
                                                                    <td>{{$user->created_at}}</td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-sm{{ $user->id }}" style="margin: 5px"><i class="fab fa-wpexplorer"></i> View</button>
                                                                        @can('admin-edit')
                                                                        <a href="/admin-edit/{{ $user->id }}" type="button" class="btn btn-info waves-effect waves-light" style="margin: 5px"><i class="fas fas fa-user-edit"></i> Edit</a>
                                                                        @endcan
                                                                        @can('admin-disable')
                                                                            @if($user->status == "active")
                                                                                <a href="/admin-disable/{{ $user->id }}" type="button" class="btn btn-outline-warning waves-effect waves-light" style="margin: 5px"><i class="fas fa-user-alt-slash"></i>Disable</a>
                                                                            @else
                                                                                <a href="/admin-enable/{{ $user->id }}" type="button" class="btn btn-outline-warning waves-effect waves-light" style="margin: 5px"><i class="fas fa-user-alt-slash"></i>Enable</a>
                                                                            @endif
                                                                        @endcan
                                                                    {{--  <button type="button" class="btn btn-outline-danger waves-effect waves-light"><i class="fas fa-trash"></i>Delete</button></td>--}}
                                                                </tr>

                                                                    <div class="col-sm-6 col-md-3 m-t-30">
                                                                        <div class="modal fade bs-example-modal-sm{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                                            <div class="modal-dialog modal-sm">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title mt-0"
                                                                                            id="mySmallModalLabel">{{ ucfirst($user->first_name) }}
                                                                                            Details</h5>
                                                                                        <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                            <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <p>Last
                                                                                            Name: {{ $user->last_name }}</p>
                                                                                        <p>First
                                                                                            Name: {{ $user->first_name }}</p>
                                                                                        <p>Email: {{$user->email}}</p>
                                                                                        <p>Phone
                                                                                            Number: {{$user->phoneno}}</p>
                                                                                        <p>
                                                                                            Status: {{ $user->status }}</p>
                                                                                        <p>
                                                                                            Gender: {{ $user->gender }}</p>
                                                                                        <p>
                                                                                            Address: {{ $user->address }}</p>

                                                                                    </div>
                                                                                </div><!-- /.modal-content -->
                                                                            </div><!-- /.modal-dialog -->
                                                                        </div><!-- /.modal -->
                                                                    </div>

                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div> <!-- end col -->
                                        </div> <!-- end row -->
                                    </div>
                                    <div class="tab-pane p-3" id="profile-1" role="tabpanel">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card m-b-30">
                                                    <div class="card-body">

                                                        <h4 class="mt-0 header-title">Create Admin</h4>
                                                        <p class="text-muted m-b-30">Add new admin and attach a role</p>

                                                        <form method="POST" action="admin-create">
                                                            @csrf

                                                            <div class="form-group">
                                                                <label class="col-sm-2 col-form-label">Last Name</label>
                                                                <div>
                                                                    <input type="text" class="form-control" required
                                                                           data-parsley-minlength="2" placeholder="Enter admin last name" name="last_name"/>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-sm-2 col-form-label">First Name</label>
                                                                <div>
                                                                    <input type="text" class="form-control" required
                                                                           data-parsley-minlength="2" placeholder="Enter admin first name" name="first_name"/>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="example-email-input" class="col-sm-2 col-form-label">Email</label>
                                                                <div>
                                                                    <input class="form-control" type="email" id="example-email-input" placeholder="admin@email.com" name="email">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="example-number-input" class="col-sm-2 col-form-label">Phone no</label>
                                                                <div>
                                                                    <input name="phoneno" class="form-control" type="tel" id="example-tel-input" placeholder="08xxxxxxx">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-sm-2 col-form-label">Role</label>
                                                                <div>
                                                                    <select class="form-control" name="role_id">
                                                                        @foreach($roles as $role)
                                                                        <option value="{{$role->id}}">{{$role->name}} @if(\Illuminate\Support\Facades\Auth::user()->company_id==1) - {{$role->company->name}}@endif</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-group m-b-0">
                                                                <div>
                                                                    @can('admin-create')
                                                                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                                        Create
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
                                    </div>


                                    @if ($edit ?? '')
                                        <div class="tab-pane active p-3" id="edit-1" role="tabpanel">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="card m-b-30">
                                                        <div class="card-body">

                                                            <h4 class="mt-0 header-title">Edit Admin</h4>
                                                            <p class="text-muted m-b-30">Modify Admin</p>

                                                            <form method="POST" action="/admin-update/{{$use->id}}">
                                                                @csrf

                                                                <div class="form-group">
                                                                    <label class="col-sm-2 col-form-label">Last Name</label>
                                                                    <div>
                                                                        <input type="text" class="form-control" required
                                                                               data-parsley-minlength="2" placeholder="Enter admin last name" name="last_name" value="{{$use->last_name}}"/>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="col-sm-2 col-form-label">First Name</label>
                                                                    <div>
                                                                        <input type="text" class="form-control" required
                                                                               data-parsley-minlength="2" placeholder="Enter admin first name" name="first_name" value="{{$use->first_name}}"/>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="example-email-input" class="col-sm-2 col-form-label">Email</label>
                                                                    <div>
                                                                        <input class="form-control" type="email" disabled id="example-email-input" placeholder="admin@email.com" name="email" value="{{$use->email}}">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="example-number-input" class="col-sm-2 col-form-label">Phone no</label>
                                                                    <div>
                                                                        <input name="phoneno" class="form-control" type="tel" id="example-tel-input" placeholder="08xxxxxxx" value="{{$use->phoneno}}">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="col-sm-2 col-form-label">Role</label>
                                                                    <div>
                                                                        <select class="form-control" name="role_id">
                                                                            @foreach($roles as $role)
                                                                                @if($role->id == $use->role_id)
                                                                                    {{true}}
                                                                                    <option selected value="{{$role->id}}">{{$role->name}}</option>
                                                                                @else
                                                                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                                                                @endif

                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group m-b-0">
                                                                    <div>
                                                                        @can('admin-edit')
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
                                        </div>
                                    @endif

                            </div>

                    </div>
                </div>
            </div>
        </div> <!-- end row -->
        @stop

        @section('after-scripts')

        @stop

        @section('before-styles')
        @stop
