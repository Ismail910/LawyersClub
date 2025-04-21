<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\BudgetPrint;
use App\Models\Invoice;
use App\Models\InvoicePrint;
use App\Models\MemberPrint;
class CounterController extends Controller
{
    // Increment member_subscription_sequence
    // public function incrementMemberSubscriptionSequence(Request $request)
    // {
    //     $counter = Counter::firstOrCreate([]); // Ensure the counter record exists
    //     $counter->increment('member_subscription_sequence'); // Increment the member subscription sequence
    //     return response()->json(['message' => 'Member subscription sequence incremented successfully'], 200);
    // }




    public function incrementMemberSubscriptionSequence(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
        ]);

        $member = Member::find($request->member_id);
        if (!$member) {
            return response()->json(['error' => 'Member not found'], 404);
        }

        $counter = Counter::firstOrCreate([]);

        MemberPrint::create([
            'serial_number' => $counter->member_subscription_sequence,
            'printed_at'    => now(),
            'name'          => $member->name,
            'notes'         => $member->notes,
            'amount'        => $member->amount,
        ]);

        $counter->increment('member_subscription_sequence');

        return response()->json([
            'message' => 'MemberPrint created & subscription sequence incremented successfully ✅',
            'serial_number' => $counter->member_subscription_sequence,
        ]);
    }




    public function incrementDisbursementOrderSequence(Request $request)
    {
        $invoiceId = $request->input('invoice_id');
        $invoice = Invoice::find($invoiceId);

        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found.'], 404);
        }

        $counter = Counter::firstOrCreate([]);


        InvoicePrint::create([
            'serial_number'   => $counter->disbursement_order_sequence,
            'category_id'     => $invoice->category_id,
            'invoice_number'  => $invoice->invoice_number,
            'amount'          => $invoice->amount,
            'description'     => $invoice->description,
            'printed_at'      => now(),
        ]);
        $counter->increment('disbursement_order_sequence');
        return response()->json(['message' => 'Disbursement order sequence incremented and InvoicePrint saved ✅']);
    }

public function incrementSupplyOrderSequence(Request $request)
{
    $budgetId = $request->input('budget_id');

    // Fetch the budget
    $budget = Budget::find($budgetId);
    if (!$budget) {
        return response()->json(['error' => 'Budget not found.'], 404);
    }

    // Ensure the counter record exists
    $counter = Counter::firstOrCreate([]);


    BudgetPrint::create([
        'serial_number' => $counter->supply_order_sequence,
        'printed_at'    => now(),
        'category_id'   => $budget->category_id,
        'amount'        => $budget->amount,
        'notes'         => $budget->notes,
    ]);

    $counter->increment('supply_order_sequence');

    return response()->json(['message' => 'Supply order sequence incremented and BudgetPrint saved ✅']);
}

}
