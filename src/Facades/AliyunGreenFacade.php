<?php

namespace Lmdfx\AliyunGreen\Facades;

use Illuminate\Support\Facades\Facade as AliyunGreenFacade;

class Facade extends AliyunGreenFacade {

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