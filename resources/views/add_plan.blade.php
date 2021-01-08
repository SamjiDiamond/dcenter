@extends('layouts.layout')

@section('title','Plan')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">

                    <h4 class="mt-0 header-title">Add Plan</h4>
                    <p class="text-muted m-b-30">Add more plan to subscription list on Dynamic Centre.</p>

                    <form method="POST" action="">
                        @csrf
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" required placeholder="Type something"/>
                        </div>

                        <div class="form-group">
                            <label>Cost</label>
                            <input type="text" class="form-control" name="cost" required placeholder="Type in amount"/>
                        </div>

                        <div class="form-group">
                            <label>Paystack Plan</label>
                            <div>
                                <input type="text" class="form-control" required
                                       data-parsley-pattern="PLN_[A-Fa-f0-9]{15}"
                                       placeholder="Paystack Plan ID"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Stripe Plan</label>
                            <input type="text" class="form-control" required placeholder="Stripe Plan ID"/>
                        </div>

                        <div class="form-group">
                            <label>Features <code class="highlighter-rouge">seperated by comma (,)</code></label>
                            <div>
                                <textarea required class="form-control" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    Add Plan
                                </button>
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
