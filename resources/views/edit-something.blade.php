@section('content')
<table class="table_full editable-bg-color bg_color_e6e6e6 editable-bg-image" bgcolor="#e6e6e6" width="100%"
       align="center" mc:repeatable="castellab" mc:variant="Header" cellspacing="0" cellpadding="0" border="0"
       xmlns:mc="http://www.w3.org/1999/xhtml">
    <!-- header -->
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
                                                    <img editable="true" mc:edit="image001" src="assets/images/logo.png" width="60" style="display:block; line-height:; font-size:0; border:10px; border-color: white" alt="logo" />
                                                </a>
                                            </td>
                                        </tr>
                                    </table><!-- END logo -->

                                    <!-- options -->
                                    <table width="50%" align="right" border="0" cellspacing="0" cellpadding="0">

                                        <tr>
                                            <td align="right">
                                                <a href="#" style="border-style: none !important; display: block; border: 0 !important;" class="editable-img">
                                                    <img editable="true" mc:edit="image002" src="assets/images/logo.png" width="60" style="display:block; line-height:0; font-size:0; border:0;" border="0" alt="options" />
                                                </a>
                                            </td>
                                        </tr>
                                    </table><!-- END options -->

                                </td>
                            </tr>

                            <tr>
                                <td align="center">
                                    <div class="editable-img">
                                        <img editable="true" mc:edit="image003" src="{{env('APP_URL')}}/images/circle-icon-ticket.png"  style="display:block; line-height:0; font-size:0; border:0;" border="0" alt="" />
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td mc:edit="text001" align="center" class="text_color_ffffff" style="color: #ffffff; font-size: 30px; font-weight: 700; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
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
                            <tr><td height="30"></td></tr>

                            <tr>
                                <td mc:edit="text002" align="center" class="text_color_ffffff" style="color: #ffffff; font-size: 12px; font-weight: 300; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
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
                <tr><td height="60"></td></tr>
            </table><!-- END container -->
        </td>
    </tr>

    <!-- body -->
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

                            <tr>
                                <td mc:edit="text003" align="left" class="center_content text_color_282828" style="color: #282828; font-size: 18px; font-weight: 700; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
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
                            <tr><td height="10"></td></tr>

                            <tr style="margin-bottom: 20px">
                                <td mc:edit="text004" align="left" class="center_content text_color_282828" style="color: #282828; font-size: 14px;line-height: 2; font-weight: 500; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                    <div class="editable-text" style="line-height: 2;">
										<span class="text_container">
											<multiline>
												Your wallet has been credited successfully with the sum of #500. You can also view your funding in the <strong>Transaction</strong> section of your <strong>app</strong>.
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
    @stop
    