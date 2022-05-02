@section('content')
    <!-- Section-12 -->
    <table class="table_full editable-bg-color bg_color_e6e6e6 editable-bg-image" bgcolor="#e6e6e6" width="100%" align="center"  mc:repeatable="castellab" mc:variant="Header" cellspacing="0" cellpadding="0" border="0">
        <!-- header -->
        <tr>
            <td>
                <!-- container -->
                <table class="table editable-bg-color bg_color_303f9f" bgcolor="#303f9f" width="600" align="center" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
                    <!-- padding-top -->
                    <tr><td height="25"></td></tr>
                    <tr>
                        <td>
                            <!-- Inner container -->
                            <table class="table" width="520" align="center" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
                                <tr>
                                    <td>
                                        <!-- logo -->
                                        <table class="table" width="50%" align="left" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td align="left">
                                                    <a href="#" class="editable-img">
                                                        <img editable="true" mc:edit="image001" src="{{$message->embed('assets/images/logo_small.png')}}" width="68" style="display:block; line-height:0; font-size:0; border:0;" border="0" alt="logo" />
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr><td height="22"></td></tr>
                                        </table><!-- END logo -->

                                        <!-- options -->
                                        <table  class="table" width="50%" align="right" border="0" cellspacing="0" cellpadding="0">
                                            <!-- margin-top -->
                                            <tr><td height="3"></td></tr>
                                            <tr>
                                                <td align="right">
                                                    <a href="#" style="border-style: none !important; display: block; border: 0 !important;" class="editable-img">
                                                        <img editable="true" mc:edit="image002" src="{{$message->embed('assets/images/logo_small.png')}}" width="20" style="display:block; line-height:0; font-size:0; border:0;" border="0" alt="options" />
                                                    </a>
                                                </td>
                                            </tr>
                                        </table><!-- END options -->

                                    </td>
                                </tr>

                                <!-- horizontal gap -->
                                <tr><td height="60"></td></tr>

                                <tr>
                                    <td align="center">
                                        <div class="editable-img">
                                            <img editable="true" mc:edit="image003" src="images/circle-icon-multiple-input.png"  style="display:block; line-height:0; font-size:0; border:0;" border="0" alt="" />
                                        </div>
                                    </td>
                                </tr>

                                <!-- horizontal gap -->
                                <tr><td height="40"></td></tr>

                                <tr>
                                    <td mc:edit="text001" align="center" class="text_color_ffffff" style="color: #ffffff; font-size: 30px; font-weight: 700; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                        <div class="editable-text">
										<span class="text_container">
											<multiline>Purchase Confirmation</multiline>
										</span>
                                        </div>
                                    </td>
                                </tr>

                                <!-- horizontal gap -->
                                <tr><td height="30"></td></tr>

                                <tr>
                                    <td mc:edit="text002" align="center" class="text_color_ffffff" style="color: #ffffff; font-size: 12px; font-weight: 300; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                        <div class="editable-text">
										<span class="text_container">
											<multiline>
                                            {{ $checkout->created_at }}
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

        <!-- body -->
        <tr>
            <td>
                <!-- container -->
                <table class="table editable-bg-color bg_color_ffffff" bgcolor="#ffffff" width="600" align="center" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
                    <!-- padding-top -->
                    <tr><td height="60"></td></tr>

                    <tr>
                        <td>
                            <!-- inner container -->
                            <table class="table" width="520" align="center" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
                                <tr>
                                    <td mc:edit="text003" align="left" class="center_content text_color_282828" style="color: #282828; font-size: 14px; font-weight: 900; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                        <div class="editable-text">
										<span class="text_container">
											<multiline>Hi {{ $checkout->first_name }} {{ $checkout->last_name }}</multiline>
										</span>
                                        </div>
                                    </td>
                                </tr>

                                <!-- horizontal gap -->
                                <tr><td height="15"></td></tr>

                                <tr>
                                    <td mc:edit="text004" align="left" class="center_content text_color_282828" style="color: #282828; font-size: 16px;line-height: 2; font-weight: 500; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                        <div class="editable-text" style="line-height: 2;">
										<span class="text_container">
											<multiline>
												Thanks for buying with us. Below is a summary of your recent purchase. You can download your purchased item at your <a href="#" style="color: #303f9f;text-decoration: none;">downloads page</a> and view your invoice(s) on your <a href="#" style="color: #303f9f; text-decoration: none;">statement here.</a>
											</multiline>
										</span>
                                        </div>
                                    </td>
                                </tr>

                                <!-- horizontal gap -->
                                <tr><td height="50"></td></tr>

                                <!-- table -->
                                <tr>
                                    <td>
                                        <table  class="table" width="100%" border="0" cellspacing="0" cellpadding="0" style="border-radius: 10px">
                                            <tr>
                                                <td style="border-top: 1px solid #e6e6e6;border-right: 1px solid #e6e6e6;border-left: 1px solid #e6e6e6;border-bottom: 1px solid #e6e6e6; padding: 30px;border-top-left-radius: 5px;border-top-right-radius: 5px;">
                                                    <!-- column-1  -->
                                                    <table  width="49%" align="left" border="0" cellspacing="0" cellpadding="0">
                                                        <tr><td height="10"></td></tr>
                                                        <tr>
                                                            <td mc:edit="text003" align="left" class="text_color_282828" style="color: #282828; font-size: 14px; font-weight: 700; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                                                <div class="editable-text">
																<span class="text_container">
																	<multiline>Your Order: </multiline>
																</span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table><!-- END column-1 -->

                                                    <!-- vertical gap -->
                                                    <table class="tablet_hide" width="2%" align="left" border="0" cellspacing="0" cellpadding="0">
                                                        <tr><td height="1"></td></tr>
                                                    </table>

                                                    <!-- column-2  -->
                                                    <table  width="49%" align="right" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td>
                                                                <table class="button_bg_color_303f9f" bgcolor="#303f9f" width="110" height="35" align="right" border="0" cellpadding="0" cellspacing="0" style="background-color:#303f9f; border-radius:3px;">
                                                                    <tr>
                                                                        <td mc:edit="text013" align="center" valign="middle" style="color: #ffffff; font-size: 12px; font-weight: 400; font-family: 'Open Sans', Helvetica, sans-serif; mso-line-height-rule: exactly;" class="text_color_282828">
                                                                            <div class="editable-text">
																			<span class="text_container">
																				<multiline>
																					<a href="#" style="text-decoration: none; color: #ffffff;">My Downloads</a>
																				</multiline>
																			</span>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table><!-- END column-2 -->
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="border-top: 1px solid #e6e6e6;border-right: 1px solid #e6e6e6;border-left: 1px solid #e6e6e6; background-color: #fafafa; padding: 30px;">
                                                    <table  width="49%" align="left" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td mc:edit="text003" align="left" class="text_color_282828" style="color: #282828; font-size: 12px; font-weight: 700; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                                                <div class="editable-text">
																<span class="text_container">
																	<multiline>Item by Dynamic Center</multiline>
																</span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <!-- list of products -->
                                            <tr>
                                                <td style="border-top: 1px solid #e6e6e6;border-right: 1px solid #e6e6e6;border-left: 1px solid #e6e6e6;border-bottom: 1px solid #e6e6e6; padding-right: 30px; padding-left:30px;border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <!-- product-item -->
                                                        <tr>
                                                            <td style="padding-top: 30px;">
                                                                <!-- column-1  -->
                                                                <table class="table1-3" width="80" align="left" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                        <td align="center">
                                                                            <a href="#" style="border-style: none !important; display: block; border: 0 !important;" class="editable-img">
                                                                                <img editable="true" mc:edit="image005" src="images/sq-icon-girl.png" style="display:block; line-height:0; font-size:0; border:0;" border="0" alt="" />
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                    <!-- margin-bottom -->
                                                                    <tr><td height="30"></td></tr>
                                                                </table><!-- END column-1 -->

                                                                <!-- vertical gap -->
                                                                <table class="tablet_hide" width="20" align="left" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr><td height="1"></td></tr>
                                                                </table>

                                                                <!-- column-2  -->
                                                                <table class="table1-3" width="210" align="left" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                        <td mc:edit="text006" align="left" class="center_content text_color_b0b0b0" style="color: #b0b0b0; font-size: 13px;line-height: 1.5; font-weight: 400; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                                                            <div class="editable-text" style="line-height: 1.5;">
																			<span class="text_container">
																				<multiline>Amount : {{ $checkout->amount }}</multiline>
																			</span>
                                                                            </div>
                                                                        </td>
                                                                    </tr>

                                                                    <!-- horizontal gap -->
                                                                    <tr><td height="10"></td></tr>

                                                                    <tr>
                                                                        <td mc:edit="text006" align="left" class="center_content text_color_282828" style="color: #282828; font-size: 12px;line-height: 1.5; font-weight: 300; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                                                            <div class="editable-text" style="line-height: 1.5;">
																			<span class="text_container">
																				<multiline>Quantity : {{ $checkout->quantity }}</multiline>
																			</span>
                                                                            </div>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td mc:edit="text006" align="left" class="center_content text_color_282828" style="color: #282828;font-size: 11px;line-height: 1.5; font-weight: 100; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                                                            <div class="editable-text" style="line-height: 1.5;">
																			<span class="text_container">
																				<multiline>24 hrs months support <span style="color: #b0b0b0"> Order ID: {{ $checkout->order_id }}</span></multiline>
																			</span>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <!-- margin-bottom -->
                                                                    <tr><td height="30"></td></tr>
                                                                </table><!-- END column-2 -->

                                                                <!-- vertical gap -->
                                                                <table class="tablet_hide" width="20" align="left" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr><td height="1"></td></tr>
                                                                </table>

                                                                <!-- column-3  -->
                                                                <table class="table1-3" width="100" align="right" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                        <td>
                                                                            <table class="button_bg_color_282828 bg_color_282828 center_button" bgcolor="#282828" width="80" height="30" align="center" border="0" cellpadding="0" cellspacing="0" style="background-color:#282828; border-radius:3px;">
                                                                                <tr>
                                                                                    <td mc:edit="text013" align="center" valign="middle" style="color: #ffffff; font-size: 12px; font-weight: 400; font-family: 'Open Sans', Helvetica, sans-serif; mso-line-height-rule: exactly;" class="text_color_282828">
                                                                                        <div class="editable-text">
																						<span class="text_container">
																							<multiline>
																								<!--<a href="#" style="text-decoration: none; color: #ffffff;">Install $50</a>-->
																							</multiline>
																						</span>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                    <tr><td height="10"></td></tr>
                                                                    <tr>
                                                                        <td>
                                                                            <table class="button_bg_color_abb0cf bg_color_abb0cf center_button" bgcolor="#abb0cf" width="80" height="30" align="center" border="0" cellpadding="0" cellspacing="0" style="background-color:#abb0cf; border-radius:3px;">
                                                                                <tr>
                                                                                    <td mc:edit="text013" align="center" valign="middle" style="color: #ffffff; font-size: 12px; font-weight: 400; font-family: 'Open Sans', Helvetica, sans-serif; mso-line-height-rule: exactly;" class="text_color_282828">
                                                                                        <div class="editable-text">
																						<span class="text_container">
																							<multiline>
																								<!--<a href="#" style="text-decoration: none; color: #ffffff;">Get Help</a>-->
																							</multiline>
																						</span>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                    <!-- margin-bottom -->
                                                                    <tr><td height="30"></td></tr>
                                                                </table><!-- END column-3 -->
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- horizontal gap -->
                                <tr><td height="60"></td></tr>

                                <tr>
                                    <td mc:edit="text004" align="center" class="center_content text_color_282828" style="color: #282828; font-size: 14px;line-height: 2; font-weight: 500; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                        <div class="editable-text" style="line-height: 2;">
										<span class="text_container">
											<multiline>Download your license certificate at your <a href="#" style="color: #303f9f;text-decoration: none;">downloads page.</a></multiline>
										</span>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td mc:edit="text004" align="center" class="center_content text_color_282828" style="color: #282828; font-size: 14px;line-height: 2; font-weight: 500; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                        <div class="editable-text" style="line-height: 2;">
										<span class="text_container">
											<multiline>If you have trouble accessing your items, you can contact <a href="#" style="color: #303f9f;text-decoration: none;">support</a></multiline>
										</span>
                                        </div>
                                    </td>
                                </tr>
                                <!-- horizontal gap -->
                                <tr><td height="40"></td></tr>

                            </table><!-- END inner container -->
                        </td>
                    </tr>

                    <!-- padding-bottom -->
                    <tr><td height="30"></td></tr>
                </table><!-- END container -->
            </td>
        </tr>
@stop
    <!-- footer -->
@extends('mail.footer')
