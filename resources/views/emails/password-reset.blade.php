<!doctype html>
<html lang="en">
<head>
    <title>Email Verification</title>
    <link href="https://fonts.googleapis.com/css?family=Merriweather:300,300i,400,400i,700,700i,900,900i|Source+Sans+Pro:200,200i,300,300i,400,400i,600,600i,700,700i,900,900i&display=swap"
          rel="stylesheet">

    <style type="text/css">
        .btn-password-reset {
            padding: 10px 30px;
            background-color: #C96756;
            color: #fff;
            display: inline-block;
            cursor: pointer;
            transition: all .3s;
            font-weight: bold;
        }

        .btn-password-reset a,
        .btn-password-reset a:visited {
            text-decoration: none;
            color: #fff;
        }

        .btn-password-reset:hover {
            background-color: #b35d5d;
        }
    </style>
</head>
<body style="font-family: 'Source Sans Pro', 'sans-serif'">
<table width="100%">
    <tr>
        <td style="text-align: center; padding: 20px 0">
            <center><img src="{{env('APP_URL') . '/img/logo.png'}}" style="width: 250px;" alt=""></center>
        </td>
    </tr>

    <tr>
        <td style="text-align: center; padding: 0">
            <p style="margin: 0; padding: 0;text-align: center; font-family: 'Merriweather', 'serif';font-size: 60px; color: #C96756; font-weight: 900; font-style: italic">
                Hello!
            </p>
        </td>
    </tr>

    <tr>
        <td>
            <center>
                <p style="max-width: 520px;  margin: 0; padding: 15px 5px;text-align: center; font-family: 'Source Sans Pro', 'sans-serif';font-size: 20px; color: rgba(0,0,0,0.5)">
                    You are receiving this email because we received a password reset request for your account.
                </p>
            </center>
        </td>
    </tr>
    <tr>
        <td style="text-align: center; padding: 5px 0">
            <div class="btn-password-reset">
                <a href="{{env('APP_URL').'/password/resetting/'.$user->email.'/'.$user->password_token}}">RESET PASSWORD</a>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <center>
                <p style="max-width: 520px;  margin: 0; padding: 15px 5px;text-align: center; font-family: 'Source Sans Pro', 'sans-serif';font-size: 20px; color: rgba(0,0,0,0.5)">
                    This password reset link will expire in 60 minutes.
                </p>
            </center>
        </td>
    </tr>
    <tr>
        <td>
            <center>
                <p style="max-width: 520px;  margin: 0; padding: 15px 5px;text-align: center; font-family: 'Source Sans Pro', 'sans-serif';font-size: 20px; color: rgba(0,0,0,0.5)">
                    If you did not request a password reset, no further action is required.
                </p>
            </center>
        </td>
    </tr>

    <tr>
        <td style="text-align: center; padding: 0">
            <center>
                <p style="max-width: 520px;  margin: 0; padding: 15px 5px;text-align: left; font-family: 'Source Sans Pro', 'sans-serif';font-size: 20px; color: rgba(0,0,0,0.5)">
                    Regards, <br>
                    RateThisMeeting
                </p>
            </center>

        </td>
    </tr>
    <tr>
        <td>
            <center>
                <hr style="max-width: 520px;color: rgba(0,0,0,0.1)">
                <p style="max-width: 520px;  margin: 0; padding: 0 5px;text-align: center; font-family: 'Source Sans Pro', 'sans-serif';font-size: 14px; color: rgba(0,0,0,0.5)">


                    If you're having trouble clicking the "Reset Password" button, <br>
                    copy and paste the URL below into your web browser: <span
                            style="color: #C96756;">{{env('APP_URL').'/password/resetting/'.$user->email.'/'.$user->password_token}}</span>
                </p>


            </center>


        </td>
    </tr>
</table>
</body>
</html>