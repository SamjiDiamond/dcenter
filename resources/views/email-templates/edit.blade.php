@extends('layouts.layout')
@section('title','Email Templates')
@section('content')

<div class="row">
    <div class="col-lg-12">
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
    
        <div class="card m-b-30">
            <div class="card-body">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                      <button class="nav-link active" id="nav-home-tab" data-toggle="tab" data-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Preview</button>
                      <button class="nav-link" id="nav-profile-tab" data-toggle="tab" data-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Edit</button>
                    </div>
                  </nav>
                  <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        {!! $template->content !!}
                    </div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <form class="pt-3"action="{{($type == 'template') ? route('email-templates.update',$template->id) : route('email-template-versions.update',$template->id) }}" method="Post">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="templateName">Name</label>
                                <input type="text" value="{{$template->name}}" name="name" class="form-control text-light">
                            </div>
    
                            <div class="form-group">
                                <label for="templateName">Template</label>
                              <textarea name="content" id="" cols="30" rows="10" class="form-control text-light">{{$template->content}}</textarea>
                            </div>
                            
                            <div class="form-group text-right">
                                <input  type="submit" class="btn btn-success">
                            </div>
                        </form>
                    </div>
                   
                  </div>
                  
            </div>
        </div>
    </div>
</div>

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