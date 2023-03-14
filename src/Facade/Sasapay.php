<?php

namespace Mosesgathecha\Sasapay\Facade;

use Illuminate\Support\Facades\Facade;


/* It tells Laravel to use the `sasapay-app` binding when the `Sasapay` facade is called */

class Sasapay extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'sasapay-app';
    }
}
