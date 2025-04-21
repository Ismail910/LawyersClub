<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\ArchivedBudget;
use App\Models\Counter;
use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $budgets = Budget::with('category')->select('*');

            return DataTables::of($budgets)
                ->addIndexColumn()
                ->editColumn('created_at', function ($budget) {
                    return $budget->created_at ? $budget->created_at->format('Y-m-d') : '-';
                })
                ->addColumn('category', function ($budget) {
                    return $budget->category ? $budget->category->name : '-';
                })
                ->addColumn('amount', function ($invoice) {
                    return number_format((float)$invoice->amount, 2); // Format the amount with 2 decimal places
                })
                ->addColumn('action', function ($budget) {
                    return '
                        <a href="'.route('budgets.show', $budget->id).'" class="btn btn-info btn-sm">طباعة امر توريد</a>
                        <a href="'.route('budgets.edit', $budget->id).'" class="btn btn-warning btn-sm">تعديل</a>
                        <form action="'.route('budgets.destroy', $budget->id).'" method="POST" style="display:inline;">
                            '.csrf_field().method_field('DELETE').'
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'هل أنت متأكد؟\')">حذف</button>
                        </form>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('accountant.budgets.index');
    }

    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('accountant.budgets.create', compact('parentCategories'));
    }

    public function getSubcategories($parentId)
    {
        $subcategories = Category::where('parent_id', $parentId)->get();
        return response()->json($subcategories);
    }


    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'tenant_name' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            Budget::create([
                'tenant_name' => $request->tenant_name,
                'category_id' => $request->category_id,
                'amount' => $request->amount,
                'notes' => $request->notes,
            ]);

            DB::commit();
            return redirect()->route('budgets.index')->with('success', 'تم إنشاء التوريد بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('حدث خطأ ما')->withInput();
        }
    }
    public function show(Request $request, $id)
    {
        $budget = Budget::with(['category', 'category.parent'])->find($id);

        if (!$budget) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'أمر التوريد غير موجود'], 404);
            }
            abort(404);
        }

        $counter = Counter::first();
        $supply_order_sequence = $counter ? $counter->supply_order_sequence : 0;

        // Prepare category data - always return both names separately
        $categoryName = $budget->category?->name ?? null;
        $parentCategoryName = $budget->category?->parent?->name ?? null;

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'id' => $budget->id,
                'tenant_name' => $budget->tenant_name,
                'category_name' => $categoryName,
                'parent_category_name' => $parentCategoryName,
                'amount' => number_format((float) $budget->amount, 2, '.', ''),
                'notes' => $budget->notes,
                'supply_order_sequence' => $supply_order_sequence,
                'created_at' => $budget->created_at->format('Y-m-d H:i:s')
            ]);
        }

        // Prepare data for view
        $budget->supply_order_sequence = $supply_order_sequence;
        $budget->category_name = $categoryName;
        $budget->parent_category_name = $parentCategoryName;

        return view('accountant.budgets.show', compact('budget'));
    }


    public function edit(Budget $budget)
    {
        $parentCategories = Category::whereNull('parent_id')->get();
        $subcategories = Category::where('parent_id', $budget->category->parent_id)->get();

        return view('accountant.budgets.edit', compact('budget', 'parentCategories', 'subcategories'));
    }


    public function update(Request $request, Budget $budget)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'tenant_name' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Archive the current budget before updating
            ArchivedBudget::create([
                'original_id' => $budget->id,
                'tenant_name' => $request->tenant_name,
                'category_id' => $budget->category_id,
                'amount' => $budget->amount,
                'notes' => $budget->notes,
                'archived_at' => now(),
            ]);

            // Update the existing budget with new data
            $budget->update([
                'tenant_name' => $request->tenant_name,
                'category_id' => $request->category_id,
                'amount' => $request->amount,
                'notes' => $request->notes,
            ]);

            DB::commit();
            return redirect()->route('budgets.index')->with('success', 'تم تحديث التوريد وأرشفة البيانات السابقة بنجاح');
        } catch (\Exception $e) {

            DB::rollBack();
            return back()->withErrors('حدث خطأ أثناء التحديث')->withInput();
        }
    }

    public function destroy(Budget $budget)
    {
        $budget->delete();
        return redirect()->route('budgets.index')->with('success', 'تم حذف التوريد بنجاح');
    }
}
