<h1 align="center"> 阿里云内容安全检测 </h1>


## Installing

```shell
$ composer require lmdfx/aliyun-green -vvv
```
## Service Provider
```shell
//file app.php in array providers :
Lmdfx\AliyunGreen\AliyunGreenServiceProvider::class,
```
## Aliases
```shell
//file app.php in array aliases :
'AliyunGreen' => \Lmdfx\AliyunGreen\AliyunGreenFacade::class,
```

## config
```shell
//复制配置文件到config目录下
php artisan vendor:publish --provider=Lmdfx\AliyunGreen\AliyunGreenServiceProvider
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
];

```
## Usage
```php
<?php
use Illuminate\Support\Facades\Route;

//文本检查
Route::get('test', function() {
    return AliyunGreen::greenTextScan('test');
});
//会直接返回阿里云检查结果
{
    "code": 200,
    "data": [
        {
            "code": 200,
            "content": "test",
            "msg": "OK",
            "results": [
                {
                    "label": "normal",
                    "rate": 99.91,
                    "scene": "antispam",
                    "suggestion": "pass"
                }
            ],
            "taskId": "txt4wP0KSIBQpj5rV7E9FiQd1-1v6R5I"
        }
    ],
    "msg": "OK",
    "requestId": "C4E1EC85-C773-5833-85EA-EDE3CFCC9282"
}
-------
//图片检查
Route::get('test', function() {
    // return AliyunGreen::greenTextScan('test');
    $url = 'https://images.pexels.com/photos/57440/cherry-blossom-blossom-bloom-spring-57440.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500';
    return AliyunGreen::greenImageScan($url);
});
{
    "code": 200,
    "data": [
        {
            "code": 200,
            "extras": {},
            "msg": "OK",
            "results": [
                {
                    "label": "normal",
                    "rate": 99.9,
                    "scene": "porn",
                    "suggestion": "pass"
                },
                {
                    "label": "normal",
                    "rate": 100.0,
                    "scene": "terrorism",
                    "suggestion": "pass"
                },
                {
                    "label": "normal",
                    "rate": 99.91,
                    "scene": "ad",
                    "suggestion": "pass"
                },
                {
                    "label": "normal",
                    "rate": 99.91,
                    "scene": "qrcode",
                    "suggestion": "pass"
                },
                {
                    "label": "normal",
                    "rate": 99.91,
                    "scene": "live",
                    "suggestion": "pass"
                },
                {
                    "label": "normal",
                    "rate": 99.9,
                    "scene": "logo",
                    "suggestion": "pass"
                }
            ],
            "taskId": "img7f0eyk7rH3R4ij3wtoW6n7-1v6Rbu",
            "url": "https://images.pexels.com/photos/57440/cherry-blossom-blossom-bloom-spring-57440.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500"
        }
    ],
    "msg": "OK",
    "requestId": "FEEF2A57-092F-57CA-804A-CA0FE8EE3E94"
}

//视频检测,仅支持异步检测，拿到taskId去获取检查结果
Route::get('test', function() {
    // return AliyunGreen::greenTextScan('test');
    // $url = 'https://images.pexels.com/photos/57440/cherry-blossom-blossom-bloom-spring-57440.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500';
    // return AliyunGreen::greenImageScan($url);
    $videurl='https://look.thinksns.com/storage/public:MjAyMS8xMC8xMS94MzhCVnZiejJhUk02MG9Sa1hWdTlHM2ZuQmVUb2x6WWJ6QXlYYkdzQ0VJMmhtVENNTlBTMW9pOUhBZ0RRMnVPLm1wNA==';
    return AliyunGreen::greenVideoScan($videurl);
});
{
    "code": 200,
    "data": [
        {
            "code": 200,
            "msg": "OK",
            "taskId": "vi76GKuEkUkfu7P5nK0YgXKq-1v6Rfr",
            "url": "https://look.thinksns.com/storage/public:MjAyMS8xMC8xMS94MzhCVnZiejJhUk02MG9Sa1hWdTlHM2ZuQmVUb2x6WWJ6QXlYYkdzQ0VJMmhtVENNTlBTMW9pOUhBZ0RRMnVPLm1wNA=="
        }
    ],
    "msg": "OK",
    "requestId": "25492596-3A71-5601-B362-16CA2003B3C6"
}


//通过taskId获取视频检测结果
Route::get('test', function() {
    // return AliyunGreen::greenTextScan('test');
    // $url = 'https://images.pexels.com/photos/57440/cherry-blossom-blossom-bloom-spring-57440.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500';
    // return AliyunGreen::greenImageScan($url);
    // $videurl='https://look.thinksns.com/storage/public:MjAyMS8xMC8xMS94MzhCVnZiejJhUk02MG9Sa1hWdTlHM2ZuQmVUb2x6WWJ6QXlYYkdzQ0VJMmhtVENNTlBTMW9pOUhBZ0RRMnVPLm1wNA==';
    // return AliyunGreen::greenVideoScan($videurl);
    return AliyunGreen::getVideAsyncScanResult('vi76GKuEkUkfu7P5nK0YgXKq-1v6Rfr');
});

{
    "code": 200,
    "data": [
        {
            "code": 200,
            "extras": {
                "frameCount": "9",
                "framePrefix": "http://aligreen-beijing.oss-cn-beijing-internal.aliyuncs.com/prod/hammal/111222906/29877421_public3AMjAyMS8xMC8xMS94MzhCVnZiejJhUk02MG9Sa1hWdTlHM2ZuQmVUb2x6WWJ6QXlYYkdzQ0VJMmhtVENNTlBTMW9pOUhBZ0RRMnVPLm1wNA3D3D-frames/f0000"
            },
            "msg": "OK",
            "results": [
                {
                    "label": "normal",
                    "rate": 99.9,
                    "scene": "porn",
                    "suggestion": "pass"
                },
                {
                    "label": "normal",
                    "rate": 99.9,
                    "scene": "terrorism",
                    "suggestion": "pass"
                },
                {
                    "label": "normal",
                    "rate": 99.9,
                    "scene": "ad",
                    "suggestion": "pass"
                },
                {
                    "label": "normal",
                    "rate": 99.9,
                    "scene": "live",
                    "suggestion": "pass"
                },
                {
                    "label": "normal",
                    "rate": 99.9,
                    "scene": "logo",
                    "suggestion": "pass"
                }
            ],
            "taskId": "vi76GKuEkUkfu7P5nK0YgXKq-1v6Rfr",
            "url": "https://look.thinksns.com/storage/public:MjAyMS8xMC8xMS94MzhCVnZiejJhUk02MG9Sa1hWdTlHM2ZuQmVUb2x6WWJ6QXlYYkdzQ0VJMmhtVENNTlBTMW9pOUhBZ0RRMnVPLm1wNA=="
        }
    ],
    "msg": "OK",
    "requestId": "5ABB0CF1-D439-528A-A90B-F1BDBEE851FD"
}
```

## License

MIT