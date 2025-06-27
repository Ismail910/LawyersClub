<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceStatisticsController extends Controller
{
    /**
     * Fetch child categories based on parent category ID.
     */
    public function getChildCategories(Request $request)
    {
        $request->validate([
            'parent_id' => 'required|exists:categories,id',
        ]);

        $childCategories = Category::where('parent_id', $request->parent_id)->get();

        return response()->json($childCategories);
    }
    /**
     * Fetch invoice statistics based on the selected filters.
     */
    public function getStatistics(Request $request)
    {
        $query = Invoice::query();

        // Filter by parent category or category if provided
        if ($request->has('parent_category') && $request->parent_category != 'all') {
            // Get categories under the selected parent category
            $parentCategoryId = $request->parent_category;
            $categoryIds = Category::where('parent_id', $parentCategoryId)->pluck('id')->toArray();
            $query->whereIn('category_id', $categoryIds);
        }

        // If 'category_id' is provided, filter by that
        if ($request->has('category_id') && $request->category_id != 'all') {
            $query->where('category_id', $request->category_id);
        }

        // Apply date range filter
        $fromDate = $request->has('from_date') ? $request->from_date : Carbon::now()->startOfMonth()->toDateString();
        $toDate = $request->has('to_date') ? $request->to_date : Carbon::now()->endOfMonth()->toDateString();

        // Fix: Specify `invoices.created_at` to avoid ambiguity
        $query->whereBetween('invoices.created_at', [$fromDate, $toDate]);

        // Get monthly statistics
        $monthlyStats = $query->clone()
            ->select(DB::raw('YEAR(invoices.created_at) as year, MONTH(invoices.created_at) as month, SUM(amount) as total_amount'))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Get yearly statistics
        $yearlyStats = $query->clone()
            ->select(DB::raw('YEAR(invoices.created_at) as year, SUM(amount) as total_amount'))
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        // Get total amount
        $totalAmount = $query->sum('amount');

        // Get category-wise statistics
        $categoryStats = $query->clone()
            ->join('categories', 'invoices.category_id', '=', 'categories.id')
            ->select('categories.name as category_name', DB::raw('SUM(amount) as total_amount'))
            ->whereBetween('invoices.created_at', [$fromDate, $toDate]) // Fix: Specify `invoices.created_at` here as well
            ->groupBy('categories.name')
            ->orderBy('total_amount', 'desc')
            ->get();

        // Fetch filtered invoice records and add the action column
        $invoiceData = $query->select('invoices.*', 'categories.name as category_name')
            ->join('categories', 'invoices.category_id', '=', 'categories.id')
            ->get()
            ->map(function ($invoice) {
                // Add the action field
                $invoice->action = '
                    <a href="' . route('invoices.show', $invoice->id) . '" class="btn btn-info btn-sm">عرض</a>
                    <a href="' . route('invoices.edit', $invoice->id) . '" class="btn btn-warning btn-sm">تعديل</a>
                    <form action="' . route('invoices.destroy', $invoice->id) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'هل أنت متأكد؟\')">حذف</button>
                    </form>
                ';

                // Format created_at as y-m-d
                $invoice->created_at = Carbon::parse($invoice->created_at)->format('y-m-d');

                // Keep amount as numeric value for frontend formatting
                $invoice->amount = (float) $invoice->amount;

                return $invoice;
            });

        return response()->json([
            'monthly' => $monthlyStats,
            'yearly' => $yearlyStats,
            'total' => $totalAmount,
            'categories' => $categoryStats,
            'invoiceData' => $invoiceData
        ]);
    }


    public function showStatisticsPage()
    {
        // Fetch all categories and pass them to the view
        $categories = Category::whereNull('parent_id')->get(); // Only fetch parent categories

        // Return view with categories
        return view('accountant.invoices.statistics', compact('categories'));
    }
}
