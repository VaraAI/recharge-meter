<?php

namespace RechargeMeter\RechargeMeterService\Facades;

use Illuminate\Support\Facades\Facade;
use RechargeMeter\RechargeMeterService\Services\UseTypeService;

class UseType extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return UseTypeService::class;
    }
} 