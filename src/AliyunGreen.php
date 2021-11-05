<?php

namespace Lmdfx\AliyunGreen;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use AlibabaCloud\Client\Result\Result;


class AliyunGreen
{

    protected static $client;

    protected $accessKeyId,$accessKeySecret,$regionId,$debug,$timeout,$connectTimeout,$enable, $init_error;


    public function __construct()
    {
        $this->accessKeyId = config('ali-green.access_key_id');
        $this->accessKeySecret = config('ali-green.access_key_secret');
        $this->regionId = config('ali-green.region_id','cn-beijing');
        $this->debug = config('ali-green.debug',false);
        $this->timeout = config('ali-green.timeout',6);
        $this->connectTimeout = config('ali-green.connect_timeout',10);
        $this->enable = config('ali-green.enable',true);

        try {
            AlibabaCloud::accessKeyClient($this->accessKeyId, $this->accessKeySecret)
                ->regionId($this->regionId)
                ->timeout($this->timeout)
                ->debug($this->debug)
                ->connectTimeout($this->connectTimeout)// 连接超时10秒
                ->asDefaultClient();
        } catch (ClientException $e) {
            $this->init_error =  $e->getErrorMessage();
        }
    }

    /**
     * 发送检测请求
     * @param $path
     * @param $body
     * @throws ClientException
     * @throws ServerException
     *
     */
    protected function sendQuery($path, $body, $method = 'POST')
    {
        if( $this->init_error ) {
            return ['message' => $this->init_error, 'code' => 403];
        }

        try{
            return AlibabaCloud::roa()
                ->product('Green')
                ->version('2018-05-09')
                ->pathPattern($path)
                ->method($method)
                ->body(json_encode($body))
                ->request();
        } catch (ClientException | ServerException $exception) {
            return ['message' => $exception->getErrorMessage(), 'code' => 403];
        }

    }

    /**
     * 文本垃圾内容检测
     * @param $text
     * @return array
     */
    public function greenTextScan($text)
    {

        if(!$this->enable){
            return ['message' => '未开启检测', 'code' => 403];
        }
        $body = [
            'bizType' => config('ali-green.bizType'),
            'scenes' => 'antispam',
            'tasks' => [
                'content' => $text
            ]
        ];

        return $this->sendQuery('/green/text/scan',$body);
    }

    /**
     * 检测图片
     * @param $url
     * @param  string  $type async-异步 sync -同步
     * @return array
     */
    public function greenImageScan($url)
    {
        if(!$this->enable){
            return ['message' => '未开启检测', 'code' => 403];
        }
        $scenes = config('ali-green.image_scan_scenes');

        $body = [
            'bizType' => config('ali-green.bizType'),
            'scenes' => $scenes,
            'tasks' => [
                'url' => $url,//待检测图片url
            ]
        ];
        $path = '/green/image/scan';

        return $this->sendQuery($path,$body);

    }

    /**
     * 视频检测 -只能异步
     * @param $url
     * @return array
     */
    public function greenVideoScan($url)
    {
        if(!$this->enable){
            return ['message' => '未开启检测', 'code' => 403];
        }
        $scenes = config('ali-green.video_scan_scenes');

        $callback = config('ali-green.video_callback');

        $body = [
            'bizType' => config('ali-green.bizType'),
            'scenes' => $scenes,
            'tasks' => [
                'url' => $url,//待检测图片url
            ],
            'callback' => $callback,//检测结果回调通知
            'seed' => config('ali-green.seed'), //随机字符串，该值用于回调通知请求中的签名。
        ];

        if (config('ali-green.audio_scenes')) {
            $body['audioScenes'] = 'antispam';
        }

        $path = '/green/video/asyncscan';

        return $this->sendQuery($path,$body);

    }


    /**
     * 获取视频异步检测结果
     * @param $taskId
     * @return Result|array
     * @throws ServerException
     * @throws ClientException
     */
    public function getVideAsyncScanResult($taskId)
    {

        $path = '/green/video/results';
        $body = [
            $taskId
        ];

        $result = $this->sendQuery($path,$body);

        if($result['data'][0]['code'] === 280){
            //正在检测中
            sleep(5);
            return $this->getVideAsyncScanResult($taskId);
        }

        return $result;
    }
}
