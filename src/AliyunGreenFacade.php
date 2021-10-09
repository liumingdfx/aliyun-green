<?php

namespace Lmdfx\AliyunGreen;

use Illuminate\Support\Facades\Facade;

class AliyunGreenFacade extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'AliyunGreen';
    }
}