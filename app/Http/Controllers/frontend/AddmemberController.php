<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Member;

class AddmemberController extends Controller
{
    //
    public function index()
    {
        $doctor = Auth::guard('doctor')->user();
        if ($doctor && isset($doctor->has_member) && !$doctor->has_member) {
            return redirect()->route('nodashboard')->with('error', 'Member management is disabled for your account by onboarding team.');
        }

        $doctorname = $doctor ? $doctor->name : '';
        $members = Member::where(function($q) use ($doctor, $doctorname) {
            if ($doctor && isset($doctor->id)) {
                $q->where('doctor_id', $doctor->id);
            }
            $q->orWhere('created_by', $doctorname)
              ->orWhere('created_by', $doctor->Doctor_Emp_id ?? '')
              ->orWhere('created_by', (string)($doctor->id ?? ''));
        })
        ->where(function($q) {
            $q->where('isdeleted', 0)
              ->orWhereNull('isdeleted');
        })
        ->latest()
        ->get();

        return view('frontend.Doctordashboard.addmember', compact('members'));
    }
 public function store(Request $request)
    {
        return redirect()->back()->with('error', 'Doctors cannot add staff. Staff and receptionists are registered and managed by Clinic Administration.');
    }

public function update(Request $request, $id)
{
    $member = Member::findOrFail($id);

    $request->validate([
        'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\.\'-]+$/u'],
        'role' => 'required|string',
    ], [
        'name.regex' => 'Member name must contain letters only (numbers are not allowed).',
    ]);

    $member->update([
        'name'       => $request->name,
        'role'       => $request->role,
        'updated_by' => Auth::guard('doctor')->user()->name ?? 'Doctor',
    ]);

    return redirect()->route('addmember')->with('success', 'Member updated successfully.');
}
public function toggleStatus($id)
{
    $member = Member::findOrFail($id);

    $member->status = $member->status === 'active' ? 'inactive' : 'active';
    $member->updated_by = Auth::guard('doctor')->user()->name ?? 'Doctor';
    $member->save();

    return response()->json([
        'success' => true,
        'status'  => $member->status,
    ]);
}
    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        $member->isdeleted = 1;
        $member->updated_by = Auth::guard('doctor')->user()->name ?? 'Doctor';
        $member->save();

        return redirect()->back()->with('success', 'Member moved to deleted staff list successfully.');
    }

    public function deletedStaff()
    {
        $doctor = Auth::guard('doctor')->user();
        if ($doctor && isset($doctor->has_deleted_staff) && !$doctor->has_deleted_staff) {
            return redirect()->route('nodashboard')->with('error', 'Deleted staff management is disabled for your account by onboarding team.');
        }

        $doctorname = $doctor ? $doctor->name : '';
        $deletedMembers = Member::where(function($q) use ($doctor, $doctorname) {
            if ($doctor && isset($doctor->id)) {
                $q->where('doctor_id', $doctor->id);
            }
            $q->orWhere('created_by', $doctorname)
              ->orWhere('created_by', $doctor->Doctor_Emp_id ?? '')
              ->orWhere('created_by', (string)($doctor->id ?? ''));
        })
        ->where('isdeleted', 1)
        ->latest()
        ->get();

        return view('frontend.Doctordashboard.deletedstaff', compact('deletedMembers'));
    }

    public function restore($id)
    {
        $member = Member::findOrFail($id);
        $member->isdeleted = 0;
        $member->updated_by = Auth::guard('doctor')->user()->name ?? 'Doctor';
        $member->save();

        return redirect()->back()->with('success', 'Staff member restored successfully.');
    }
    
}
