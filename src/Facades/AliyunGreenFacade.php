<?php

namespace Lmdfx\AliyunGreen\Facades;

use Illuminate\Support\Facades\Facade;

class AliyunGreenFacade extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ali-green';
    }
}