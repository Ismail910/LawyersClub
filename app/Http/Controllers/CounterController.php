<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use Illuminate\Http\Request;

class CounterController extends Controller
{
    // Increment member_subscription_sequence
    public function incrementMemberSubscriptionSequence(Request $request)
    {
        $counter = Counter::firstOrCreate([]); // Ensure the counter record exists
        $counter->increment('member_subscription_sequence'); // Increment the member subscription sequence
        return response()->json(['message' => 'Member subscription sequence incremented successfully'], 200);
    }

    // Increment disbursement_order_sequence
    public function incrementDisbursementOrderSequence(Request $request)
    {
        $counter = Counter::firstOrCreate([]); // Ensure the counter record exists
        $counter->increment('disbursement_order_sequence'); // Increment the disbursement order sequence
        return response()->json(['message' => 'Disbursement order sequence incremented successfully'], 200);
    }

    // Increment supply_order_sequence
    public function incrementSupplyOrderSequence(Request $request)
    {
        $counter = Counter::firstOrCreate([]); // Ensure the counter record exists
        $counter->increment('supply_order_sequence'); // Increment the supply order sequence
        return response()->json(['message' => 'Supply order sequence incremented successfully'], 200);
    }
}
