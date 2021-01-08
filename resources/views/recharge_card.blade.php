@extends('layouts.layout')

@section('title','Recharge Card')
@section('content')
    <div class="row">
        <div class="col-lg-12">
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

                    <h4 class="mt-0 header-title">Post Recharge Card</h4>
                    <p class="text-muted m-b-30">Auto generate Recharge Card and send to customer.</p>

                    <form method="POST" action="/rechargecard">
                            @csrf
                        <div class="form-group">
                            <label>Email / Phone number</label>
                            <input type="text" name="user_name" class="form-control" required placeholder="Type in Email or Phone number"/>
                        </div>

                        <div class="form-group">
                            <label>Network</label>
                            <select class="form-control" name="network" required>
                                <option>MTN</option>
                                <option>AIRTEL</option>
                                <option>GLO</option>
                                <option>9MOBILE</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Amount</label>
                            <select class="form-control" name="amount" required>
                                <option value="100">#100</option>
                                <option value="200">#200</option>
                                <option value="500">#500</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Quantity </label>
                            <div>
                                <input type="number" class="form-control" min="1" max="50" maxlength="2" required data-parsley-minlength="2" placeholder="Enter card quantity" name="quantity" id="quantity" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                @can('recharge_card')
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    Send Recharge Card
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
    <!-- Parsley js -->
    <script src="../plugins/parsleyjs/parsley.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>

    <script>
        $(document).ready(function() {
            $('form').parsley();
        });
    </script>
@stop

@section('before-styles')

@stop
