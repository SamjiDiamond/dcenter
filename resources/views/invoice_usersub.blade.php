@extends('layouts.layout')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">

                    <div class="row">
                        <div class="col-12">
                            <div class="invoice-title">
                                <h4 class="float-right font-16"><strong>Order # <?php $orderid=$company->id.rand(); echo $orderid ?></strong></h4>
                                <h3 class="m-t-0">
                                    <img src="assets/images/logo.png" class="d-print-none" alt="logo" height="28"/>
                                    <img src="assets/images/logo_dark.png" class="d-none d-print-block" alt="logo" height="28"/>
                                </h3>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <address>
                                        <strong>Billed To:</strong><br>
                                        {{$company->name}}<br>
                                        {{$company->address}}
                                    </address>
                                </div>
                                {{--<div class="col-6 text-right">
                                    <address>
                                        <strong>Shipped To:</strong><br>
                                        Kenny Rigdon<br>
                                        1234 Main<br>
                                        Apt. 4B<br>
                                        Springfield, ST 54321
                                    </address>
                                </div>--}}
                            </div>
                            <div class="row">
                                <div class="col-6 m-t-30">
                                    <address>
                                        <strong>Payment Method:</strong><br>
                                        Paystack<br>
{{--                                        Visa ending **** 4242<br>--}}
                                    </address>
                                </div>
                                <div class="col-6 m-t-30 text-right">
                                    <address>
                                        <strong>Order Date:</strong><br>
                                        <?php echo Date('d, M Y'); ?><br><br>
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="panel panel-default">
                                <div class="p-2">
                                    <h3 class="panel-title font-20"><strong>Order summary</strong></h3>
                                </div>
                                <div class="">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <td><strong>Item</strong></td>
                                                <td class="text-center"><strong></strong></td>
                                                <td class="text-center"><strong></strong>
                                                </td>
                                                <td class="text-right"><strong></strong></td>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <tr>
                                                <td>{{$plan->name}} - Subscription</td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-right"></td>
                                            </tr>

                                            <tr>
                                                <td class="thick-line"></td>
                                                <td class="thick-line"></td>
                                                <td class="thick-line text-center">
                                                    <strong>Subtotal</strong>
                                                </td>
                                                @php

                                                    $subtotal = number_format((float)$plan->cost, 2);

                                                    $vat = number_format(($plan->cost * 0.075), 2);

                                                    $display_total = $subtotal;
                                                    $total = number_format((float)$subtotal, 2);


                                                @endphp

                                                <td class="thick-line text-right">₦{{ $subtotal }}</td>
                                            </tr>
                                            <tr>
                                                <td class="no-line"></td>
                                                <td class="no-line"></td>
                                                <td class="no-line text-center">
                                                    <strong>VAT(7.5%)</strong></td>
                                                <td class="no-line text-right">₦{{ $vat }}</td>
                                            </tr>
{{--
                                            @if($u_count>4)

                                            <tr>
                                                <td class="no-line"></td>
                                                <td class="no-line"></td>
                                                <td class="no-line text-center">
                                                    <strong>Discount(1.5%)</strong></td>
                                                <td class="no-line text-right">${{ $u_discount }}</td>
                                            </tr>
                                            @endif--}}
                                            <tr>
                                                <td class="no-line"></td>
                                                <td class="no-line"></td>
                                                <td class="no-line text-center">
                                                    <strong>Total</strong></td>
                                                <td class="no-line text-right"><h4 class="m-0">₦{{ $display_total }}</h4></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="d-print-none mo-mt-2">
                                        <div class="float-right">

                                            <form action="{{route('pay')}}" method="POST" id="payment-form">
                                                @csrf
                                                <div class="form-group">
                                                    <div class="card-body">

                                                        <input type="hidden" name="email" value="{{$company->email}}">
                                                        <input type="hidden" name="first_name" value="{{$company->name}}">
                                                        <input type="hidden" name="last_name" value="dcenter">
                                                        <input type="hidden" name="orderID" value="{{$orderid}}">
                                                        <input type="hidden" name="amount" value="{{ $total * 1000 }}">
                                                        <input type="hidden" name="plan" value="{{ $plan->id}}">
                                                        <input type="hidden" name="quantity" value="1">
                                                        <input type="hidden" name="currency" value="NGN">
                                                        <input type="hidden" name="metadata"
                                                               value="{{ json_encode([ 'custom_fields' => [
                                   ['display_name' => "Billed to", "variable_name" => "Billedto", "value" => $company->name],
                                   ['display_name' => "Payed By", "variable_name" => "payby", "value" => \Illuminate\Support\Facades\Auth::user()->email],
                                  ]]) }}"> {{-- For other necessary things you want to add to your payload. it is optional though --}}


                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <a href="javascript:window.print()"
                                                       class="btn btn-success waves-effect waves-light"><i
                                                            class="fa fa-print"></i>
                                                    </a>
                                                    <button type="submit"
                                                            class="btn btn-primary waves-effect waves-light">Pay
                                                    </button>
                                                  
                                                </div>
                                            </form>


                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div> <!-- end row -->

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->


@endsection

@section('after-scripts')
    <script>
        function payKorapay() {
        window.Korapay.initialize({
            key: "{{env('KORAPAY_KEY')}}", 
            amount: {{$total*100}}, 
            currency: "NGN",
            customer: {
              name: "{{$company->name}}",
              email: "{{$company->email}}"
            },

            onClose: function () {
              // Handle when modal is closed
            },
            onSuccess: function (data) {
              // Handle when payment is successful

              console.log(data['reference']);
              $.ajax({
                        url : '/pay',
                        type:'GET',
                        success: function (response){
                            console.log(response);
                            if(response)
                            { $('#payment-form').prepend(
                                `<h1 style="color: green; margin: 2px;"><strong>Trasaction Mail Sent to your Email</strong></h1>`
                            );
                                
                            }
                        }


                    });
              alert('Transaction Successful. transaction ref is ' + data.reference);
            },
            onFailed: function (data) {
              // Handle when payment fails
            },
            notification_url: "https://superadmin.mcd.5starcompany.com.ng/api/hook/korapay"
        });
    }
       /* function payWithPaystack(){
            var handler = PaystackPop.setup({
                key: '{{env('PAYSTACK_PUBLIC')}}',
                email: '{{$company->email}}',
                plan: "{{$plan->paystack_plan}}",
                ref: "{{$orderid}}",
                metadata: {
                    custom_fields: [
                        {
                            display_name: "subscription",
                            variable_name: "subscription",
                            value: "{{$company->name}} subscription on {{$plan->name}}"
                        }
                    ]
                },
                callback: function(response){
                    // alert('successfully subscribed. transaction ref is ' + response.reference);
                    responseHandler(response);
                },
                onClose: function(){
                    // alert('Payment closed');
                }
            });
            handler.openIframe();
        }

        //singlecharge
        function payWithPaystackSingle(){
            var handler = PaystackPop.setup({
                key: '{{env('PAYSTACK_PUBLIC')}}',
                email: '{{$company->email}}',
                amount: {{$total*100}},
                currency: "NGN",
                ref: "{{$orderid}}", // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
                metadata: {
                    custom_fields: [
                        {
                            display_name: "Daily User Subscription",
                            variable_name: "user_subscription",
                            value: "p"
                        }
                    ]
                },
                callback: function(response){
                    // alert('success. transaction ref is ' + response.reference);
                    responseHandler(response);
                },
                onClose: function(){
                    // alert('window closed');
                }
            });
            handler.openIframe();
        }

        // Submit the form with the token ID.
        function responseHandler(response) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'reference');
            hiddenInput.setAttribute('value', response.reference );
            form.appendChild(hiddenInput);

            // Submit the form
            form.submit();
        }

        */
    </script>
@stop

@section('before-styles')
    <script src="https://korablobstorage.blob.core.windows.net/modal-bucket/korapay-collections.min.js"></script>
@stop


