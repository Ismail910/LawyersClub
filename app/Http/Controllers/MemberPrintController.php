<?php

namespace App\Http\Controllers;

use App\Models\MemberPrint;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class MemberPrintController extends Controller
{
    public function index(Request $request)
    {
        $currentYear = date('Y');
        $from = $request->has('from') ? $request->get('from') : "{$currentYear}-01-01";
        $to = $request->has('to') ? $request->get('to') : "{$currentYear}-12-31";

        if ($request->ajax()) {
            $query = MemberPrint::whereBetween('printed_at', [$from, $to])
                ->select([
                    'member_prints.id',
                    'member_prints.serial_number',
                    'member_prints.name',
                    'member_prints.amount',
                    'member_prints.notes',
                    'member_prints.printed_at'
                ])
                ->get(); // Get all records at once

            return DataTables::of($query)
                ->editColumn('printed_at', function($memberPrint) {
                    return $memberPrint->printed_at ? $memberPrint->printed_at->format('Y-m-d') : '';
                })
                ->make(true);
        }

        return view('memberprints.index', compact('from', 'to'));
    }
}
