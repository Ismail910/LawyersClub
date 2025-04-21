<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ArchivedBudget;
use App\Models\Budget;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ArchivedBudgetController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $archivedBudgets = ArchivedBudget::with('category', 'originalBudget')
                ->select('id', 'original_id', 'category_id', 'amount', 'notes', 'archived_at');

            return DataTables::of($archivedBudgets)
                ->addIndexColumn()
                ->addColumn('category', function ($archivedBudget) {
                    return $archivedBudget->category ? $archivedBudget->category->name : '-';
                })
                ->addColumn('original_budget', function ($archivedBudget) {
                    return $archivedBudget->originalBudget ? 'Budget ID: ' . $archivedBudget->original_id : '-';
                })
                ->addColumn('amount', function ($invoice) {
                    return number_format((float)$invoice->amount, 2); // Format the amount with 2 decimal places
                })

                ->addColumn('action', function ($archivedBudget) {
                    return '
                        <a href="'.route('archived-budgets.show', $archivedBudget->id).'" class="btn btn-info btn-sm">عرض</a>
                        <form action="'.route('archived-budgets.destroy', $archivedBudget->id).'" method="POST" style="display:inline;">
                            '.csrf_field().method_field('DELETE').'
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'هل أنت متأكد؟\')">حذف</button>
                        </form>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('accountant.archived_budgets.index');
    }





    public function show(ArchivedBudget $archivedBudget)
    {
        return view('accountant.archived_budgets.show', compact('archivedBudget'));
    }


    public function destroy(ArchivedBudget $archivedBudget)
    {
        $archivedBudget->delete();
        return redirect()->route('archived-budgets.index')->with('success', 'تم حذف التوريد المؤرشفة بنجاح');
    }
}
