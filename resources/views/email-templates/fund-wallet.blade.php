@extends('layouts.layout')
@section('title', 'Fund Walet Email Templates')
@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-home-tab" data-toggle="tab" data-target="#nav-home"
                                type="button" role="tab" aria-controls="nav-home" aria-selected="true">Preview</button>
                            <button class="nav-link" id="nav-profile-tab" data-toggle="tab" data-target="#nav-profile"
                                type="button" role="tab" aria-controls="nav-profile"
                                aria-selected="false">Edit</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                            aria-labelledby="nav-home-tab">
                            <table class="table_full editable-bg-color bg_color_e6e6e6 editable-bg-image" bgcolor="#e6e6e6"
                                width="100%" align="center" mc:repeatable="castellab" mc:variant="Header" cellspacing="0"
                                cellpadding="0" border="0" xmlns:mc="http://www.w3.org/1999/xhtml">
                                <!-- header -->
                                <tr>
                                    <td>
                                        <!-- container -->
                                        <table class="table1 editable-bg-color bg_color_303f9f" bgcolor="#303f9f"
                                            width="600" align="center" border="0" cellspacing="0" cellpadding="0"
                                            style="margin: 0 auto;">
                                            <!-- padding-top -->
                                            <tr>
                                                <td height="25"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <!-- Inner container -->
                                                    <table class="table1" width="520" align="center" border="0"
                                                        cellspacing="0" cellpadding="0" style="margin: 0 auto;">
                                                        <tr>
                                                            <td>
                                                                <!-- logo -->
                                                                <table width="50%" align="left" border="0"
                                                                    cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                        <td align="left">
                                                                            <a href="#" class="editable-img">
                                                                                <img editable="true" mc:edit="image001"
                                                                                    src="assets/images/logo.png"
                                                                                    width="60"
                                                                                    style="display:block; line-height:; font-size:0; border:10px; border-color: white"
                                                                                    alt="logo" />
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                </table><!-- END logo -->

                                                                <!-- options -->
                                                                <table width="50%" align="right" border="0"
                                                                    cellspacing="0" cellpadding="0">

                                                                    <tr>
                                                                        <td align="right">
                                                                            <a href="#"
                                                                                style="border-style: none !important; display: block; border: 0 !important;"
                                                                                class="editable-img">
                                                                                <img editable="true" mc:edit="image002"
                                                                                    src="assets/images/logo.png"
                                                                                    width="60"
                                                                                    style="display:block; line-height:0; font-size:0; border:0;"
                                                                                    border="0" alt="options" />
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                </table><!-- END options -->

                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td align="center">
                                                                <div class="editable-img">
                                                                    <img editable="true" mc:edit="image003"
                                                                        src="{{ env('APP_URL') }}/images/circle-icon-ticket.png"
                                                                        style="display:block; line-height:0; font-size:0; border:0;"
                                                                        border="0" alt="" />
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td mc:edit="text001" align="center" class="text_color_ffffff"
                                                                style="color: #ffffff; font-size: 30px; font-weight: 700; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                                                <div class="editable-text">
                                                                    <span class="text_container">
                                                                        <multiline>
                                                                            Wallet funded successfully
                                                                        </multiline>
                                                                    </span>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <!-- horizontal gap -->
                                                        <tr>
                                                            <td height="30"></td>
                                                        </tr>

                                                        <tr>
                                                            <td mc:edit="text002" align="center"
                                                                class="text_color_ffffff"
                                                                style="color: #ffffff; font-size: 12px; font-weight: 300; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                                                <div class="editable-text">
                                                                    <span class="text_container">
                                                                        <multiline>
                                                                            8 Dec, 2015
                                                                        </multiline>
                                                                    </span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table><!-- END inner container -->
                                                </td>
                                            </tr>
                                            <!-- padding-bottom -->
                                            <tr>
                                                <td height="60"></td>
                                            </tr>
                                        </table><!-- END container -->
                                    </td>
                                </tr>

                                <!-- body -->
                                <tr>
                                    <td>
                                        <!-- container -->
                                        <table class="table1 editable-bg-color bg_color_ffffff" bgcolor="#ffffff"
                                            width="600" align="center" border="0" cellspacing="0"
                                            cellpadding="0" style="margin: 0 auto;">
                                            <!-- padding-top -->
                                            <tr>
                                                <td height="60"></td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <!-- inner container -->
                                                    <table class="table1" width="520" align="center" border="0"
                                                        cellspacing="0" cellpadding="0" style="margin: 0 auto;">

                                                        <tr>
                                                            <td mc:edit="text003" align="left"
                                                                class="center_content text_color_282828"
                                                                style="color: #282828; font-size: 18px; font-weight: 700; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                                                <div class="editable-text">
                                                                    <span class="text_container">
                                                                        <multiline>
                                                                            Hello John Klem,
                                                                        </multiline>
                                                                    </span>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <!-- horizontal gap -->
                                                        <tr>
                                                            <td height="10"></td>
                                                        </tr>

                                                        <tr style="margin-bottom: 20px">
                                                            <td mc:edit="text004" align="left"
                                                                class="center_content text_color_282828"
                                                                style="color: #282828; font-size: 14px;line-height: 2; font-weight: 500; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                                                <div class="editable-text" style="line-height: 2;">
                                                                    <span class="text_container">
                                                                        <multiline>
                                                                            Your wallet has been credited successfully with
                                                                            the sum of #500. You can also view your funding
                                                                            in the <strong>Transaction</strong> section of
                                                                            your <strong>app</strong>.
                                                                        </multiline>
                                                                    </span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table><!-- END inner container -->
                                                </td>
                                            </tr>

                                        </table><!-- END container -->
                                    </td>
                                </tr>
                                <!-- footer -->
                                <tr>
                                    <td>
                                        <!-- container -->
                                        <table class="table1" width="600" align="center" border="0"
                                            cellspacing="0" cellpadding="0" style="margin: 0 auto;">
                                            <!-- padding-top -->
                                            <tr>
                                                <td height="40"></td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <!--  column-1 -->
                                                    <table class="table1-2" width="350" align="left" border="0"
                                                        cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td mc:edit="text011" align="left"
                                                                class="center_content text_color_929292"
                                                                style="color: #929292; font-size: 14px; line-height: 2; font-weight: 400; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                                                <div class="editable-text" style="line-height: 2;">
                                                                    <span class="text_container">
                                                                        <multiline>
                                                                            {{-- This mail was sent to {{ $user->email }} via :<a href="#" style="color: #303f9f;text-decoration: none;"> Ayomide Data Ventures</a> --}}
                                                                        </multiline>
                                                                    </span>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <!-- horizontal gap -->
                                                        <tr>
                                                            <td height="20"></td>
                                                        </tr>

                                                        <tr>
                                                            <td mc:edit="text012" align="left" class="center_content"
                                                                style="font-size: 14px;font-weight: 400; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                                                <div class="editable-text">
                                                                    <span class="text_container">
                                                                        <multiline>
                                                                            <a href="#" class="text_color_929292"
                                                                                style="color:#929292; text-decoration: none;">Dynamic
                                                                                Centre</a>
                                                                        </multiline>
                                                                    </span>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <!-- horizontal gap -->
                                                        <tr>
                                                            <td height="10"></td>
                                                        </tr>

                                                        <tr>
                                                            <td mc:edit="text013" align="left" class="center_content"
                                                                style="font-size: 14px;font-weight: 400; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                                                <div class="editable-text">
                                                                    <span class="text_container">
                                                                        <multiline>
                                                                            <a href="#" class="text_color_929292"
                                                                                style="color:#929292; text-decoration: none; display: block;">Powered
                                                                                by 5Star Company</a>
                                                                        </multiline>
                                                                    </span>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <!-- margin-bottom -->
                                                        <tr>
                                                            <td height="30"></td>
                                                        </tr>
                                                    </table><!-- END column-1 -->

                                                    <!-- vertical gap -->
                                                    <table class="tablet_hide" width="130" align="left"
                                                        border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td height="1"></td>
                                                        </tr>
                                                    </table>

                                                    <!-- column-2  -->
                                                    <table class="table1-2" width="120" align="right" border="0"
                                                        cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td>
                                                                <table width="120" align="center"
                                                                    style="margin: 0 auto;">
                                                                    <tr>
                                                                        <!-- facebook -->
                                                                        <td align="center" width="30">
                                                                            <a href="#"
                                                                                style="border-style: none !important; display: inline-block;; border: 0 !important;"
                                                                                class="editable-img">
                                                                                <img editable="true" mc:edit="image007"
                                                                                    src=""
                                                                                    width="30"
                                                                                    style="display:block; line-height:0; font-size:0; border:0;"
                                                                                    border="0" alt="" />
                                                                            </a>
                                                                        </td>

                                                                        <!-- vertical gap -->
                                                                        <td width="15"></td>

                                                                        <!-- twitter -->
                                                                        <td align="center" width="30">
                                                                            <a href="#"
                                                                                style="border-style: none !important; display: inline-block; border: 0 !important;"
                                                                                class="editable-img">
                                                                                <img editable="true" mc:edit="image008"
                                                                                    src=""
                                                                                    width="30"
                                                                                    style="display:block; line-height:0; font-size:0; border:0;"
                                                                                    border="0" alt="" />
                                                                            </a>
                                                                        </td>

                                                                        <!-- vertical gap -->
                                                                        <td width="15"></td>

                                                                        <!-- google+ -->
                                                                        <td align="center" width="30">
                                                                            <a href="#"
                                                                                style="border-style: none !important; display: inline-block;; border: 0 !important;"
                                                                                class="editable-img">
                                                                                <img editable="true" mc:edit="image009"
                                                                                    src=""
                                                                                    width="30"
                                                                                    style="display:block; line-height:0; font-size:0; border:0;"
                                                                                    border="0" alt="" />
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <!-- margin-bottom -->
                                                        <tr>
                                                            <td height="30"></td>
                                                        </tr>
                                                    </table><!-- END column-2 -->
                                                </td>
                                            </tr>

                                            <!-- padding-bottom -->
                                            <tr>
                                                <td height="70"></td>
                                            </tr>
                                        </table><!-- END container -->
                                    </td>
                                </tr>
                            </table><!-- END wrapper -->

                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <form action="" method="Post">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="templateName">Name</label>
                                    <input type="text" value="" name="name" class="form-control text-light">
                                </div>
        
                                <div class="form-group">
                                    <label for="templateName">Template</label>
                                  <textarea name="content" id="" cols="30" rows="10" class="form-control text-light"></textarea>
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


