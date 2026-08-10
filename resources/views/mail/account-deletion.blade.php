@section('content')
    <table class="table_full" width="100%" align="center" bgcolor="#e6e6e6" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td>
                <table class="table1" bgcolor="#ffffff" width="600" align="center" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
                    <tr><td height="40"></td></tr>
                    <tr>
                        <td align="center" style="font-family: lato, Helvetica, sans-serif;">
                            <h2 style="color: #c0392b; margin: 0;">Account scheduled for deletion</h2>
                        </td>
                    </tr>
                    <tr><td height="20"></td></tr>
                    <tr>
                        <td align="center" style="color: #666666; font-size: 15px; line-height: 1.7; font-family: lato, Helvetica, sans-serif;">
                            Hi {{ $user->first_name }},<br>
                            Your Dynamic Center account has been scheduled for deletion on
                            <strong>{{ $user->deletion_scheduled_for ? $user->deletion_scheduled_for->format('F j, Y') : 'a future date' }}</strong>.<br><br>
                            If this was a mistake, you can cancel the deletion from your account settings
                            at any time before the scheduled date.
                        </td>
                    </tr>
                    <tr><td height="40"></td></tr>
                </table>
            </td>
        </tr>
    </table>
@stop
@extends('mail.footer')
