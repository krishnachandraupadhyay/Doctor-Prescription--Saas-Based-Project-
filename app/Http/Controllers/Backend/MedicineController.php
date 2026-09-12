<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\company_name;
use App\Models\dosage_name;
use App\Models\duration_name;
use App\Models\interval_name;
use App\Models\medicine_categorie;
use App\Models\unit_name;
use App\Models\Medicine;
use App\Models\Suggestion;

use Illuminate\Support\Facades\Auth;

class MedicineController extends Controller
{
   
     public function create(){
        $medicine=medicine::latest()->paginate(15);
        $category=medicine_categorie::all();
        $company=company_name::all();
        return view("backend.Admin.medicine",compact('medicine','category','company'));
    }
    public function sugestion(){
        $suggest=suggestion::latest()->paginate(15);
        return view("backend.Admin.suggestion", compact('suggest'));
    }
    public function Addsuggestion(Request $request){
        $request->validate([
            'suggestion*' => 'required | string',
            'description'=>'string'
        ]);
        foreach ($request->suggestion as $suggestion) {
            suggestion::create([
                'suggestion_name' => $suggestion,
                'description'=>$request->description
            ]);
        }

        return back()->with('success', 'Suggestion Added Successfully');
    }
    public function viewform(){
        $comp=company_name::all();
        $cate=medicine_categorie::all();
        return view("backend.Admin.Addmedicine", compact('cate','comp'));
    }
    public function deletemedicine($id){
        $p_id = $id;
        $medicine = medicine::find($p_id);

        if (! $medicine) {
            return redirect()->back()->with('error', 'Record not found.');
        }

        $medicine->delete();

        return redirect()->back()->with('success', 'Record deleted successfully.');
    }
    public function store(Request $request){
        $request->validate([
            'medicine'=>'required | string',
            'generic'=>'required | string',
            'category'=>'required | string',
            'company'=>'required | string',
            'description'=>'string'
        ]);

        $userId = Auth::guard('doctor')->id() ?? Auth::id();

        medicine::create([
            'medicine_name'=>$request->medicine,
            'generic_name'=>$request->generic,
            'category_id'=>$request->category,
            'company_id'=>$request->company,
            'description'=>$request->description,
            'created_by' => $userId,
            'updated_by' => $userId,
        ]);

        return redirect()->route('medicine')->with('success','Medicine Added Successfully');
    }
    public function editmedicine($id){
        $cate=medicine_categorie::all();
        $medicine=medicine::find($id);
        $comp=company_name::all();
        return view('backend.Admin.editmedicine',compact('medicine','cate','comp'));
    }
    public function updatemedicine(Request $request){
        $request->validate([
            'medicine'=>'required | string',
            'generic'=>'required | string',
            'category'=>'required | string',
            'company'=>'required | string',
            'description'=>'string'
        ]);

        $userId = Auth::guard('doctor')->id() ?? Auth::id();
        $medicine=medicine::find($request->id);
        $medicine->update([
            'medicine_name'=>$request->medicine,
            'generic_name'=>$request->generic,
            'category_id'=>$request->category,
            'company_id'=>$request->company,
            'description'=>$request->description,
            'updated_by' => $userId,
        ]);

        return redirect()->back()->with('success','Medicine Updated Successfully');

    }
    public function destroy($id)
     {
    $medicine = Medicine::findOrFail($id);
    $medicine->delete();

    return redirect()->back()->with('success', 'Medicine deleted successfully.');
    }
    public function show(Request $request){
        return $this->listmedicine($request);
    }
    public function updatesuggestion(Request $request, $id)
     {
      $request->validate([
        'suggestion_name' => 'required|string|max:255',
        'description'      => 'required|string',
      ]);

      $suggestion = Suggestion::findOrFail($id);

      $suggestion->suggestion_name = $request->suggestion_name;
      $suggestion->description     = $request->description;
      $suggestion->save();

      return redirect()->back()->with('success', 'Suggestion updated successfully.');
     }
    public function suggestiondestroy($id)
    {
    $suggestion = Suggestion::findOrFail($id);
    $suggestion->delete();

    return redirect()->back()->with('success', 'Suggestion deleted successfully.');
     }

 public function listmedicine(Request $request)
{
    $category = medicine_categorie::all();
    $company  = company_name::all();

    $medicineNames = medicine::whereNotNull('medicine_name')
                              ->distinct()
                              ->orderBy('medicine_name')
                              ->pluck('medicine_name');

    $genericNames  = medicine::whereNotNull('generic_name')
                              ->distinct()
                              ->orderBy('generic_name')
                              ->pluck('generic_name');

    $query = medicine::query();

    if ($request->filled('medicine_name')) {
        $query->where('medicine_name', $request->medicine_name);
    }

    if ($request->filled('generic_name')) {
        $query->where('generic_name', $request->generic_name);
    }

    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    if ($request->filled('company_id')) {
        $query->where('company_id', $request->company_id);
    }

    $medicine = $query->latest()->paginate(10)->withQueryString();

    return view('backend.Admin.listofmedicine', compact(
        'medicine', 'category', 'company', 'medicineNames', 'genericNames'
    ));
}




    
    //
}
