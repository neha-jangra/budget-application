<body style="margin: 0px; font-family: 'Inter', sans-serif;">
    <table border="0" cellspacing="0" cellpadding="0" width="600" style="width: 600px;max-width:600px; margin: 0 auto;border-collapse: collapse;" align="center">
        <tbody><tr>
            <td>
                <table border="0" cellspacing="0" cellpadding="0" width="100%" style="width: 100%;background-color: #101828;  border-collapse: collapse;" align="center">
                    <tbody><tr>
                        <td style="height: 28px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="width: 100%;">
                            <img src="{{ asset('/images/email-logo.png') }}" alt="logo" style="margin: 0 auto; display: table; height:74px">
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 28px;">&nbsp;</td>
                    </tr>
                </tbody></table>
            </td>
        </tr>
        <tr>
            <td>
                <table border="0" cellspacing="0" cellpadding="0" width="100%"
                    style="width: 100%; border-collapse: collapse; " align="center">
                    <tr>
                        <td>
                            <h1
                                style="font-size: 20px;font-weight: 600;color:#2D3A43;text-align: left;margin: 32px 0px 5px;line-height:25px;">
                                Hey, {{ $data['name'] }}</h1>
                        </td>

                    </tr>
                    <tr>
                        <td>
                            <p
                                style="font-size: 14px;font-weight: 400;color: #2D3A43; margin-top:0px; margin-bottom: 25px;line-height:19px;">
                                You recently requested to reset your password.</p>
                            <p
                                style="font-size: 14px;font-weight: 400;color: #2D3A43;margin-top:0px;margin-bottom: 7px;line-height:19px;">
                                Click on <a
                                            href="{{ $data['link']}}">Reset
                                            Password</a> link, you will be prompted to enter the verification code provided below:</p>
                            <p
                                style="font-size: 14px;font-weight: 400;color: #2D3A43; margin-top:0px; margin-bottom: 7px;line-height:19px;">
                                Verification Code: <span style="font-weight: 600;">{{$data['code']}}</span>
                            </p>

                            <p
                                style="font-size: 14px;font-weight: 400;color: #2D3A43; margin-top:0px; margin-bottom: 25px;line-height: 19px;">
                                Please note that the verification code is valid for the next 24 hours. If you do not reset your password within this timeframe, you may need to initiate a new password reset request.</p>
                            <p
                                style="font-size: 14px;font-weight: 400;color: #2D3A43; margin-top:0px; margin-bottom: 0px;line-height: 19px;">
                                Kind regards,
                            </p>
                            <p
                                style="font-size: 14px;font-weight: 400;color: #2D3A43; margin-top:0px; margin-bottom: 0px;line-height: 19px;">
                                ECNL
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:32px">&nbsp; </td>
                    </tr>
                    <tr>
                        <td style="background: #101828; padding: 15px 20px;">
                            <table border="0" cellspacing="0" cellpadding="0" width="100%"
                                style="width: 100%;  border-collapse: collapse;" align="center">

                                <tr style=" border-collapse: collapse;">
                                    <td style="text-align: center; border-collapse: collapse;color:#fff;">
                                        Reset Password
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </tbody></table>
    <table class="gmail-app-fix">
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0" border="0" align="center" width="600">
                    <tr>
                        <td cellpadding="0" cellspacing="0" border="0" height="1"; style="line-height: 1px; min-width: 600px;">
                            <img width="200" height="1" style="display: block; max-height: 1px; min-height: 1px; min-width: 600px; width: 600px;"/>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>