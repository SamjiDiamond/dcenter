<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OthersController extends Controller
{
    public function faq(){
        $faq=Faq::where("company_id","=",Auth::user()->company_id)->orderBy('id', 'desc')->select('id', 'title', 'content')->get();
        return response()->json(['status' => 1, 'message'=>'Fetched FAQ successfully', 'data'=> $faq]);
    }
}
