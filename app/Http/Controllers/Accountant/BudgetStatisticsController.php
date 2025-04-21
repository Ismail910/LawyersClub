<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Budget;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BudgetStatisticsController extends Controller
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
    public function getStatistics(Request $request)
    {
        $query = Budget::query();

        // 🔹 Filter by parent category if provided
        if ($request->has('parent_category') && !empty($request->parent_category) && $request->parent_category != 'all') {
            $parentCategoryId = $request->parent_category;
            $categoryIds = Category::where('parent_id', $parentCategoryId)->pluck('id')->toArray();
            $query->whereIn('category_id', $categoryIds);
        }

        // 🔹 Filter by category if provided
        if ($request->has('category_id') && !empty($request->category_id) && $request->category_id != 'all') {
            $query->where('category_id', $request->category_id);
        }

        // 🔹 Fix: Specify `budgets.created_at`
        $fromDate = $request->has('from_date') ? $request->from_date : Carbon::now()->startOfMonth()->toDateString();
        $toDate = $request->has('to_date') ? $request->to_date : Carbon::now()->endOfMonth()->toDateString();
        $query->whereBetween('budgets.created_at', [$fromDate, $toDate]);

        // 🔹 Get monthly statistics
        $monthlyStats = $query->clone()
            ->select(DB::raw('YEAR(budgets.created_at) as year, MONTH(budgets.created_at) as month, SUM(amount) as total_amount'))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // 🔹 Get yearly statistics
        $yearlyStats = $query->clone()
            ->select(DB::raw('YEAR(budgets.created_at) as year, SUM(amount) as total_amount'))
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        // 🔹 Get total amount
        $totalAmount = $query->sum('amount');

        // 🔹 Get category-wise statistics (Fix: Specify `budgets.created_at`)
        $categoryStats = $query->clone()
            ->join('categories', 'budgets.category_id', '=', 'categories.id')
            ->select('categories.name as category_name', DB::raw('SUM(amount) as total_amount'))
            ->whereBetween('budgets.created_at', [$fromDate, $toDate])
            ->groupBy('categories.name')
            ->orderBy('total_amount', 'desc')
            ->get();

        // 🔹 Get filtered budget records for table
        $budgetData = $query->select('budgets.*', 'categories.name as category_name')
            ->join('categories', 'budgets.category_id', '=', 'categories.id')
            ->get()
            ->map(function ($budget) {
                // Add the action column with the view, edit, and delete buttons
                $budget->action = '
                    <a href="' . route('budgets.show', $budget->id) . '" class="btn btn-info btn-sm">عرض</a>
                    <a href="' . route('budgets.edit', $budget->id) . '" class="btn btn-warning btn-sm">تعديل</a>
                    <form action="' . route('budgets.destroy', $budget->id) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'هل أنت متأكد؟\')">حذف</button>
                    </form>
                ';

                // Format the amount to two decimal places
                $budget->amount = number_format($budget->amount, 2);

                return $budget;
            });

        return response()->json([
            'monthly' => $monthlyStats,
            'yearly' => $yearlyStats,
            'total' => $totalAmount,
            'categories' => $categoryStats,
            'budgetData' => $budgetData
        ]);
    }



    public function showStatisticsPage()
    {
        // Fetch all categories and pass them to the view
        $categories = Category::whereNull('parent_id')->get(); // Only fetch parent categories

        // Return view with categories
        return view('accountant.budgets.statistics', compact('categories'));
    }
}
