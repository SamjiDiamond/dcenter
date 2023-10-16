@section('content')
<!-- Section-3 -->
<table class="table_full editable-bg-color bg_color_e6e6e6 editable-bg-image" bgcolor="#e6e6e6" width="100%" align="center"  mc:repeatable="castellab" mc:variant="Header" cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td>
            <!-- container -->
            <table class="table1 editable-bg-color bg_color_303f9f" bgcolor="#303f9f" width="600" align="center" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
                <!-- padding-top -->
                <tr><td height="25"></td></tr>
                <tr>
                    <td>
                        <!-- Inner container -->
                        <table class="table1" width="520" align="center" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
                            <tr>
                                <td>
                                    <!-- logo -->
                                    <table width="50%" align="left" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td align="left">
                                                <a href="#" class="editable-img">
                                                    <img editable="true" mc:edit="image013" src="assets/images/logo_small.png" width="68" style="display:block; line-height:0; font-size:0; border:0;" border="0" alt="logo" />
                                                </a>
                                            </td>
                                        </tr>
                                    </table><!-- END logo -->

                                    <!-- options -->
                                    <table width="50%" align="right" border="0" cellspacing="0" cellpadding="0">
                                        <!-- margin-top -->
                                        <tr><td height="3"></td></tr>
                                        <tr>
                                            <td align="right">
                                                <a href="#" style="border-style: none !important; display: block; border: 0 !important;" class="editable-img">
                                                    <img editable="true" mc:edit="image014" src="assets/images/logo_small.png"  width="20" style="display:block; line-height:0; font-size:0; border:0;" border="0" alt="options" />
                                                </a>
                                            </td>
                                        </tr>
                                    </table><!-- END options -->

                                </td>
                            </tr>

                            <tr>
                                <td align="center">
                                    <div class="editable-img">
                                        <img editable="true" mc:edit="image015" src="images/circle-icon-user.png"  style="display:block; line-height:0; font-size:0; border:0;" border="0" alt="" />
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td mc:edit="text022" align="center" class="text_color_ffffff" style="color: #ffffff; font-size: 30px; font-weight: 700; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                    <div class="editable-text">
										<span class="text_container">
											<multiline>
												Account Created
											</multiline>
										</span>
                                    </div>
                                </td>
                            </tr>

                            <!-- horizontal gap -->
                            <tr><td height="30"></td></tr>

                           <!-- <tr>
                                <td mc:edit="text023" align="center" class="text_color_ffffff" style="color: #ffffff; font-size: 12px; font-weight: 300; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                    <div class="editable-text">
										<span class="text_container">
											<multiline>
												
											</multiline>
										</span>
                                    </div>
                                </td>
                            </tr>-->
                        </table><!-- END inner container -->
                    </td>
                </tr>
                <!-- padding-bottom -->
                <tr><td height="10"></td></tr>
            </table><!-- END container -->
        </td>
    </tr>

    <tr>
        <td>
            <!-- container -->
            <table class="table1 editable-bg-color bg_color_ffffff" bgcolor="#ffffff" width="600" align="center" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
                <!-- padding-top -->
                <tr><td height="60"></td></tr>

                <tr>
                    <td>
                        <!-- inner container -->
                        <table class="table1" width="520" align="center" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;">

                            <!-- horizontal gap -->
                            <tr><td height="10"></td></tr>

                            <tr>
                                <td mc:edit="text024" align="center" class="center_content text_color_a1a2a5" style="color: #a1a2a5; font-size: 14px;line-height: 2; font-weight: 500; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                    <div class="editable-text" style="line-height: 2;">
										<span class="text_container">
											<multiline>
												Your account has been created successfully and is ready to use.
											</multiline>
										</span>
                                    </div>
                                </td>
                            </tr>

                            <!-- horizontal gap -->
                            <tr><td height="20"></td></tr>

                            <tr>
                                <td mc:edit="text025" align="center" style="font-size: 14px; line-height: 2; font-weight: 500; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                    <div class="editable-text" style="line-height: 2;">
										<span class="text_container">
											<multiline>
												<a href="#" class="text_color_303f9f" style="color: #303f9f; text-decoration: none;">http://themeforest.net/user/castellab</a>
											</multiline>
										</span>
                                    </div>
                                </td>

                            </tr>
                            <!-- horizontal gap -->
                            <tr><td height="20"></td></tr>

                            <tr>
                                <td mc:edit="text026" align="center" class="text_color_282828" style="color: #282828; font-size: 20px; font-weight: 700; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                    <div class="editable-text">
										<span class="text_container">
											<multiline>
                                                {{ $user->created_at }}
											</multiline>
										</span>
                                    </div>
                                </td>
                            </tr>

                            <!-- horizontal gap -->
                            <tr><td height="30"></td></tr>

                            <tr>
                                <td mc:edit="text027" align="center" class="text_color_a1a2a5" style="color: #a1a2a5; font-size: 14px;line-height: 2; font-weight: 500; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                    <div class="editable-text" style="line-height: 2;">
										<span class="text_container">
											<multiline>
											Nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit .
											</multiline>
										</span>
                                    </div>
                                </td>
                            </tr>

                            <!-- horizontal gap -->
                            <tr><td height="40"></td></tr>

                            <tr>
                                <td align="center">
                                    <!-- button -->
                                    <table class="button_bg_color_303f9f bg_color_303f9f" bgcolor="#303f9f" width="225" height="50" align="center" border="0" cellpadding="0" cellspacing="0" style="background-color:#303f9f; border-radius:3px;">
                                        <tr>
                                            <td mc:edit="text028" align="center" valign="middle" style="color: #ffffff; font-size: 16px; font-weight: 600; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;" class="text_color_ffffff">
                                                <div class="editable-text">
													<span class="text_container">
														<multiline>
															<a href="#" style="text-decoration: none; color: #ffffff;">Confirm Your Email</a>
														</multiline>
													</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </table><!-- END button -->
                                </td>
                            </tr>

                            <!-- horizontal gap -->
                            <tr><td height="30"></td></tr>

                            <tr>
                                <td mc:edit="text029" align="center" class="text_color_a1a2a5" style="color: #a1a2a5; font-size: 14px;line-height: 2; font-weight: 500; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                    <div class="editable-text" style="line-height: 2;">
										<span class="text_container">
											<multiline>
												Thanks
											</multiline>
										</span>
                                    </div>
                                </td>
                            </tr>
                            <!-- horizontal gap -->
                            <tr><td height="5"></td></tr>

                            <tr>
                                <td mc:edit="text030" align="center" class="text_color_a1a2a5" style="color: #a1a2a5; font-size: 14px;line-height: 2; font-weight: 500; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                    <div class="editable-text" style="line-height: 2;">
										<span class="text_container">
											<multiline>
												Dynamic Center
											</multiline>
										</span>
                                    </div>
                                </td>
                            </tr>

                            <!-- horizontal gap -->
                            <tr><td height="20"></td></tr>

                            <tr>
                                <td mc:edit="text031" align="center" class="text_color_a1a2a5" style="color: #a1a2a5; font-size: 14px;line-height: 2; font-weight: 500; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                    <div class="editable-text" style="line-height: 2;">
										<span class="text_container">
											<multiline>
											Any questions? Get in touch by <a href="#" class="text_color_303f9f" style="color:#303f9f; text-decoration: none;">&nbsp; Email &nbsp; </a> or take a look Our <a href="#" class="text_color_303f9f" style="color:#303f9f; text-decoration: none;">&nbsp; Help Page &nbsp;</a>
											</multiline>
										</span>
                                    </div>
                                </td>
                            </tr>
                        </table><!-- END inner container -->
                    </td>
                </tr>

                <!-- padding-bottom -->
                <tr><td height="60"></td></tr>
            </table><!-- END container -->
        </td>
    </tr>
@stop
@extends('mail.footer')
