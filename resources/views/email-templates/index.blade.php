@extends('layouts.layout')
@section('title', 'Email Templates')
@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <h2 class="text-center bg-primary mx-auto w-25 mb-4 p-1 rounded">Default Templates</h2>
                    <div class="row">
                        @forelse($templates as $template)
                        <div class="col-md-3">
                            <p class="text-center font-weight-bold">{{ $template->name }}</p>
                            <div class="card overflow-auto" style="width: 18rem;max-height: 300px;">
                                <div class="card-body">
                                        <p class="card-text"> {!! $template->content !!} </p>    
                                </div>
                                <div class="card-footer text-center">
                                    <a href="{{ route('email-templates.edit', $template->id) }}"
                                        class="btn btn-outline-success my-2" style="width: 10rem;">Preview</a>
                                </div>
                            </div>
                        </div>
                      
                    @empty
                        <p>No template to display</p>
                    @endforelse

                    </div>
                   
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <div>
                        <h2 class="text-center bg-primary mx-auto w-25 mb-4 p-1 rounded">Saved Templates</h2>
                        <div class="row">
                            @forelse($templateVersions as $templateVersion)
                            <div class="col-md-3">
                                <p class="text-center font-weight-bold">{{ $templateVersion->name }}</p>
                                <div class="card overflow-auto" style="width: 18rem;max-height: 300px;">
                                    <div class="card-body ">
                                        <p class="card-text">{!! $templateVersion->content !!} </p>
                                    </div>
                                    <div class="card-footer text-center">
                                        <a href="{{ route('email-template-versions.edit', $templateVersion->id) }}"
                                            class="btn btn-outline-success my-2" style="width: 10rem;">Preview</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                        @endforelse
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
