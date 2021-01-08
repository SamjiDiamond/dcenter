@extends('layouts.layouts')

@section('title',"Buy SMS Units")
@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card m-b-30">
                <div class="card-body">

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


                    <h4 class="mt-0 header-title">Buy SMS Units</h4>
                    <p class="text-muted m-b-30">Enter units to buy and press pay.</p>


                        <div class="form-group">
                            <label id="up">SMS Unit to buy</label>
                            <input type="number" class="form-control" min="1" max="100000" maxlength="6" onkeyup="f(this.value)" onchange="f(this.value)" required data-parsley-minlength="2" placeholder="Enter SMS units to buy" name="units" id="units" value="" />
                            <input type="hidden" class="form-control" required data-parsley-minlength="2" name="u" id="u" value="" />
                        </div>

                        <div class="pt-3">
                            <button onclick="payWithPaystackSingle(units.value, u.value)" id="pay" class="btn btn-primary btn-block">Pay</button>
                        </div>

                </div>
            </div>

        </div> <!-- end col -->

        <div class="col-lg-6">
            <div class="card m-b-30">
                <div class="card-body">

                    <h4 class="mt-0 header-title">SMS Units price list</h4>
{{--                    <p class="text-muted m-b-30">--}}
{{--                        Use <code>.table-striped</code> to add zebra-striping to any table row--}}
{{--                        within the <code>&lt;tbody&gt;</code>.--}}
{{--                    </p>--}}

                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Units Range</th>
                                <th>Unit Price</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>1 - 9,999</td>
                                <td>4.0</td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>10,000 - 49,000</td>
                                <td>3.8</td>
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td>50,000 - 100,000</td>
                                <td>3.5</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->

    </div> <!-- end row -->

    <form action="{{ route('sms.payment') }}" method="POST" id="payment-form">
        @csrf
        <div class="form-group">
            <div class="card-body">
                <input type="hidden" name="unit_price" value="" />
                <input type="hidden" name="unit_purchased" value="" />
                <input type="hidden" name="total_amount" value="" />
            </div>
        </div>
    </form>

@stop

@section('after-scripts')
    <script type="text/javascript">
        function f(value) {
            var u;
            if(value < 9999){
                u=4;
            }else if(value< 49999){
                u=3.8
            }else if(value< 100000){
                u=3.5
            }else{
                document.getElementById("units").value=1;
            }
            document.getElementById("up").innerHTML="Unit Price is " + u;
            document.getElementById("pay").innerHTML="Pay " + u * value;
            document.getElementById("u").value=u;


            if(u==undefined){
                document.getElementById("up").innerHTML="SMS Unit to buy";
                document.getElementById("pay").innerHTML="Pay ";
            }
        }

        //singlecharge
        function payWithPaystackSingle(value, unit){
            var handler = PaystackPop.setup({
                key: '{{env('PAYSTACK_PUBLIC')}}',
                email: '{{auth()->user()->email}}',
                amount: unit * value * 100,
                currency: "NGN",
                ref: "sms_{{rand()}}", // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
                metadata: {
                    custom_fields: [
                        {
                            display_name: "Description",
                            variable_name: "description",
                            value: "SMS Units payment from samji Company by "
                        },{
                            display_name: "Units",
                            variable_name: "unit",
                            value: value
                        },{
                            display_name: "Unit Price",
                            variable_name: "up",
                            value: unit
                        },{
                            display_name: "Payer ID",
                            variable_name: "payerid",
                            value: "{{auth()->id()}}"
                        }
                    ]
                },
                callback: function(response){
                    // alert('success. transaction ref is ' + response.reference);
                    document.getElementById("unit_price").value=unit;
                    document.getElementById("unit_purchased").value=value;
                    document.getElementById("total_amount").value=unit * value;
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
    </script>


@stop

@section('before-styles')
<!-- App Icons -->
<link rel="shortcut icon" href="assets/images/favicon.ico">
<script src="https://js.paystack.co/v1/inline.js"></script>
@stop
