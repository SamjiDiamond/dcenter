@extends('layouts.mylayouts')

@section('title','Company Wallet Ledger')
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
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" data-toggle="tab" href="#profile-1" role="tab">
                                <span class="d-none d-md-block"></span><span class="d-block d-md-none"><i class="mdi mdi-account h5"></i></span>
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

                                            <h4 class="mt-0 header-title">Company Wallet</h4>
                                            <p class="text-muted m-b-30 font-14"></p>

                                            <div class="table-responsive">
                                                <table id="datatable-buttons" class="table table-striped mb-0">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Company Name</th>
                                                        <th>Payment Id.</th>
                                                        <th>Amount</th>
                                                        <th>Description</th>
                                                        <th>Type</th>
                                                        <th>Status</th>
                                                        <th>Old Wallet </th>
                                                        <th>New Wallet</th>
                                                        <th>Created at</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @forelse($companyWallets as $companyWallet)
                                                    <tr>
                                                        <th scope="row">{{ $i++ }}</th>
                                                        <td>{{ $companyWallet->company->name }}</td>
                                                        <td>{{ $companyWallet->payment_id }}</td>
                                                        <td>{{ $companyWallet->amount}}</td>
                                                        <td>{{ $companyWallet->description }}</td>
                                                        <td>{{ $companyWallet->type}}</td>
                                                        <td>{{ $companyWallet->status }}</td>
                                                        <td>{{ $companyWallet->old_wallet }}</td>
                                                        <td>{{ $companyWallet->new_wallet }}</td>
                                                         <td>{{ $companyWallet->created_at }}</td>
                                                      
                                                    </tr>

                                                    @empty
                                                    <tr><td colspan="10" class="text-center">No company Wallet history to display</td></tr>
                                                    @endforelse
                                                    </tbody> 
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->
                        </div>
                        
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
