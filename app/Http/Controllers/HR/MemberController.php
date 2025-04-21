<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\MembershipSection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MembersImport;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Start the query to retrieve members
            $members = Member::with('membershipSection')
                ->select('id', 'membership_number', 'name', 'job_title', 'membership_date', 'address', 'phone', 'payment_voucher_number', 'last_payment_year', 'printing_status', 'notes', 'printing_and_payment_date', 'payment_date','amount', 'current_year_paid', 'voting_right', 'gender', 'created_at');

            // Apply filters based on request parameters
            if ($request->name) {
                $members = $members->where('name', 'like', '%' . $request->name . '%');
            }

            if ($request->membership_date_start && $request->membership_date_end) {
                $members = $members->whereBetween('membership_date', [$request->membership_date_start, $request->membership_date_end]);
            }

            if ($request->last_payment_year) {
                $members = $members->where('last_payment_year', $request->last_payment_year);
            }


            if ($request->gender) {
                $members = $members->where('gender', $request->gender);
            }

            // Return filtered data as DataTable
            return DataTables::of($members)
                ->addIndexColumn()
                ->addColumn('action', function ($member) {
                    return '
                        <a href="'.route('hr.members.show', $member->id).'" class="btn btn-info btn-sm">عرض</a>
                        <a href="'.route('hr.members.edit', $member->id).'" class="btn btn-warning btn-sm">تعديل</a>
                        <form action="'.route('hr.members.destroy', $member->id).'" method="POST" style="display:inline;">
                            '.csrf_field().method_field('DELETE').'
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'هل أنت متأكد؟\')">حذف</button>
                        </form>
                    ';
                })
                ->addColumn('amount', function ($member) {
                    return number_format((float) $member->amount, 2, '.', '');
                })


                ->rawColumns(['action'])
                ->make(true);
        }

        // Retrieve all members if no filter is applied
        return view('hr.members.index');
    }


    public function create()
    {
        $membershipSections = MembershipSection::all();
        return view('hr.members.create', compact('membershipSections'));
    }


        // Store new member
        public function store(Request $request)
        {
            // Validate the incoming request data
            $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:15',
                'job_title' => 'required|string|max:255',
                'membership_number' => 'nullable|string|max:255',
                'membership_date' => 'nullable|date',
                'address' => 'nullable|string|max:255',
                'payment_voucher_number' => 'nullable|string|max:255',
                'last_payment_year' => 'nullable|digits:4|integer',
                'printing_status' => 'nullable|string|max:255',
                'notes' => 'nullable|string|max:1000',
                'printing_and_payment_date' => 'nullable|date',
                'payment_date' => 'nullable|date',
                'current_year_paid' => 'nullable|string|max:255',
                'voting_right' => 'nullable|boolean',
                'gender' => 'nullable|string|max:255',
                'amount' => 'nullable|numeric|min:0',
            ]);


            try {
                DB::beginTransaction();

                // Create the new member
                Member::create($request->all());

                DB::commit();
                return redirect()->route('hr.members.index')->with('success', 'تم إضافة العضو بنجاح');
            } catch (\Exception $e) {
                dd($e);
                DB::rollBack();
                return back()->withErrors('حدث خطأ ما')->withInput();
            }
        }

        // Show member details

        public function show(Request $request, Member $member)
        {
            $counter = Counter::first();
            $member_subscription_sequence = $counter ? $counter->member_subscription_sequence : 0;


            $rawAmount = str_replace(',', '', $member->amount ?? 0);
            $amount = number_format((float)$rawAmount, 2, '.', '');
            [$pounds, $piastres] = explode('.', $amount);

            $amountInWords = $this->convertAmountToArabicWords($amount);
            $poundsArabic = $this->toArabicDigits($pounds);
            $piastresArabic = $this->toArabicDigits($piastres);

            if ($request->expectsJson()) {
                return response()->json([
                    'id' => $member->id,
                    'name' => $member->name,
                    'amount' => number_format($member->amount, 2),
                    'pounds' => $poundsArabic,
                    'piastres' => $piastresArabic,
                    'amount_in_words' => $amountInWords,
                    'phone' => $member->phone,
                    'job_title' => $member->job_title,
                    'membership_number' => $member->membership_number,
                    'membership_date' => $member->membership_date,
                    'address' => $member->address,
                    'payment_voucher_number' => $member->payment_voucher_number,
                    'last_payment_year' => $member->last_payment_year,
                    'printing_status' => $member->printing_status,
                    'notes' => $member->notes,
                    'printing_and_payment_date' => $member->printing_and_payment_date,
                    'payment_date' => $member->payment_date,
                    'current_year_paid' => $member->current_year_paid,
                    'voting_right' => $member->voting_right,
                    'gender' => $member->gender,
                    'member_subscription_sequence' => $member_subscription_sequence,
                    'created_at' => $member->created_at->format('Y-m-d H:i:s')
                ]);
            }

            return view('hr.members.show', compact(
                'member',
                'poundsArabic',
                'piastresArabic',
                'amountInWords'
            ));
        }


        private function convertAmountToArabicWords($amount)
{
    $formatter = new \NumberFormatter("ar", \NumberFormatter::SPELLOUT);
    $parts = explode('.', $amount);
    $pounds = (int)($parts[0] ?? 0);
    $piastres = (int)($parts[1] ?? 0);

    $words = '';
    if ($pounds > 0) {
        $words .= $formatter->format($pounds) . ' جنيه';
    }

    if ($piastres > 0) {
        $words .= ' و ' . $formatter->format($piastres) . ' قرش';
    }

    if ($words === '') {
        $words = 'صفر جنيه';
    }

    return $words . ' فقط لا غير';
}

private function toArabicDigits($number)
{
    $western = ['0','1','2','3','4','5','6','7','8','9'];
    $eastern = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
    return str_replace($western, $eastern, $number);
}


        // Edit member
        public function edit(Member $member)
        {
            // Retrieve all available membership sections for selection
            $membershipSections = MembershipSection::all();
            return view('hr.members.edit', compact('member', 'membershipSections'));
        }

        // Update member details
        public function update(Request $request, Member $member)
        {
            // Validate the incoming request data
            $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:15',
                'job_title' => 'required|string|max:255',
                'membership_number' => 'nullable|string|max:255',
                'membership_date' => 'nullable|date',
                'address' => 'nullable|string|max:255',
                'payment_voucher_number' => 'nullable|string|max:255',
                'last_payment_year' => 'nullable|digits:4|integer',
                'printing_status' => 'nullable|string|max:255',
                'notes' => 'nullable|string|max:1000',
                'printing_and_payment_date' => 'nullable|date',
                'payment_date' => 'nullable|date',
                'current_year_paid' => 'nullable|string|max:255',
                'voting_right' => 'nullable|boolean',
                'gender' => 'nullable|string|max:255',
                'amount' => 'nullable|numeric|min:0',
            ]);


            try {
                DB::beginTransaction();

                // Update the member's details
                $member->update($request->all());

                DB::commit();
                return redirect()->route('hr.members.index')->with('success', 'تم تحديث بيانات العضو بنجاح');
            } catch (\Exception $e) {

                DB::rollBack();
                return back()->withErrors('حدث خطأ ما')->withInput();
            }
        }

        // Delete member
        public function destroy(Member $member)
        {
            try {
                // Delete the member
                $member->delete();
                return redirect()->route('hr.members.index')->with('success', 'تم حذف العضو بنجاح');
            } catch (\Exception $e) {
                return back()->withErrors('حدث خطأ ما');
            }
        }

        // Upload member data from file
        public function upload(Request $request)
        {
            // Validate the incoming request data (no need for membership_section_id anymore)
            $request->validate([
                'file' => 'required|mimes:xlsx,csv,xls',
            ]);

            try {
                // Process the uploaded file and import members without the membership_section_id
                Excel::import(new MembersImport(), $request->file('file'));

                return redirect()->back()->with('success', 'تم استيراد الأعضاء بنجاح!');
            } catch (\Exception $e) {
                return back()->withErrors('حدث خطأ أثناء استيراد الأعضاء');
            }
        }

    }
