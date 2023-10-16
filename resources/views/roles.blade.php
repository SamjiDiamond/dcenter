@extends('layouts.mylayouts')

@section('title','Roles & Permission')
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

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                    @endif

                    <!-- Nav tabs -->
                    <ul class="nav nav-pills nav-justified" role="tablist">
                        @if ($edit ?? '')
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link active" data-toggle="tab" href="#edit-1" role="tab">
                                <span class="d-none d-md-block">Edit Role</span><span class="d-block d-md-none"><i class="mdi mdi-home-variant h5"></i></span>
                            </a>
                        </li>
                            <li class="nav-item waves-effect waves-light">
                                <a class="nav-link" data-toggle="tab" href="#home-1" role="tab">
                                    <span class="d-none d-md-block">Roles</span><span class="d-block d-md-none"><i class="mdi mdi-home-variant h5"></i></span>
                                </a>
                            </li>
                            @else
                            <li class="nav-item waves-effect waves-light">
                                <a class="nav-link active" data-toggle="tab" href="#home-1" role="tab">
                                    <span class="d-none d-md-block">Roles</span><span class="d-block d-md-none"><i class="mdi mdi-home-variant h5"></i></span>
                                </a>
                            </li>

                        @endif

                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" data-toggle="tab" href="#profile-1" role="tab">
                                <span class="d-none d-md-block">Create Roles</span><span class="d-block d-md-none"><i class="mdi mdi-account h5"></i></span>
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

                                            <h4 class="mt-0 header-title">Roles</h4>
                                            <p class="text-muted m-b-30 font-14">Manage Roles</p>

                                            <div class="table-responsive">
                                                <table class="table table-striped mb-0">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Description</th>
                                                        <th>Company</th>
                                                        <th>Created at</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($roles as $role)
                                                    <tr>
                                                        <th scope="row">{{ $i++ }}</th>
                                                        <td>{{ $role->name }}</td>
                                                        <td>{{ $role->title }}</td>
                                                        <td>{{ $role->company }}</td>
                                                        <td>{{ $role->created_at }}</td>
                                                        <td>
                                                            @can('role-view')
                                                            <button type="button" class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-sm{{ $role->id }}"><i class="fab fa-wpexplorer"></i> View</button>
                                                            @endcan

                                                            @can('role-edit')
                                                                <a href="/role-edit/{{ $role->id }}" type="button" class="btn btn-info waves-effect waves-light"><i class="fas fas fa-user-edit"></i> Edit</a>
                                                            @endcan
{{--                                                            <a href="/roles-delete/{{ $role->id }}" type="button" class="btn btn-outline-danger waves-effect waves-light"><i class="fas fa-trash"></i>Delete</a>--}}
                                                        </td>
                                                    </tr>

                                                    <div class="col-sm-6 col-md-3 m-t-30">
                                                        <div class="modal fade bs-example-modal-sm{{ $role->id }}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-sm">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title mt-0" id="mySmallModalLabel">{{ ucfirst($role->name) }} Role Details</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p>Name: {{ $role->name }}</p>
                                                                        <p>Description: {{ $role->title }}</p>

                                                                        <ul>
                                                                            <strong>Assigned Permissions</strong>
                                                                        @foreach($rolePermissions as $rolePermission)
                                                                                @if ($rolePermission->entity_id == $role->id)
                                                                            <li>{{$rolePermission->ability_name}}</li>
                                                                                @endif
                                                                            @endforeach
                                                                        </ul>

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

                                            <h4 class="mt-0 header-title">Create Role</h4>
                                            <p class="text-muted m-b-30">Add new role and attach permission to it.</p>

                                            <form method="POST" action="/role-create">
                                                @csrf

                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <div>
                                                        <input type="text" name="name" class="form-control" required data-parsley-minlength="2" placeholder="Enter role name"/>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <div>
                                                        <input type="text" name="description" class="form-control" required
                                                               data-parsley-minlength="2" placeholder="Enter role description"/>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>Permissions</label>
                                                    <div>
                                                        {{--@foreach($permissions as $permission)
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" name="{{$permission->id}}" class="custom-control-input" id="customCheck{{$permission->id}}" data-parsley-multiple="groups"
                                                                   data-parsley-mincheck="2">
                                                            <label class="custom-control-label" for="customCheck{{$permission->id}}">{{ucfirst($permission->name)}}</label>
                                                        </div>
                                                        @endforeach--}}

                                                            @foreach ($permissions as $permission)
                                                           
                                            <input type="checkbox" value="{{$permission->id}}" name="permission[]">
                                           
                                         <label for="{{$permission->name}}">{{ ucfirst($permission->title)}}
                                         </label>
                                          <br>
                                                                {{-- {{ Form::checkbox('permission[]',  $permission->id ) }}
                                                                {{ Form::label($permission->name, ucfirst($permission->title)) }}<br> --}}

                                                            @endforeach

                                                        {{--<div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck2" data-parsley-multiple="groups"
                                                                   data-parsley-mincheck="2">
                                                            <label class="custom-control-label" for="customCheck2">Create Admin</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck3" data-parsley-multiple="groups"
                                                                   data-parsley-mincheck="2">
                                                            <label class="custom-control-label" for="customCheck3">Edit Customer</label>
                                                        </div>--}}

                                                    </div>
                                                </div>

                                                <div class="form-group m-b-0">
                                                    <div>
                                                        {{-- @can('role-create') --}}
                                                        <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                            Create
                                                        </button>
                                                        {{-- @endcan --}}
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

                                            <h4 class="mt-0 header-title">Edit Role</h4>
                                            <p class="text-muted m-b-30">Add new role and attach permission to it.</p>

                                            <form method="POST" action="/role-update/{{$rol->id}}">
                                                @csrf

                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <div>
                                                        <input type="text" name="name" class="form-control" required data-parsley-minlength="2" placeholder="Enter role name" value="{{$rol->name}}"/>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <div>
                                                        <input type="text" name="description" class="form-control" required data-parsley-minlength="2" placeholder="Enter role description" value="{{ $rol->title }}"/>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>Permissions</label>
                                                    <div>
                                                        @foreach($permissions as $value)
                                                            <label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $roleP) ? true : false, array('class' => 'name')) }}
                                                                {{ $value->title }}</label>
                                                            <br/>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <div class="form-group m-b-0">
                                                    <div>
                                                        @can('role-edit')
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
