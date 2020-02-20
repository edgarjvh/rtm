<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Join my team</title>
    <link href="https://fonts.googleapis.com/css?family=Merriweather:300,300i,400,400i,700,700i,900,900i|Source+Sans+Pro:200,200i,300,300i,400,400i,600,600i,700,700i,900,900i&display=swap"
          rel="stylesheet">
</head>
<body style="padding: 10px">
<table style="width: 90%; max-width:520px;font-family: 'Source Sans Pro', sans-serif;">
    <tr>
        <td>
            <p style="margin: 0; padding: 0;text-align: left; font-family: 'Merriweather', 'serif';font-size: 24px; color: #C96756; font-weight: 900; font-style: italic">
                Join!
            </p>
        </td>
    </tr>
    <tr>
        <td>
            <p style="font-style: italic">
                <span style="color:#C96756;">{{$team_leader_name}}</span> thinks this will interest you, join his/her team at <span style="color:#C96756;">Rate This Meeting</span> and start to gain productivity.
                <br>



            </p>
        </td>
    </tr>

    <tr>
        <td>
            <a href="{{env('APP_URL') . '/join?token=' . $token}}" style="color:#C96756;text-decoration: none;width: 90px;padding: 5px 10px; border: 2px solid #C96756; border-radius: 7px">
                GO FOR IT!
            </a>
            <br>
            <br>
            <br>
        </td>
    </tr>

    <tr>
        <td>
            <img src="{{env('APP_URL') . '/img/logo.png'}}" style="width: 200px;"
                 alt="">
        </td>
    </tr>
    <tr>
        <td>

            <p style="max-width: 520px;  margin: 0; padding: 15px 5px;text-align: justify; font-style: italic; font-family: 'Source Sans Pro', 'sans-serif';font-size: 14px; color: rgba(0,0,0,0.5)">
                This email was sent to you by <a style="color:#C96756;text-decoration: none;" href="{{env('APP_URL') . ''}}">ratethismeeting.com</a>
                if you
                would like to rate your own meetings <a style="color:#C96756;text-decoration: none;"
                                                        href="{{env('APP_URL') . '/register'}}">click
                    here</a>
                to sign up.
            </p>

        </td>
    </tr>

    <tr>
        <td>

            <hr style="max-width: 520px;color: rgba(0,0,0,0.1)">
            <p style="max-width: 520px;  margin: 0; padding: 0 5px;text-align: left; font-style: italic; font-family: 'Source Sans Pro', 'sans-serif';font-size: 14px; color: rgba(0,0,0,0.5)">


                To block your email so that you do not receive emails from ratethismeeting again <a
                        style="color:#C96756;text-decoration: none;" href="{{env('APP_URL') . '/unsubscribe'}}">click here</a>
            </p>


        </td>
    </tr>
</table>
</body>
</html>