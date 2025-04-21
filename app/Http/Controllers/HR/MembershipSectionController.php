<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MembershipSection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MembershipSectionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $sections = MembershipSection::select('id', 'name', 'description', 'created_at');

            return DataTables::of($sections)
                ->addIndexColumn()
                ->addColumn('action', function ($section) {
                    return '
                        <a href="'.route('hr.membership-sections.show', $section->id).'" class="btn btn-info btn-sm">عرض</a>
                        <a href="'.route('hr.membership-sections.edit', $section->id).'" class="btn btn-warning btn-sm">تعديل</a>
                        <form action="'.route('hr.membership-sections.destroy', $section->id).'" method="POST" style="display:inline;">
                            '.csrf_field().method_field('DELETE').'
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'هل أنت متأكد؟\')">حذف</button>
                        </form>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('hr.membership_sections.index');
    }

    public function create()
    {
        return view('hr.membership_sections.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:membership_sections,name',
            'description' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            MembershipSection::create($request->all());

            DB::commit();
            return redirect()->route('hr.membership-sections.index')->with('success', 'تم إضافة القسم بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('حدث خطأ ما')->withInput();
        }
    }

    public function show(MembershipSection $membershipSection)
    {
        return view('hr.membership_sections.show', compact('membershipSection'));
    }

    public function edit(MembershipSection $membershipSection)
    {
        return view('hr.membership_sections.edit', compact('membershipSection'));
    }

    public function update(Request $request, MembershipSection $membershipSection)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:membership_sections,name,' . $membershipSection->id,
            'description' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $membershipSection->update($request->all());

            DB::commit();
            return redirect()->route('hr.membership-sections.index')->with('success', 'تم تحديث القسم بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('حدث خطأ ما')->withInput();
        }
    }

    public function destroy(MembershipSection $membershipSection)
    {
        $membershipSection->delete();
        return redirect()->route('hr.membership-sections.index')->with('success', 'تم حذف القسم بنجاح');
    }
}
