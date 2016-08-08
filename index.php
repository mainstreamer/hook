<?php

if (isset ($_POST['text'])){
    $city = $_POST['text'];
} else { $city = 'Kiev';};

$curl = curl_init();
$options = [
//        CURLOPT_URL => 'http://api.openweathermap.org/data/2.5/forecast/city?id=703448&APPID=84e19d41375b77b64e5b6c7036aee0e3',
    CURLOPT_URL => 'http://api.openweathermap.org/data/2.5/forecast/weather?q='.$city.'&APPID=84e19d41375b77b64e5b6c7036aee0e3',
        CURLOPT_RETURNTRANSFER => true,
    ];

curl_setopt_array($curl, $options);
$data = curl_exec($curl);
//file_put_contents('weather.txt',$data);
curl_close($curl);

//$data = file_get_contents('weather.txt');
$data = json_decode($data, true, 512);

//var_dump(($data['list']));exit;

$headline = $city.' '.$data['city']['name'].' '.$data['city']['country'].'';
//$limit = count(($data['list']));
$formattedData = [];
foreach ($data['list'] as $key => $record) {
    $formattedData[$key] = date('D H:i',($data['list'][$key]['dt'])).' '.round($record['main']['temp']-273.15).'C '.$record["weather"][0]['description'].' press.: '.round($record['main']['pressure']).' humidity: '.$record['main']['humidity'].'% wind: '.$record['wind']['speed'].' m/s ';
    $formattedData[$key]['icon'] = $record["weather"][0]['icon'];
}


$payload = '{  
"fallback": "Required text summary of the attachment that is shown by clients that understand attachments but choose not to show them.",


"attachments": [
        {
            "fallback": "Weather.",
            "color": "#36a64f",
            "title": "'.$city.' weather",
            "title_link": "https://api.slack.com/",
            "text": "'.$formattedData[0].'",
            "fields": [
                {
                }
            ],
            "image_url": "http://openweathermap.org/img/w/'.$formattedData[0]['icon'].'.png",
            "thumb_url": "http://example.com/path/to/thumb.png",
            "footer": "Slack API",
            "footer_icon": "https://platform.slack-edge.com/img/default_application_icon.png",
            "ts": 123456789
        }
    ],
"text": ""
}';

echo $payload;
/*
$curl = curl_init();
$options = [
    CURLOPT_URL => 'https://hooks.slack.com/services/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',
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $payload,
];

curl_setopt_array($curl, $options);
$data = curl_exec($curl);
curl_close($curl);*/
