<?php
namespace Mosinas\SmsPilot\Facades;

use Illuminate\Support\Facades\Facade;

class SmsPilotFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'smspilot';
    }
}