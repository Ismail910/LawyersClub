<?php

namespace App\Http\Controllers;

use App\Models\InvoicePrint;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class InvoicePrintController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $from = $request->input('from');
            $to = $request->input('to');
            $parentCategoryId = $request->input('parent_category_id');
            $subcategoryId = $request->input('subcategory_id');

            if (!$from) {
                // Set fiscal year start date (July 1)
                $currentYear = date('Y');
                $currentMonth = date('n');

                if ($currentMonth >= 7) {
                    // Current fiscal year (July 1 of current year)
                    $from = $currentYear . '-07-01';
                } else {
                    // Previous fiscal year (July 1 of previous year)
                    $from = ($currentYear - 1) . '-07-01';
                }
            }
            if (!$to) {
                // Set fiscal year end date (June 30)
                $currentYear = date('Y');
                $currentMonth = date('n');

                if ($currentMonth >= 7) {
                    // Current fiscal year (June 30 of next year)
                    $to = ($currentYear + 1) . '-06-30';
                } else {
                    // Previous fiscal year (June 30 of current year)
                    $to = $currentYear . '-06-30';
                }
            }

            $query = InvoicePrint::with(['category'])
                ->whereBetween('printed_at', [$from, $to]);

            // Filter by categories
            if ($parentCategoryId && $parentCategoryId !== 'all') {
                if ($subcategoryId && $subcategoryId !== 'all') {
                    // Filter by specific subcategory
                    $query->where('category_id', $subcategoryId);
                } else {
                    // Filter by all subcategories of the parent
                    $subcategoryIds = Category::where('parent_id', $parentCategoryId)->pluck('id')->toArray();
                    if (!empty($subcategoryIds)) {
                        $query->whereIn('category_id', $subcategoryIds);
                    }
                }
            } elseif ($subcategoryId && $subcategoryId !== 'all') {
                // Filter by specific subcategory even if no parent selected
                $query->where('category_id', $subcategoryId);
            }

            $data = $query->select([
                'invoice_prints.serial_number',
                'invoice_prints.invoice_number',
                'invoice_prints.amount',
                'invoice_prints.description',
                'invoice_prints.printed_at',
                'invoice_prints.category_id'
            ])->get();

            return DataTables::of($data)
                ->addColumn('category_name', function($invoicePrint) {
                    return $invoicePrint->category ? $invoicePrint->category->name : 'غير محدد';
                })
                ->editColumn('amount', function($invoicePrint) {
                    return (float) $invoicePrint->amount;
                })
                ->make(true);
        }

        // Get all categories for the dropdown
        $categories = Category::whereNull('parent_id')->get();

        return view('invoiceprints.index', compact('categories'));
    }

    public function getSubcategories(Request $request)
    {
        $parentId = $request->input('parent_id');

        if (!$parentId) {
            return response()->json([]);
        }

        $subcategories = Category::where('parent_id', $parentId)
            ->select('id', 'name')
            ->get();

        return response()->json($subcategories);
    }
}
