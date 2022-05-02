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
            <form action="{{route('admin.payment.store')}}" method="post" >
                @csrf
                
            <h1 class="text-center mb-4">Payout</h1>

                <div class="form-group">
                <label for="account_number">Bank Account</label>
                    <select class="form-control" name="bank_code">
                        <option value="">Select Company Bank Account</option>
                            @foreach($banks as $bank)
                                <option value="{{$bank->code}}">{{$bank->name}}</option>
                            @endforeach
                        </select>
                                        
                </div>
                <div class="form-group">
                    <label for="account_number">Account Number</label>
                    <input type="text" class="form-control" id="account_number" name="account_number" aria-describedby="emailHelp" placeholder="Enter account number" min="10" max="10">
                    <small id="emailHelp" class="form-text text-muted">We'll never share your account number with anyone else.</small>
                </div>
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="text" class="form-control" id="amount"  name="amount" placeholder="amount">
                    <small id="emailHelp" class="form-text text-muted">amount in naira.</small>
                
                </div>
        
                <button type="submit" class="btn btn-success" style="width:50%;margin:0 auto; display:block">Submit</button>

                  
            </form>
        </div>
                        
      
    </div>
    <!-- end row -->
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
