@extends('layouts.layout')

@section('title','Airtime Transaction')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">

                    <h4 class="mt-0 header-title">Post Airtime Transaction</h4>
                    <p class="text-muted m-b-30">In case you need to help customer purchase airtime for a specific reason.</p>

                    <form method="POST" action="/postairtimetransaction">
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
                            <label>Phone No </label>
                            <div>
                                <input type="text" name="phoneno" class="form-control" required
                                       data-parsley-pattern="0[0-9]{10}"
                                       placeholder="e.g 070XXXXX"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                @can('post_airtime_transaction')
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    Post Transaction
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
