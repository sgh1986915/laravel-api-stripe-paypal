<?php

namespace Wicochandra\Captcha\Facade;

use Illuminate\Support\Facades\Facade;

class Captcha extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return 'captcha';
    }
}
