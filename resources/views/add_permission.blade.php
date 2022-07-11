@extends('layouts.layout')

@section('title','Permission')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">

            @if(session()->has('perm'))
            <div class="alert alert-success">
            {{ session()->get('perm') }}
            </div>
        
        @endif

          @if(session()->has('per'))
            <div class="alert alert-success">
            {{ session()->get('per') }}
            </div>
        
        @endif
                <div class="card-body">

                    <h4 class="mt-0 header-title">Add Permission</h4>
                    <p class="text-muted m-b-30">Add a new Permission to control Customer access.</p>

                    <form method="POST" action="{{ url('permission') }}">
                            @csrf
                        <div class="form-group">
                            <label>Ability Id</label>
                            <input type="text" name="ability" class="form-control" required placeholder="Type title for user clarification"/>
                        </div>

                         <div class="form-group">
                            <label>Entity Type</label>
                            <input type="text" name="entity_type" class="form-control" required placeholder="Type title for user clarification"/>
                        </div>

                         <div class="form-group">
                            <label>Entity Id</label>
                            <input type="text" name="entity_id" class="form-control" required placeholder="Type title for user clarification"/>
                        </div>

                        <div class="form-group">
                            <label>Forbidden</label>
                            <input type="text" name="forbidden" class="form-control" required placeholder="Type name for system identification"/>
                        </div>

                        <div class="form-group">
                            <div>
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    Add Permission
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
