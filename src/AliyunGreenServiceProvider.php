<?php

namespace Lmdfx\AliyunGreen;

use Illuminate\Support\ServiceProvider;
class AliyunGreenServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function boot(){
        $this->publishes([
            __DIR__.'/Config/ali-green.php' => config_path('ali-green.php'),// 发布配置文件到 laravel 的config下
        ]);
    }

    public function register()
    {
        $this->app->singleton(AliyunGreen::class, function(){
            return new AliyunGreen();
        });

        $this->app->alias(AliyunGreen::class, 'ali-green');
    }

    public function provides()
    {
        return [AliyunGreen::class, 'ali-green'];
    }
}