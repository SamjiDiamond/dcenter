@extends('layouts.layout')

@section('title','Charge Customer')
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

                    <h4 class="mt-0 header-title">Charge Customer</h4>
                    <p class="text-muted m-b-30">In case you need to charge your customer for a specific reason.</p>

                    <form method="POST" action="/chargecustomer">
                            @csrf
                        <div class="form-group">
                            <label>Email / Phone number</label>
                            <input type="text" name="user_name" class="form-control" required placeholder="Type in Email or Phone number"/>
                        </div>

                        <div class="form-group">
                            <label>Amount</label>
                            <input type="number" name="amount" class="form-control" required placeholder="Type in amount"/>
                        </div>

                        <div class="form-group">
                            <label>Description </label>
                            <div>
                                <textarea name="description" class="form-control" required rows="5">Being charges for ...</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                @can('charge_customer')
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    Charge Customer
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
