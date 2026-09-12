<?php


namespace App\Http\Controllers\Backend;


use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\symptoms;
use App\Models\diagnosis_test;
use App\Models\User;

class SymptomsController extends Controller
{
    // List all symptoms
    public function index()
    {
        $symptoms = symptoms::latest()->paginate(15);

        return view('backend.Admin.symptoms', compact('symptoms'));
    }

    // Add one or more symptoms
    public function add(Request $request)
    {
        $request->validate([
            'symptom_name*' => 'required',
        ]);

        foreach ($request->symptom_name as $symptom) {
            symptoms::create([
                'symptom_name' => $symptom,
                'created_by' => auth()->user()->name ?? 'Admin',
                'updated_by' => auth()->user()->name ?? 'Admin',
            ]);
        }

        return back()->with('success', 'Symptom Added Successfully');
    }

    // Delete a symptom
    public function delete($id)
    {
        $symptom = symptoms::find($id);

        if (! $symptom) {
            return redirect()->back()->with('error', 'Record not found.');
        }

        $symptom->delete();

        return redirect()->back()->with('success', 'Record deleted successfully.');
    }

    // Update a symptom
    public function update(Request $request)
    {
        $request->validate([
            'symptom_name' => 'required',
        ]);

        $symptom = symptoms::findOrFail($request->id);

        $symptom->update([
            'symptom_name' => $request->symptom_name,
            'updated_by' => auth()->user()->name ?? 'Admin',
        ]);

        return redirect()->back()->with('success', 'Symptom updated Successfully');
    }
    public function indexTest(){
        $tests = diagnosis_test::latest()->paginate(15);
        return view('backend.Admin.diagnosis',compact('tests'));
    }

    // Add one or more diagnosis tests (supports test_name[] like Suggestion)
    public function addTest(Request $request)
    {
        $request->validate([
            'test_name*' => 'required',
            'description' => 'nullable',
        ]);

        foreach ($request->test_name as $test) {
            diagnosis_test::create([
                'test_name' => $test,
                'description' => $request->description,
                'created_by' => auth()->user()->name ?? 'Admin',
                'updated_by' => auth()->user()->name ?? 'Admin',
            ]);
        }

        return back()->with('success', 'Diagnosis Test Added Successfully');
    }

    // Delete a diagnosis test
    public function deleteTest($id)
    {
        $test = diagnosis_test::find($id);

        if (! $test) {
            return redirect()->back()->with('error', 'Record not found.');
        }

        $test->delete();

        return redirect()->back()->with('success', 'Record deleted successfully.');
    }

    // Update a diagnosis test
    public function updateTest(Request $request, $id)
    {
        $request->validate([
            'test_name' => 'required',
            'description' => 'nullable',
        ]);

        $test = diagnosis_test::findOrFail($id);

        $test->update([
            'test_name' => $request->test_name,
            'description' => $request->description,
            'updated_by' => auth()->user()->name ?? 'Admin',
        ]);

        return redirect()->back()->with('success', 'Diagnosis Test updated Successfully');
    }
     

}