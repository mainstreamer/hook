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
//echo '<pre>';
//var_dump(($data['list'][0]['weather'][0]['icon']));exit;

$headline = $city.' '.$data['city']['name'].' '.$data['city']['country'].'';
//$limit = count(($data['list']));
$formattedData = [];
$icon = [];
foreach ($data['list'] as $key => $record) {
    $temp = round($record['main']['temp']-273.15);
    if ($temp > 0) {$temp = '+'.$temp;}
    $date = date('D H:i',($data['list'][$key]['dt']));
    $formattedData[$key] = $date.' '.$temp.'C, '.$record["weather"][0]['description'].', humidity: '.$record['main']['humidity'].'%, '.' press.: '.round($record['main']['pressure']).' kPa, wind: '.$record['wind']['speed'].' m/s ';
    $icon[] = $record["weather"][0]['icon'];
}
//echo $formattedData[0]['icon']; exit;

$attachments = '';
foreach ($formattedData as $k => $v ) {
    if ($k < 5) {
    $attachments.='
    {
        "fallback": "Weather.",
            "color": "#36a64f",
            "title": "'.$city.' weather",
            "title_link": "https://api.slack.com/",
            "text": "'.$v.'",
            "fields": [
                {
                    "text" : "'.$icon[$k].'"
                }
            ],
            "image_url": "http://openweathermap.org/img/w/'.$icon[$k].'.png"
    },';
    }
}

$attachments = substr($attachments,0,strlen($attachments)-1);


$payload = '{  
"fallback": "Required text summary of the attachment that is shown by clients that understand attachments but choose not to show them.",


"attachments": ['.$attachments.'
        
    ],
"text": "'.$formattedData[39].'"
}';


$curl = curl_init();
$options = [
    CURLOPT_URL => 'https://hooks.slack.com/services/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',
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $payload,
];

curl_setopt_array($curl, $options);
$data = curl_exec($curl);
curl_close($curl);