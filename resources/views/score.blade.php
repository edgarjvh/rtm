<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Meeting Score</title>
</head>
<body style="margin: 0;padding: 0; box-sizing: border-box">
    <div class="meeting-score-container"
         style="display: flex; padding: 10px; border: 1px solid rgba(0,0,0,0.2);width: 480px">

        <div class="score"
        style="padding: 10px;width: 100px; height: 100px; border-radius: 50%; color: #fff; background-color: #E56854;display: flex; justify-content: center; align-items: center; font-family: Source Sans Pro, sans-serif; font-size: 60px">
            {{$score}}
        </div>
        
        <div class="msg" style="font-family: Source Sans Pro, sans-serif; display: flex; flex-direction: column; justify-content: space-between;padding: 10px;flex-grow: 1">
            <div class="text">
                This is my <span style="color:#E56854">Meeting Score</span> on <span style="color:#E56854">Rate This Meeting</span><br>
                <span style="font-weight: 600">What's yours?</span>
            </div>
            
            <div class="logo" style="display: flex; justify-content: flex-end">
                <img src="{{asset('img/logo.png')}}" alt="" style="width: 200px;">
            </div>
        </div>
    </div>
</body>
</html>