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
<body>
<div class="rating">

    <a href="#">
        <img class="star-on" src="http://ratemymeeting.webilation.com/img/ratingstar_on.png" alt="">
        <img class="star-off" src="http://ratemymeeting.webilation.com/img/ratingstar_off.png" alt="">

        <div>
            5
        </div>
    </a>

    <a href="#">
        <img class="star-on" src="http://ratemymeeting.webilation.com/img/ratingstar_on.png" alt="">
        <img class="star-off" src="http://ratemymeeting.webilation.com/img/ratingstar_off.png" alt="">

        <div>
            4
        </div>
    </a>

    <a href="#">
        <img class="star-on" src="http://ratemymeeting.webilation.com/img/ratingstar_on.png" alt="">
        <img class="star-off" src="http://ratemymeeting.webilation.com/img/ratingstar_off.png" alt="">

        <div>
            3
        </div>
    </a>

    <a href="#">
        <img class="star-on" src="http://ratemymeeting.webilation.com/img/ratingstar_on.png" alt="">
        <img class="star-off" src="http://ratemymeeting.webilation.com/img/ratingstar_off.png" alt="">

        <div>
            2
        </div>
    </a>

    <a href="#">
        <img class="star-on" src="http://ratemymeeting.webilation.com/img/ratingstar_on.png" alt="">
        <img class="star-off" src="http://ratemymeeting.webilation.com/img/ratingstar_off.png" alt="">

        <div>
            1
        </div>
    </a>

</div>
</body>
</html>
