@extends('layouts.layout')

@section('title','Billing')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center">
                <h5>Choose your Plan</h5>
                <p class="text-muted">Choose the plan that best suits your business.</p>
            </div>
        </div>
    </div>
    <div class="row">
        @foreach($plans as $plan)
        <div class="col-xl-3 col-md-6">
            <div class="pricing-box text-center mt-4 p-2">
                <div class="pricing-title pt-4">
                    <div class="mb-5">
                        @if($plan->id==1)
                        <i class="mdi mdi-gauge-empty h1 text-primary"></i>
                            @elseif($plan->id==2)
                        <i class="mdi mdi-gauge-low h1 text-success"></i>
                            @elseif($plan->id==3)
                        <i class="mdi mdi-gauge h1 text-info"></i>
                            @elseif($plan->id==4)
                        <i class="mdi mdi-gauge-full h1 text-pink"></i>
                        @endif
                    </div>
                    <h5 class="mt-0">{{ $plan->name }}</h5>
                </div>
                <div class="pt-3 pb-4">
                    <h1 class="price font-weight-normal"><sup><small>₦</small></sup>{{ number_format($plan->cost, 2) }}</h1>
                </div>
                <div class="plan-features mb-4">
                    <?php $desc=explode(',', $plan->description)?>
                    @foreach($desc as $list)
                        <p>{{$list}}</p>
                        @endforeach

                </div>
                <div class="pt-3">
                    @if($plan->id==1)
{{--                        @if(!auth()->user()->subscribedToPlan($plan->stripe_plan, 'user_sub'))--}}
                        @if($sub != $plan->stripe_plan)
                            <a href="{{ route('planshow', $plan->slug) }}" class="btn btn-primary btn-block">Join Now</a>
                        @endif
                    @elseif($plan->id==2)
                        @if($sub != $plan->stripe_plan)
                            <a href="{{ route('planshow', $plan->slug) }}" class="btn btn-success btn-block">Join Now</a>
                        @endif
                    @elseif($plan->id==3)
                        @if($sub != $plan->stripe_plan)
                            <a href="{{ route('planshow', $plan->slug) }}" class="btn btn-info btn-block">Join Now</a>
                        @endif
                    @elseif($plan->id==4)
                        @if($sub != $plan->stripe_plan)
                            <a href="{{ route('planshow', $plan->slug) }}" class="btn btn-pink btn-block">Join Now</a>
                        @endif
                    @endif

                </div>
            </div>
        </div>
        @endforeach

    </div>
    <!-- end row -->
@stop

@section('after-scripts')

@stop

@section('before-styles')
   @stop
