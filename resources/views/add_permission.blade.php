@extends('layouts.layout')

@section('title','Permission')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">

                    <h4 class="mt-0 header-title">Add Permission</h4>
                    <p class="text-muted m-b-30">Add a new Permission to control user access.</p>

                    <form method="POST" action="">
                            @csrf
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" required placeholder="Type title for user clarification"/>
                        </div>

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="Type name for system identification"/>
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
