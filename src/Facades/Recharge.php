<?php

namespace RechargeMeter\RechargeMeterService\Facades;

use Illuminate\Support\Facades\Facade;
use RechargeMeter\RechargeMeterService\Services\RechargeService;

class Recharge extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return RechargeService::class;
    }
} 