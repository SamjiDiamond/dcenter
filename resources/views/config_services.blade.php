@extends('layouts.layout')

@section('title','Services Configuration')
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
                                    <span class="d-none d-md-block">Edit</span><span class="d-block d-md-none"><i class="mdi mdi-home-variant h5"></i></span>
                                </a>
                            </li>
                            <li class="nav-item waves-effect waves-light">
                                <a class="nav-link" data-toggle="tab" href="#home-1" role="tab">
                                    <span class="d-none d-md-block">Airtime</span><span class="d-block d-md-none"><i class="mdi mdi-home-variant h5"></i></span>
                                </a>
                            </li>
                        @else
                            <li class="nav-item waves-effect waves-light">
                                <a class="nav-link active" data-toggle="tab" href="#home-1" role="tab">
                                    <span class="d-none d-md-block">Airtime</span><span class="d-block d-md-none"><i class="mdi mdi-home-variant h5"></i></span>
                                </a>
                            </li>

                        @endif

                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" data-toggle="tab" href="#profile-1" role="tab">
                                <span class="d-none d-md-block">Data</span><span class="d-block d-md-none"><i class="mdi mdi-account h5"></i></span>
                            </a>
                        </li>
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" data-toggle="tab" href="#messages-1" role="tab">
                                <span class="d-none d-md-block">Tv Subscription</span><span class="d-block d-md-none"><i class="mdi mdi-email h5"></i></span>
                            </a>
                        </li>
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" data-toggle="tab" href="#messages-2" role="tab">
                                <span class="d-none d-md-block">Electricity</span><span class="d-block d-md-none"><i class="mdi mdi-email h5"></i></span>
                            </a>
                        </li>
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" data-toggle="tab" href="#settings-1" role="tab">
                                <span class="d-none d-md-block">Transfer</span><span class="d-block d-md-none"><i class="mdi mdi-settings h5"></i></span>
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

                                            <h4 class="mt-0 header-title">Airtime Configuration</h4>
                                            <p class="text-muted m-b-30 font-14">Set discount to be given to customer and you can also disable</p>

                                            @if($synairtime??'')
                                            <a href="/services-airtime-sync" type="button" class="btn btn-info waves-effect waves-light" style="margin: 5px"><i class="mdi mdi-cloud-download"></i> Syn Services (Recommended)</a>
                                            @endif

                                            <div class="table-rep-plugin">
                                                <div class="table-responsive b-0" data-pattern="priority-columns">
                                                    <table id="tech-companies-1" class="table  table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th>Network</th>
                                                            <th data-priority="1">Discount(%)</th>
                                                            <th data-priority="1">Default Discount(%)</th>
                                                            <th data-priority="6">Description</th>
                                                            <th data-priority="1">Status</th>
                                                            <th data-priority="1">Company</th>
                                                            <th data-priority="1">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($airtime as $air)
                                                        <tr>
                                                            <th><span class="co-name">{{$air->code}}</span></th>
                                                            <td>{{$air->price}}</td>
                                                            <td>{{$air->default_price->price}}</td>
                                                            <td>{{$air->desc}}</td>
                                                            <td>@if($air->status==1)  active @else disable @endif </td>
                                                            <td>{{$air->company->name}}</td>
                                                            <td><a href="/services-airtime-edit/{{ $air->id }}" type="button" class="btn btn-info waves-effect waves-light"><i class="fas fas fa-user-edit"></i>Edit</a></td>
                                                        </tr>
                                                        @endforeach

                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->
                        </div>
                        <div class="tab-pane p-3" id="profile-1" role="tabpanel">

                            <h4 class="mt-0 header-title">Data Configuration</h4>
                            <p class="text-muted m-b-30 font-14">Set price to be given to customer and you can also disable</p>

                            @if($syndata??'')
                                <a href="/services-data-sync" type="button" class="btn btn-info waves-effect waves-light" style="margin: 5px"><i class="mdi mdi-cloud-download"></i> Syn Services (Recommended)</a>
                            @endif

                            <div class="table-rep-plugin">
                                <div class="table-responsive b-0" data-pattern="priority-columns">
                                    <table id="tech-companies-1" class="table  table-striped">
                                        <thead>
                                        <tr>
                                            <th>Network</th>
                                            <th data-priority="1">Price</th>
                                            <th data-priority="1">Default Price</th>
                                            <th data-priority="6">Description</th>
                                            <th data-priority="1">Status</th>
                                            <th data-priority="1">Company</th>
                                            <th data-priority="1">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data as $dat)
                                            <tr>
                                                <th><span class="co-name">{{$dat->network}} {{$dat->code}}</span></th>
                                                <td>{{$dat->price}}</td>
                                                <td>{{$dat->default_price->price}}</td>
                                                <td>{{$dat->desc}}</td>
                                                <td>@if($dat->status==1)  active @else disable @endif </td>
                                                <td>{{$dat->company->name}}</td>
                                                <td><a href="/services-data-edit/{{ $dat->id }}" type="button" class="btn btn-info waves-effect waves-light"><i class="fas fas fa-user-edit"></i>Edit</a> </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                        <div class="tab-pane p-3" id="messages-1" role="tabpanel">
                            <h4 class="mt-0 header-title">TV Configuration</h4>
                            <p class="text-muted m-b-30 font-14">Set price to be given to customer and you can also disable</p>

                            @if($syntv??'')
                                <a href="/services-tv-sync" type="button" class="btn btn-info waves-effect waves-light" style="margin: 5px"><i class="mdi mdi-cloud-download"></i> Syn Services (Recommended)</a>
                            @endif

                            <div class="table-rep-plugin">
                                <div class="table-responsive b-0" data-pattern="priority-columns">
                                    <table id="tech-companies-1" class="table  table-striped">
                                        <thead>
                                        <tr>
                                            <th>Network</th>
                                            <th data-priority="1">Price</th>
                                            <th data-priority="1">Default Price</th>
                                            <th data-priority="6">Description</th>
                                            <th data-priority="1">Status</th>
                                            <th data-priority="1">Company</th>
                                            <th data-priority="1">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($tv as $t)
                                            <tr>
                                                <th><span class="co-name">{{$t->code}}</span></th>
                                                <td>{{$t->price}}</td>
                                                <td>{{$t->default_price->price}}</td>
                                                <td>{{$t->desc}}</td>
                                                <td>@if($t->status==1)  active @else disable @endif </td>
                                                <td>{{$t->company->name}}</td>
                                                <td><a href="/services-tv-edit/{{ $t->id }}" type="button" class="btn btn-info waves-effect waves-light"><i class="fas fas fa-user-edit"></i>Edit</a></td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>

                        <div class="tab-pane p-3" id="messages-2" role="tabpanel">
                            <h4 class="mt-0 header-title">Electricity Configuration</h4>
                            <p class="text-muted m-b-30 font-14">Set charges to be given to customer and you can also disable</p>

                            @if($synelec??'')
                                <a href="/services-electricity-sync" type="button" class="btn btn-info waves-effect waves-light" style="margin: 5px"><i class="mdi mdi-cloud-download"></i> Syn Services (Recommended)</a>
                            @endif

                            <div class="table-rep-plugin">
                                <div class="table-responsive b-0" data-pattern="priority-columns">
                                    <table id="tech-companies-1" class="table  table-striped">
                                        <thead>
                                        <tr>
                                            <th>Network</th>
                                            <th data-priority="1">Discount(%)</th>
                                            <th data-priority="1">Default Discount(%)</th>
                                            <th data-priority="6">Description</th>
                                            <th data-priority="1">Status</th>
                                            <th data-priority="1">Company</th>
                                            <th data-priority="1">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($electricity as $elec)
                                            <tr>
                                                <th><span class="co-name">{{$elec->code}}</span></th>
                                                <td>{{$elec->price}}</td>
                                                <td>{{$elec->default_price->price}}</td>
                                                <td>{{$elec->desc}}</td>
                                                <td>@if($elec->status==1)  active @else disable @endif </td>
                                                <td>{{$elec->company->name}}</td>
                                                <td><a href="/services-electricity-edit/{{ $elec->id }}" type="button" class="btn btn-info waves-effect waves-light"><i class="fas fas fa-user-edit"></i>Edit</a></td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>

                        <div class="tab-pane p-3" id="settings-1" role="tabpanel">
                            <h4 class="mt-0 header-title">Transfer Configuration</h4>
                            <p class="text-muted m-b-30 font-14">Set charges to be given to customer and you can also disable</p>

                            @if($syntran??'')
                                <a href="/services-transfer-sync" type="button" class="btn btn-info waves-effect waves-light" style="margin: 5px"><i class="mdi mdi-cloud-download"></i> Syn Services (Recommended)</a>
                            @endif

                            <div class="table-rep-plugin">
                                <div class="table-responsive b-0" data-pattern="priority-columns">
                                    <table id="tech-companies-1" class="table  table-striped">
                                        <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th data-priority="1">Charges</th>
                                            <th data-priority="1">Default Charges</th>
                                            <th data-priority="6">Description</th>
                                            <th data-priority="1">Status</th>
                                            <th data-priority="1">Company</th>
                                            <th data-priority="1">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($transfer as $trans)
                                            <tr>
                                                <th><span class="co-name">{{$trans->code}}</span></th>
                                                <td>{{$trans->price}}</td>
                                                <td>{{$trans->default_price->price}}</td>
                                                <td>{{$trans->desc}}</td>
                                                <td>@if($trans->status==1)  active @else disable @endif </td>
                                                <td>{{$trans->company->name}}</td>
                                                <td><a href="/services-transfer-edit/{{ $trans->id }}" type="button"
                                                       class="btn btn-info waves-effect waves-light"><i
                                                            class="fas fas fa-user-edit"></i>Edit</a></td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                                    @if ($edit ?? '')
                                        <div class="tab-pane active p-3" id="edit-1" role="tabpanel">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="card m-b-30">
                                                        <div class="card-body">

                                                            <h4 class="mt-0 header-title">Edit Service Configuration</h4>
                                                            <p class="text-muted m-b-30">Modify {{$editd->code}} {{$type}}</p>

                                                            <form method="POST" action="/services-{{$type}}-update/{{$editd->id}}">
                                                                @csrf

                                                                <div class="form-group">
                                                                    <label class="col-sm-2 col-form-label">Discount/Price</label>
                                                                    <div>
                                                                        <input type="number" class="form-control" required
                                                                               data-parsley-minlength="2" placeholder="Enter discount/price" min="0" name="price" value="{{$editd->price}}"/>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="col-sm-2 col-form-label">Description</label>
                                                                    <div>
                                                                        <input type="text" class="form-control" data-parsley-minlength="2" placeholder="Enter description" name="description" value="{{$editd->desc}}"/>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="col-sm-2 col-form-label">Status</label>
                                                                    <div>
                                                                        <select class="form-control" name="status">
                                                                                @if($editd->status == 1)
                                                                                    <option selected value="1">active</option>
                                                                                <option value="0">disable</option>
                                                                                @else
                                                                                <option value="1">active</option>
                                                                                    <option selected value="0">disable</option>
                                                                                @endif
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group m-b-0">
                                                                    <div>
                                                                        <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                                            Update
                                                                        </button>
                                                                        <a href="/services" type="button" class="btn btn-secondary waves-effect m-l-5">
                                                                            Cancel
                                                                        </a>
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
    <!-- Responsive-table-->
    <script src="plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js"></script>

    <script>
        $(function() {
            $('.table-responsive').responsiveTable({
                addDisplayAllBtn: 'btn btn-secondary'
            });
        });
    </script>
@stop

@section('before-styles')
    <!-- Table css -->
    <link href="plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen">
@stop
