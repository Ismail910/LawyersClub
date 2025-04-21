<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sequence;
use Carbon\Carbon;

class SequenceController extends Controller
{
    /**
     * Show sequences for Member model between the provided date range.
     */
    public function getMemberSequences(Request $request)
{
    $from = $request->input('from', Carbon::now()->startOfYear()->toDateString());
    $to = $request->input('to', Carbon::now()->endOfYear()->toDateString());


    // Parse the dates using Carbon
    $from = Carbon::parse($from)->startOfDay();
    $to = Carbon::parse($to)->endOfDay();

    // Get the sequences for the 'Member' model
    $sequences = Sequence::byModelTypeAndDateRange('Member', $from, $to)->get();

    // Map Sequences to the respective Member
    $membersWithSequences = $sequences->map(function ($sequence) {
        $member = $sequence->sequencable;  // Get the associated Member

        // Add the sequence number to the member data
        $member->sequence_number = $sequence->current_number;

        return $member;
    });

    // Return a view with the sequences for the 'Member' model
    return view('hr.members.sequences', compact('membersWithSequences'));
}

    /**
     * Show sequences for Invoice model between the provided date range.
     */
    public function getInvoiceSequences(Request $request)
    {
        // Validate the date range
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ]);

        // Parse the dates using Carbon
        $from = Carbon::parse($request->from)->startOfDay();
        $to = Carbon::parse($request->to)->endOfDay();

        // Get the sequences for the 'Invoice' model
        $sequences = Sequence::byModelTypeAndDateRange('Invoice', $from, $to)->get();

        // Map Sequences to the respective Invoice
        $invoicesWithSequences = $sequences->map(function ($sequence) {
            $invoice = $sequence->sequencable;  // Get the associated Invoice

            // Add the sequence number to the invoice data
            $invoice->sequence_number = $sequence->current_number;

            return $invoice;
        });

        // Return a view with the sequences for the 'Invoice' model
        return view('sequences.invoice', compact('invoicesWithSequences'));
    }

    /**
     * Show sequences for Budget model between the provided date range.
     */
    public function getBudgetSequences(Request $request)
    {
        // Validate the date range
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ]);

        // Parse the dates using Carbon
        $from = Carbon::parse($request->from)->startOfDay();
        $to = Carbon::parse($request->to)->endOfDay();

        // Get the sequences for the 'Budget' model
        $sequences = Sequence::byModelTypeAndDateRange('Budget', $from, $to)->get();

        // Map Sequences to the respective Budget
        $budgetsWithSequences = $sequences->map(function ($sequence) {
            $budget = $sequence->sequencable;  // Get the associated Budget

            // Add the sequence number to the budget data
            $budget->sequence_number = $sequence->current_number;

            return $budget;
        });

        // Return a view with the sequences for the 'Budget' model
        return view('sequences.budget', compact('budgetsWithSequences'));
    }
}
