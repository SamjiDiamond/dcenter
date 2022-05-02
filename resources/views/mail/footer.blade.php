@yield('content')
    <!-- footer -->
    <tr>
        <td>
            <!-- container -->
            <table class="table1" width="600" align="center" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
                <!-- padding-top -->
                <tr><td height="40"></td></tr>

                <tr>
                    <td>
                        <!--  column-1 -->
                        <table class="table1-2" width="350" align="left" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td mc:edit="text011" align="left" class="center_content text_color_929292" style="color: #929292; font-size: 14px; line-height: 2; font-weight: 400; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                    <div class="editable-text" style="line-height: 2;">
										<span class="text_container">
											<multiline>
												This mail was sent to {{ $user->email }} via :<a href="#" style="color: #303f9f;text-decoration: none;"> Ayomide Data Ventures</a>
											</multiline>
										</span>
                                    </div>
                                </td>
                            </tr>

                            <!-- horizontal gap -->
                            <tr><td height="20"></td></tr>

                            <tr>
                                <td mc:edit="text012" align="left" class="center_content" style="font-size: 14px;font-weight: 400; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                    <div class="editable-text">
										<span class="text_container">
											<multiline>
												<a href="#" class="text_color_929292" style="color:#929292; text-decoration: none;">Dynamic Centre</a>
											</multiline>
										</span>
                                    </div>
                                </td>
                            </tr>

                            <!-- horizontal gap -->
                            <tr><td height="10"></td></tr>

                            <tr>
                                <td mc:edit="text013" align="left" class="center_content" style="font-size: 14px;font-weight: 400; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;">
                                    <div class="editable-text">
										<span class="text_container">
											<multiline>
												<a href="#" class="text_color_929292" style="color:#929292; text-decoration: none; display: block;">Powered by 5Star Company</a>
											</multiline>
										</span>
                                    </div>
                                </td>
                            </tr>

                            <!-- margin-bottom -->
                            <tr><td height="30"></td></tr>
                        </table><!-- END column-1 -->

                        <!-- vertical gap -->
                        <table class="tablet_hide" width="130" align="left" border="0" cellspacing="0" cellpadding="0">
                            <tr><td height="1"></td></tr>
                        </table>

                        <!-- column-2  -->
                        <table class="table1-2" width="120" align="right" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                                    <table width="120" align="center" style="margin: 0 auto;">
                                        <tr>
                                            <!-- facebook -->
                                            <td align="center" width="30">
                                                <a href="#" style="border-style: none !important; display: inline-block;; border: 0 !important;" class="editable-img">
                                                    <img editable="true" mc:edit="image007" src="{{$message->embed('assets/images/logo_small.png')}}" width="30" style="display:block; line-height:0; font-size:0; border:0;" border="0" alt="" />
                                                </a>
                                            </td>

                                            <!-- vertical gap -->
                                            <td width="15"></td>

                                            <!-- twitter -->
                                            <td align="center" width="30">
                                                <a href="#" style="border-style: none !important; display: inline-block; border: 0 !important;" class="editable-img">
                                                    <img editable="true" mc:edit="image008" src="{{$message->embed('assets/images/logo_small.png')}}" width="30" style="display:block; line-height:0; font-size:0; border:0;" border="0" alt="" />
                                                </a>
                                            </td>

                                            <!-- vertical gap -->
                                            <td width="15"></td>

                                            <!-- google+ -->
                                            <td align="center" width="30">
                                                <a href="#" style="border-style: none !important; display: inline-block;; border: 0 !important;" class="editable-img">
                                                    <img editable="true" mc:edit="image009" src="{{$message->embed('assets/images/logo_small.png')}}" width="30" style="display:block; line-height:0; font-size:0; border:0;" border="0" alt="" />
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <!-- margin-bottom -->
                            <tr><td height="30"></td></tr>
                        </table><!-- END column-2 -->
                    </td>
                </tr>

                <!-- padding-bottom -->
                <tr><td height="70"></td></tr>
            </table><!-- END container -->
        </td>
    </tr>
</table><!-- END wrapper -->
