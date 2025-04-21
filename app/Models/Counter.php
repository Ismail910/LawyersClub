<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    protected $fillable = [
        'member_subscription_sequence',
        'disbursement_order_sequence',
        'supply_order_sequence',
    ];

    public static function incrementMemberSubscriptionSequence()
    {
        $counter = self::firstOrCreate([]);
        $counter->increment('member_subscription_sequence');
    }

    public static function incrementDisbursementOrderSequence()
    {
        $counter = self::firstOrCreate([]);
        $counter->increment('disbursement_order_sequence');
    }

    public static function incrementSupplyOrderSequence()
    {
        $counter = self::firstOrCreate([]);
        $counter->increment('supply_order_sequence');
    }
}
