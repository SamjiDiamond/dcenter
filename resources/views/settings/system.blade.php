@extends('layouts.layout')

@section('title', 'System Settings')

@section('content')
    <div class="row">
        <div class="col-xl-8 offset-xl-2">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">System Settings</h4>
                    <p class="text-muted font-14">
                        These values are used live across the dashboard. Changing them here updates the
                        Billing page and the API immediately — no hardcoded figures.
                    </p>

                    <form method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf

                        <div class="form-group">
                            <label for="fundingFee">Wallet top-up funding fee (₦)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">₦</span>
                                </div>
                                <input type="number" step="0.01" min="0" name="funding_fee" id="fundingFee"
                                       class="form-control @error('funding_fee') is-invalid @enderror"
                                       value="{{ old('funding_fee', $settings->funding_fee) }}" required>
                                @error('funding_fee')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">
                                Applied to wallet top-ups and exposed via <code>/api/user</code> →
                                <code>settings.funding_fee</code>. Current value:
                                <b>₦{{ number_format($settings->funding_fee, 2) }}</b>
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light">
                            <i class="mdi mdi-content-save-outline mr-1"></i> Save settings
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('after-scripts')
@stop

@section('before-styles')
@stop
