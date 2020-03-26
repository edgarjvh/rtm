@extends('layouts.app')

@section('content')
    <?php
    $html = <<<EOD
<table style="width: 502px !important; height: 142px; padding: 0 50px">
<tr>
    <td>
        <div class="score"
             style="
        padding: 10px;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        color: #fff;
        background-color: #E56854;
        font-family: Source Sans Pro, sans-serif;
        font-size: 60px;
        line-height: 100px;
        text-align: center">
            4.2
        </div>
    </td>

    <td>
        <div class="msg"
             style="font-family: Source Sans Pro, sans-serif; display: block; flex-direction: column; padding: 10px;">

            <div class="text">
                This is my <span style="color:#E56854">Meeting Score</span> on <span
                        style="color:#E56854">Rate This Meeting</span><br>
                <span style="font-weight: 600">What's yours?</span>
            </div>

            <div class="logo" style="display: block; text-align: right">
                <img src="https://app.ratethismeeting.com/img/logo.png" alt="" style="width: 200px;">
            </div>
        </div>
    </td>
</tr>
</table>
EOD;

    $data = array('html'=>$html);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://hcti.io/v1/image");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

    curl_setopt($ch, CURLOPT_POST, 1);
    // Retrieve your user_id and api_key from https://htmlcsstoimage.com/dashboard
    curl_setopt($ch, CURLOPT_USERPWD, "8fdb2ae4-66f1-4b89-80c9-786225d1fc03" . ":" . "fc24a5d2-e9c3-4f52-8dd2-87303c10eb32");

    $headers = array();
    $headers[] = "Content-Type: application/x-www-form-urlencoded";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close ($ch);
    $res = json_decode($result,true);

    dd($res);

    ?>
@endsection