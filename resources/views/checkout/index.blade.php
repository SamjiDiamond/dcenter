@extends('layouts.layout')

@section('title','')
<style>
    * {
  box-sizing: border-box;
}

</style>
 @section('content')
 <div class="row">
        <div class="col-md-6 mx-auto" style="width:50%;">
            <form>
                    <script src="https://korablobstorage.blob.core.windows.net/modal-bucket/korapay-collections.min.js"></script>
                    <button type="button" onclick="payKorapay()"> Pay </button>
            </form>
        </div>
</div>


<script>
    function payKorapay() {
        window.Korapay.initialize({
            key: "<?= env('KORAPAY_KEY') ?>", 
            amount: 22000, 
            currency: "NGN",
            customer: {
              name: "John Doe",
              email: "john@doe.com"
            },
            notification_url: "https://superadmin.mcd.5starcompany.com.ng/api/hook/korapay"
        });
    }
</script>
@stop

@section('before-styles')
    <!-- App Icons -->
    <link rel="shortcut icon" href="../assets/images/favicon.ico">

    <!-- DataTables -->
    <link href="../plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="../plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- morris css -->
    <link rel="stylesheet" href="../plugins/morris/morris.css">
@stop


{{--                                            @if($plan->slug=='daily')--}}
{{--                                                    <button onclick="payWithPaystackSingle()" class="btn btn-primary waves-effect waves-light">Pay</button>--}}
                                                    {{--                                            @else--}}
                                                    {{--                                                <button onclick="payWithPaystack()" class="btn btn-primary waves-effect waves-light">Pay</button>--}}
                                                    {{--                                            @endif--}}