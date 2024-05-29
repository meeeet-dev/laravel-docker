<?php

namespace MeeeetDev\LaravelDocker;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MeeeetDev\LaravelDocker\Skeleton\SkeletonClass
 */
class LaravelDockerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-docker';
    }
}
