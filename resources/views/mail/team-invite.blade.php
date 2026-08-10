@section('content')
    <table class="table_full" width="100%" align="center" bgcolor="#e6e6e6" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td>
                <table class="table1" bgcolor="#ffffff" width="600" align="center" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
                    <tr><td height="40"></td></tr>
                    <tr>
                        <td align="center" style="font-family: lato, Helvetica, sans-serif;">
                            <h2 style="color: #303f9f; margin: 0;">You have been added to a team</h2>
                        </td>
                    </tr>
                    <tr><td height="20"></td></tr>
                    <tr>
                        <td align="center" style="color: #666666; font-size: 15px; line-height: 1.7; font-family: lato, Helvetica, sans-serif;">
                            Hi {{ $user->first_name }},<br>
                            An account has been created for you on Dynamic Center.<br>
                            Use the credentials below to sign in:
                        </td>
                    </tr>
                    <tr><td height="25"></td></tr>
                    <tr>
                        <td align="center">
                            <table bgcolor="#f4f6fb" width="360" align="center" cellpadding="14" cellspacing="0" style="border-radius:6px;">
                                <tr>
                                    <td style="font-family: lato, Helvetica, sans-serif; font-size: 14px; color: #333333;">
                                        <strong>Email:</strong> {{ $user->email }}<br>
                                        <strong>Temporary password:</strong> {{ $password }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr><td height="20"></td></tr>
                    <tr>
                        <td align="center" style="color: #a1a2a5; font-size: 13px; font-family: lato, Helvetica, sans-serif;">
                            Please change your password after your first login.
                        </td>
                    </tr>
                    <tr><td height="40"></td></tr>
                </table>
            </td>
        </tr>
    </table>
@stop
@extends('mail.footer')
