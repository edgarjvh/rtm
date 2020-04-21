<!DOCTYPE html>
<html lang="en">
<head>
    <title>Rate this meeting</title>

    <link href="https://fonts.googleapis.com/css?family=Merriweather:300,300i,400,400i,700,700i,900,900i|Source+Sans+Pro:200,200i,300,300i,400,400i,600,600i,700,700i,900,900i&display=swap"
          rel="stylesheet">

    <style type="text/css">

        .rating {
            unicode-bidi: bidi-override;
            direction: rtl;
        }

        .rating > a{
            width: 30px;
            text-decoration: none;
            display: inline-block;
            color: rgba(0,0,0,0.6);
            font-size: 20px;
            font-style: normal;
            font-weight: 400;
        }

        .rating > a > img{
            width: 100%;
        }

        .rating > a > img.star-on{
            display: none;
        }

        .rating > a > div{
            font-family: 'Source Sans Pro', 'sans-serif';
            width: 100%;
            text-align: center;
        }

        .rating > a:hover > img.star-off,
        .rating > a:hover ~ a > img.star-off {
            display: none;
        }
        .rating > a:hover > img.star-on,
        .rating > a:hover ~ a > img.star-on {
            display: inline;
        }

    </style>
</head>
<body style="padding: 10px">
<table style="width: 90%; max-width:520px;font-family: 'Source Sans Pro', sans-serif;margin:0 auto">
    <tr>
        <td style="text-align: center">
            <img src="{{env('APP_URL') . '/img/logo.png'}}" style="width: 250px;margin-bottom: 20px"
                 alt="">
        </td>
    </tr>
    <tr>
        <td>
            <p style="margin: 0; padding: 0;text-align: center; font-family: 'Merriweather', 'serif';font-size: 60px; color: #C96756; font-weight: 900; font-style: italic">
                Hello!
            </p>
        </td>
    </tr>
    <tr>
        <td>
            <p style="max-width: 520px;  margin: 0; padding: 15px 5px;text-align: justify; font-family: 'Source Sans Pro', 'sans-serif';font-size: 18px; color: rgba(0,0,0,0.5)">
                Hey, someone is rating your meetings, see what's going on.
                <br>
                We have an account ready for you!

            </p>

        </td>
    </tr>

    <tr>
        <td>
            <div style="margin-bottom: 10px;"></div>
        </td>
    </tr>

    <tr>
        <td style="text-align: center; ">

            <a href="{{env('APP_URL') . '/create?th=' . $token_host}}" style="color:#C96756;text-decoration: none;width: 90px;padding: 5px 20px; border: 2px solid #C96756; border-radius: 7px">
                LOG IN
            </a>

        </td>
    </tr>


    <tr>
        <td>

            <hr style="max-width: 520px;color: rgba(0,0,0,0.1);margin-top: 20px">
            <p style="max-width: 520px;  margin: 0; padding: 0 5px;text-align: center; font-family: 'Source Sans Pro', 'sans-serif';font-size: 14px; color: rgba(0,0,0,0.5)">


                To block your email so that you do not receive emails from ratethismeeting again <a
                        style="color:#C96756;text-decoration: none;" href="{{env('APP_URL') . '/unsubscribe'}}">click here</a>
            </p>


        </td>
    </tr>
</table>
</body>
</html>
