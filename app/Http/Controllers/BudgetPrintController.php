<?php

namespace App\Http\Controllers;

use App\Models\BudgetPrint;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BudgetPrintController extends Controller
{
    public function index(Request $request)
    {
        $currentYear = date('Y');
        $from = $request->has('from') ? $request->get('from') : "{$currentYear}-01-01";
        $to = $request->has('to') ? $request->get('to') : "{$currentYear}-12-31";

        if ($request->ajax()) {
            $query = BudgetPrint::with(['category'])
                ->whereBetween('printed_at', [$from, $to])
                ->select([
                    'budget_prints.id',
                    'budget_prints.serial_number',
                    'budget_prints.amount',
                    'budget_prints.notes as description',
                    'budget_prints.printed_at',
                    'budget_prints.category_id'
                ])
                ->get(); // Get all records at once

            return DataTables::of($query)
                ->addColumn('category_name', function($budgetPrint) {
                    return $budgetPrint->category ? $budgetPrint->category->name : 'غير محدد';
                })
                ->addColumn('printed_at_formatted', function($budgetPrint) {
                    return $budgetPrint->printed_at ? $budgetPrint->printed_at->format('Y-m-d') : '';
                })
                ->make(true);
        }

        return view('budgetprints.index', compact('from', 'to'));
    }
}
