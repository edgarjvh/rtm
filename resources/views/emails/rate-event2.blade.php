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
<table style="width: 90%; max-width:520px;font-family: 'Source Sans Pro', sans-serif;">
    <tr>
        <td style="text-align: center">
            <img src="[APP_URL]/img/logo.png" style="width: 250px;margin-bottom: 20px"
                 alt="">
        </td>
    </tr>
    <tr>
        <td>
            <p style="margin: 0; padding: 0;text-align: left; font-family: 'Merriweather', 'serif';font-size: 60px; color: #C96756; font-weight: 900; font-style: italic">
                Hello!
            </p>
        </td>
    </tr>
    <tr>
        <td>
            <p style="max-width: 520px;  margin: 0; padding: 15px 5px;text-align: justify; font-family: 'Source Sans Pro', 'sans-serif';font-size: 20px; color: rgba(0,0,0,0.5)">
                you are receiving this email because the host of a recent meeting you attended would like an anonymous
                rating of the meeting. Please rate this meeting 1 - 5 where 1 is very poor and 5 is excellent
            </p>

        </td>
    </tr>

    <tr>
        <td>
            <div style="margin-bottom: 10px;"></div>
        </td>
    </tr>

    <tr>
        <td align="center">

            <div class="rating">
                <a href="[APP_URL]/rating/[RATING_KEY]/[EVENT_ID]/5'}}">
                    <img class="star-on" src="[APP_URL]/img/ratingstar_on.png" alt="">
                    <img class="star-off" src="[APP_URL]/img/ratingstar_off.png" alt="">

                    <div>
                        5
                    </div>
                </a>

                <a href="[APP_URL]/rating/[RATING_KEY]/[EVENT_ID]/4'}}">
                    <img class="star-on" src="[APP_URL]/img/ratingstar_on.png" alt="">
                    <img class="star-off" src="[APP_URL]/img/ratingstar_off.png" alt="">

                    <div>
                        4
                    </div>
                </a>

                <a href="[APP_URL]/rating/[RATING_KEY]/[EVENT_ID]/3'}}">
                    <img class="star-on" src="[APP_URL]/img/ratingstar_on.png" alt="">
                    <img class="star-off" src="[APP_URL]/img/ratingstar_off.png" alt="">

                    <div>
                        3
                    </div>
                </a>

                <a href="[APP_URL]/rating/[RATING_KEY]/[EVENT_ID]/2'}}">
                    <img class="star-on" src="[APP_URL]/img/ratingstar_on.png" alt="">
                    <img class="star-off" src="[APP_URL]/img/ratingstar_off.png" alt="">

                    <div>
                        2
                    </div>
                </a>

                <a href="[APP_URL]/rating/[RATING_KEY]/[EVENT_ID]/1'}}">
                    <img class="star-on" src="[APP_URL]/img/ratingstar_on.png" alt="">
                    <img class="star-off" src="[APP_URL]/img/ratingstar_off.png" alt="">

                    <div>
                        1
                    </div>
                </a>

            </div>

        </td>
    </tr>

    <tr>
        <td>

            <p style="max-width: 520px;  margin: 0; padding: 15px 5px;text-align: justify; font-family: 'Source Sans Pro', 'sans-serif';font-size: 20px; color: rgba(0,0,0,0.5)">
                This email was sent to you by <a style="color:#C96756;text-decoration: none;" href="[APP_URL]/">ratethismeeting.com</a>
                if you
                would like to rate your own meetings <a style="color:#C96756;text-decoration: none;"
                                                        href="[APP_URL]/register">click
                    here</a>
                to sign up.
            </p>

        </td>
    </tr>


    <tr>
        <td>

            <hr style="max-width: 520px;color: rgba(0,0,0,0.1)">
            <p style="max-width: 520px;  margin: 0; padding: 0 5px;text-align: center; font-family: 'Source Sans Pro', 'sans-serif';font-size: 14px; color: rgba(0,0,0,0.5)">


                To block your email so that you do not receive emails from ratethismeeting again <a
                        style="color:#C96756;text-decoration: none;" href="[APP_URL]/unsubscribe">click here</a>
            </p>


        </td>
    </tr>
</table>
</body>
</html>