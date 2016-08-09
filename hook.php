<?php

$payload = '{  
"fallback": "Required text summary of the attachment that is shown by clients that understand attachments but choose not to show them.",



"attachments": [
        {
            "fallback": "Required plain-text summary of the attachment.",
            "color": "#36a64f",
            "pretext": "Weather forecast",
            "author_name": "weatherbot",
            "author_link": "http://flickr.com/bobby/",
            "author_icon": "http://flickr.com/icons/bobby.jpg",
            "title": "Kiev weather",
            "title_link": "https://api.slack.com/",
            "text": "+ 19",
            "fields": [
                {
                    "title": "XAXA",
                    "value": "yes",
                    "short": false
                }
            ],
            "image_url": "http://openweathermap.org/img/w/10d.png",
            "thumb_url": "http://example.com/path/to/thumb.png",
            "footer": "Slack API",
            "footer_icon": "https://platform.slack-edge.com/img/default_application_icon.png",
            "ts": 123456789
        }
    ],
"text": ""
}';

$curl = curl_init();
$options = [
        CURLOPT_URL => 'https://hooks.slack.com/services/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',
//        CURLOPT_URL => 'hooks.slack.com/commands/1234/5678',
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $payload,
    ];

curl_setopt_array($curl, $options);
$data = curl_exec($curl);
curl_close($curl);

