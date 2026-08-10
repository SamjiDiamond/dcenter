@section('content')
    <table class="table_full" width="100%" align="center" bgcolor="#e6e6e6" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td>
                <table class="table1" bgcolor="#ffffff" width="600" align="center" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
                    <tr><td height="40"></td></tr>
                    <tr>
                        <td align="center" style="font-family: lato, Helvetica, sans-serif;">
                            <h2 style="color: #303f9f; margin: 0;">Verification Code</h2>
                        </td>
                    </tr>
                    <tr><td height="20"></td></tr>
                    <tr>
                        <td align="center" style="color: #666666; font-size: 15px; line-height: 1.7; font-family: lato, Helvetica, sans-serif;">
                            Hi {{ $user->first_name }},<br>
                            Use the code below to complete enabling two-factor authentication on your account.<br>
                            This code expires in 10 minutes.
                        </td>
                    </tr>
                    <tr><td height="25"></td></tr>
                    <tr>
                        <td align="center">
                            <table bgcolor="#303f9f" width="220" height="55" align="center" cellpadding="0" cellspacing="0" style="background-color:#303f9f; border-radius:6px;">
                                <tr>
                                    <td align="center" style="color: #ffffff; font-size: 26px; font-weight: 700; letter-spacing: 6px; font-family: lato, Helvetica, sans-serif;">
                                        {{ $code }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr><td height="25"></td></tr>
                    <tr>
                        <td align="center" style="color: #a1a2a5; font-size: 13px; font-family: lato, Helvetica, sans-serif;">
                            If you did not request this code, you can safely ignore this email.
                        </td>
                    </tr>
                    <tr><td height="40"></td></tr>
                </table>
            </td>
        </tr>
    </table>
@stop
@extends('mail.footer')
