<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ArchivedInvoice;
use App\Models\Invoice;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ArchivedInvoiceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $archivedInvoices = ArchivedInvoice::with('category', 'originalInvoice')
                ->select('id', 'original_id', 'category_id', 'invoice_number', 'amount', 'description', 'archived_at'); // changed 'total' to 'amount'

            return DataTables::of($archivedInvoices)
                ->addIndexColumn()
                ->addColumn('category', function ($archivedInvoice) {
                    return $archivedInvoice->category ? $archivedInvoice->category->name : '-';
                })
                ->addColumn('original_invoice', function ($archivedInvoice) {
                    return $archivedInvoice->originalInvoice ? 'Invoice ID: ' . $archivedInvoice->original_id : '-';
                })
                ->addColumn('amount', function ($invoice) {
                    return number_format((float)$invoice->amount, 2); // Format the amount with 2 decimal places
                })
                ->addColumn('action', function ($archivedInvoice) {
                    return '
                        <a href="'.route('archived-invoices.show', $archivedInvoice->id).'" class="btn btn-info btn-sm">عرض</a>
                        <form action="'.route('archived-invoices.destroy', $archivedInvoice->id).'" method="POST" style="display:inline;">
                            '.csrf_field().method_field('DELETE').'
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'هل أنت متأكد؟\')">حذف</button>
                        </form>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('accountant.archived_invoices.index');
    }

    public function show(ArchivedInvoice $archivedInvoice)
    {

        return view('accountant.archived_invoices.show', compact('archivedInvoice'));
    }

    public function destroy(ArchivedInvoice $archivedInvoice)
    {
        try {
            DB::beginTransaction();

            // Unarchive the original invoice before deleting the archived one
            Invoice::where('id', $archivedInvoice->original_id)->update(['archived' => false]);

            $archivedInvoice->delete();

            DB::commit();
            return redirect()->route('archived-invoices.index')->with('success', 'تم حذف الامر صرف المؤرشفة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('حدث خطأ أثناء الحذف');
        }
    }
}
