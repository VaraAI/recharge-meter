<?php

namespace RechargeMeter\RechargeMeterService\Models;

use Illuminate\Database\Eloquent\Model;

class RechargeHistory extends Model
{
    protected $table = 'recharge_histories';

    protected $fillable = [
        'meter_code',
        'meter_type',
        'amount',
        'response_token',
        'response_status',
        'balance',
        'raw_response',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance' => 'decimal:2',
        'raw_response' => 'array',
    ];
} 