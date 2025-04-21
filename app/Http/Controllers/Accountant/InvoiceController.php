<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\ArchivedInvoice;
use App\Models\Counter;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class InvoiceController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Start the query for invoices and join the categories table
            $invoices = Invoice::with('category')
                ->join('categories', 'invoices.category_id', '=', 'categories.id')
                ->select('invoices.*', 'categories.name as category_name'); // Select all invoice fields and category name

            // Apply category filter if provided
            if ($request->has('category_id') && $request->category_id) {
                $invoices->where('invoices.category_id', $request->category_id);
            }

            // Process the DataTable and return the response
            return DataTables::of($invoices)
                ->addIndexColumn()
                ->addColumn('category', function ($invoice) {
                    return $invoice->category ? $invoice->category->name : '-';  // Safely check if category exists
                })
                ->addColumn('created_at', function ($invoice) {
                    return Carbon::parse($invoice->created_at)->format('y-m-d'); // Format created_at as y-m-d
                })
                ->addColumn('amount', function ($invoice) {
                    return number_format((float)$invoice->amount, 2); // Format the amount with 2 decimal places
                })
                ->addColumn('action', function ($invoice) {
                    return '
                        <a href="'.route('invoices.show', $invoice->id).'" class="btn btn-info btn-sm">طباعه امر صرف</a>
                        <a href="'.route('invoices.edit', $invoice->id).'" class="btn btn-warning btn-sm">تعديل</a>
                        <form action="'.route('invoices.destroy', $invoice->id).'" method="POST" style="display:inline;">
                            '.csrf_field().method_field('DELETE').'
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'هل أنت متأكد؟\')">حذف</button>
                        </form>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $categories = Category::all();

        return view('accountant.invoices.index', compact('categories'));
    }



    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('accountant.invoices.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'amount' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'description' => 'nullable|string',
            'archived' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            Invoice::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'invoice_number' => $request->invoice_number,
                'amount' => $request->amount,
                'description' => $request->description,
                'archived' => $request->archived ?? false,
            ]);

            DB::commit();
            return redirect()->route('invoices.index')->with('success', 'تم إنشاء الامر صرف بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'حدث خطأ أثناء إنشاء الامر صرف: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Request $request, $id)
    {
        $invoice = Invoice::with(['category', 'category.parent'])->find($id);

        if (!$invoice) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'الفاتورة غير موجودة'], 404);
            }
            abort(404);
        }


        $counter = Counter::first();
        $disbursement_order_sequence = $counter ? $counter->disbursement_order_sequence : 0;

        if ($request->expectsJson() || $request->ajax()) {
            $categoryName = $invoice->category ? $invoice->category->name : "غير محدد";
            $parentCategoryName = $invoice->category && $invoice->category->parent
                ? $invoice->category->parent->name
                : null;

            return response()->json([
                'id' => $invoice->id,
                'name' => $invoice->name,
                'invoice_number' => $invoice->invoice_number,
                'category' => $parentCategoryName ? $parentCategoryName : $categoryName,
                'subcategory' => $parentCategoryName ? $categoryName : null,
                'amount' => $invoice->amount,
                'description' => $invoice->description,
                'disbursement_order_sequence' => $disbursement_order_sequence,
                'created_at' => $invoice->created_at->format('Y-m-d')
            ]);
        }


        $invoice->disbursement_order_sequence = $disbursement_order_sequence;

        if ($invoice->category && $invoice->category->parent) {
            $invoice->parent_category_name = $invoice->category->parent->name;
            $invoice->subcategory_name = $invoice->category->name;
        } else {
            $invoice->parent_category_name = $invoice->category ? $invoice->category->name : "غير محدد";
            $invoice->subcategory_name = null;
        }

        return view('accountant.invoices.show', compact('invoice'));
    }



    public function edit(Invoice $invoice)
    {
        $parentCategories = Category::whereNull('parent_id')->get();

        return view('accountant.invoices.edit', compact('invoice', 'parentCategories'));
    }


    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'invoice_number' => 'required|string|unique:invoices,invoice_number,' . $invoice->id,
            'amount' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'description' => 'nullable|string',
            'name'=> 'required|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            // Archive the current invoice before updating
            ArchivedInvoice::create([
                'original_id' => $invoice->id,
                'name' => $invoice->name,
                'category_id' => $invoice->category_id,
                'invoice_number' => $invoice->invoice_number,
                'amount' => $invoice->amount,
                'description' => $invoice->description,
                'archived_at' => now(),
            ]);

            $invoice->update([
                'name' => $invoice->name,
                'category_id' => $request->category_id,
                'invoice_number' => $request->invoice_number,
                'amount' => $request->amount,
                'description' => $request->description,
            ]);

            DB::commit();
            return redirect()->route('invoices.index')->with('success', 'تم تحديث الامر صرف وأرشفة البيانات السابقة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('حدث خطأ أثناء التحديث')->withInput();
        }
    }


    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'تم حذف الامر صرف بنجاح');
    }
}
