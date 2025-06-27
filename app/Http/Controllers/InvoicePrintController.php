<?php

namespace App\Http\Controllers;

use App\Models\InvoicePrint;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class InvoicePrintController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $from = $request->input('from');
            $to = $request->input('to');

            if (!$from) {
                $from = date('Y') . '-01-01';
            }
            if (!$to) {
                $to = date('Y') . '-12-31';
            }

            $query = InvoicePrint::with(['category'])
                ->whereBetween('printed_at', [$from, $to])
                ->select([
                    'invoice_prints.serial_number',
                    'invoice_prints.invoice_number',
                    'invoice_prints.amount',
                    'invoice_prints.description',
                    'invoice_prints.printed_at',
                    'invoice_prints.category_id'
                ])
                ->get(); // Add get() to retrieve all records

            return DataTables::of($query)
                ->addColumn('category_name', function($invoicePrint) {
                    return $invoicePrint->category ? $invoicePrint->category->name : 'غير محدد';
                })
                ->make(true);
        }

        return view('invoiceprints.index');
    }
}
