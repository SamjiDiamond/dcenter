@extends('layouts.layout')

@section('title','Reversal')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">

                    <h4 class="mt-0 header-title">Reversal Posting</h4>
                    <p class="text-muted m-b-30">This is where you correct wrong transactions for a specific reason.</p>

                    <form method="POST" action="/reversal">
                            @csrf
                        <div class="form-group">
                            <label>Transaction ID</label>
                            <input type="number" name="id" class="form-control" required placeholder="Type in transaction id"/>
                        </div>

                        <div class="form-group">
                            <div>
                                @can('reversal')
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    Continue
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

    @if($t??'')
    <div class="row">
        <div class="col-lg-6">
            <div class="card m-b-30">
                <div class="card-body">

                    <h4 class="mt-0 header-title">Transaction</h4>
                   {{-- <p class="text-muted m-b-30">
                        Use <code>.table-striped</code> to add zebra-striping to any table row
                        within the <code>&lt;tbody&gt;</code>.
                    </p>--}}

                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>User</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th scope="row">{{$data->id}}</th>
                                <td>{{$data->name}}</td>
                                <td>{{$data->description}}</td>
                                <td>{{$data->last_name}} {{$data->first_name}}</td>
                                <td>{{$data->status}}</td>
                                <td>{{$data->created_at}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->

        <div class="col-lg-6">
            <div class="card m-b-30">
                <div class="card-body">

                    <h4 class="mt-0 header-title">Reversal</h4>
                    {{--<p class="text-muted m-b-30">
                        Use <code>.table-striped</code> to add zebra-striping to any table row
                        within the <code>&lt;tbody&gt;</code>.
                    </p>--}}

                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>User</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th scope="row">??</th>
                                <td>Reversal</td>
                                <td>being reversal of {{$data->description}}</td>
                                <td>{{$data->last_name}} {{$data->first_name}}</td>
                                <td>pending</td>
                                <td> <?php echo date(now()) ;?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->

        <div class="col-lg-12" align="middle" style="margin: 10px">
        @can('reversal')
                <form method="POST" action="/reversal-post">
                    @csrf
                    <input type="hidden" name="id" class="form-control" required value="{{$data->id}}"/>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                    Post Reversal
                    </button>
                </form>
        @endcan
        </div>

    </div> <!-- end row -->

    @endif
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
