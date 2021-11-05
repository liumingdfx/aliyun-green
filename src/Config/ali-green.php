<?php
return [
    //是否开启检测
    'enable' => true,

    'debug' => false,

    'timeout' => 6,

    'connect_timeout' => 10,

    'access_key_id' => '',

    'access_key_secret' => '',

    'region_id' => 'cn-beijing',

    //图片检测，需要的场景
    'image_scan_scenes' => [
        'porn',//色情
        'terrorism',//暴恐涉政
        'ad',//广告
        'qrcode',//二维码
        'live',//不良场景
        'logo',//logo
    ],

    //视频检测，需要的场景
    'video_scan_scenes' => [
        'porn',//色情
        'terrorism',//暴恐涉政
        'ad',//广告
        'live',//不良场景
        'logo',//logo
    ],

    'audio_scenes' => false, //true- 检测视频里面的语音是否违规，false-不检测视频里面的语音。

    'video_callback' => 'https://xxx.com/', //视频检查回调

    'seed' =>'66c7f0f462eeedd9', //随机字符串，该值用于回调通知请求中的签名。

    'uid' => 'xxxx', //主账号UID

    'bizType' => 'default',
];
