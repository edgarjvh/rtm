<!DOCTYPE html>
<html lang="en">
<head>
    <title>Rate this meeting</title>

    <link href="https://fonts.googleapis.com/css?family=Merriweather:300,300i,400,400i,700,700i,900,900i|Source+Sans+Pro:200,200i,300,300i,400,400i,600,600i,700,700i,900,900i&display=swap"
          rel="stylesheet">

    <style type="text/css">

        #tbl-main {
            width: 100%;
            max-width: 800px;
            font-family: 'Source Sans Pro', sans-serif;
            margin: 0 auto
        }

        p.lg-title {
            margin: 0;
            padding: 0;
            text-align: left;
            font-family: 'Merriweather', 'serif';
            font-size: 60px;
            color: #C96756;
            font-weight: 900;
            font-style: italic
        }

        p {
            margin: 0;
            padding: 0;
            text-align: justify;
            font-family: 'Merriweather', 'serif';
            font-size: 16px;
            font-weight: lighter;
            font-style: italic;
        }

        p .bolder-text {
            color: #000;
            text-decoration: none;
            font-weight: bold;
        }

        #tbl-meeting-info {
            width: 60%;
            margin: 10px auto;
        }

        #tbl-meeting-info td {
            min-width: 33%;
            font-family: 'Merriweather', 'serif';
            font-size: 16px;
            font-weight: lighter;
            font-style: italic;
        }

        #tbl-meeting-info td.row {
            color: #C96756;
            font-weight: bold;
        }

        .rtm-logo {
            width: 190px;
        }

        a.rating {
            color: #C96756;
            direction: rtl;
            border: 2px solid #C96756;
            border-radius: 40px;
            padding: 7px 20px;
            background-color: #fff;
            width: 190px;
            text-decoration: none;

        }

        a.rating:visited{
            color: #C96756;
        }

        /*.rating > a {*/
            /*width: 30px;*/
            /*text-decoration: none;*/
            /*display: inline-block;*/
            /*color: rgba(0, 0, 0, 0.6);*/
            /*font-size: 20px;*/
            /*font-style: normal;*/
            /*font-weight: 400;*/
        /*}*/

        /*.rating > a > img {*/
            /*width: 100%;*/
        /*}*/

        /*.rating > a > img.star-on {*/
            /*display: none;*/
        /*}*/

        /*.rating > a > div {*/
            /*font-family: 'Source Sans Pro', 'sans-serif';*/
            /*width: 100%;*/
            /*text-align: center;*/
        /*}*/

        /*.rating > a:hover > img.star-off,*/
        /*.rating > a:hover ~ a > img.star-off {*/
            /*display: none;*/
        /*}*/

        /*.rating > a:hover > img.star-on,*/
        /*.rating > a:hover ~ a > img.star-on {*/
            /*display: inline;*/
        /*}*/

        @media screen and (max-width: 480px){
            .rtm-logo{
                width: 300px;
            }
        }


    </style>
</head>
<body style="padding: 0; margin: 0">
<table border="0" cellpadding="0" cellspacing="0" id="tbl-main">

    <tr>
        <td>
            <p class="lg-title">
                Hello!
            </p>
        </td>
    </tr>
    <tr>
        <td>
            <p style="padding: 30px 0 20px 0">
                Hey, someone is rating your meetings, see what's going on.
            </p>

        </td>
    </tr>

    <tr style="background-color: #354c58">
        <td style="padding-top: 15px">
            <p style="color: #fff; text-align: center; padding: 10px 15px 20px 15px; font-weight: bold">
                Would you provide an anonymous rating of the meeting?
            </p>
        </td>
    </tr>

    <tr style="background-color: #354c58">
        <td align="center">

            <div style="padding-bottom: 30px; padding-top: 5px">
                <a href="{{env('APP_URL') . '/create?th=' . $token_host}}" class="rating">
                    LOG IN
                </a>
            </div>
        </td>
    </tr>

    <tr>
        <td style="text-align: center; padding: 60px 0">
            <img src="{{env('APP_URL') . '/img/logo.png'}}" class="rtm-logo" alt="">
        </td>
    </tr>

    <tr>
        <td>

            <p>
                This email was sent to you by <a style="color:#C96756;text-decoration: none; font-weight: bold"
                                                 href="{{env('APP_URL') . ''}}">ratethismeeting.com</a>
                if you would like to rate your own meetings <a class="bolder-text"
                                                               href="{{env('APP_URL') . '/register'}}">click
                    here</a>
            </p>
            <p>
                To block your email so that you do not receive emails from ratethismeeting again <a class="bolder-text"
                                                                                                    href="{{env('APP_URL') . '/unsubscribe'}}">click here</a>
            </p>
        </td>
    </tr>

</table>
</body>
</html>
