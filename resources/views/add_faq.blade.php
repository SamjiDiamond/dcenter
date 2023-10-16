@extends('layouts.layout')

@section('title','Faq')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">

            @if(session()->has('faq')){
            <div class="alert alert-success">
            {{ session()->get('faq') }}
            </div>
        }
        @endif

          @if(session()->has('fa')){
            <div class="alert alert-success">
            {{ session()->get('fa') }}
            </div>
        }
        @endif
                <div class="card-body">

                    <h4 class="mt-0 header-title">Add FAQ</h4>
                    <p class="text-muted m-b-30">Add FAQ to make life easier for merchant on Dynamic Centre.</p>

                    <form method="POST" action="{{ url('faqss') }}">
                            @csrf
                        <div class="form-group">
                            <label>Topic</label>
                            <input type="text" name="title" class="form-control" required placeholder="Type something"/>
                        </div>

                        <div class="form-group">
                            <label>Content</label>
                            <div>
                                <textarea  required class="form-control" rows="5" name="content"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Posted by</label>
                            <input type="text" name="posted_by" class="form-control" required placeholder="Type something"/>
                        </div>

                        <div class="form-group">
                            <label>Company Id</label>
                            <input type="text" name="company_id" class="form-control" required placeholder="Type something"/>
                        </div>
                        <div class="form-group">
                            <div>
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    Add Faq
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
