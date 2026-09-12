<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\company_name;
use App\Models\dosage_name;
use App\Models\duration_name;
use App\Models\interval_name;
use App\Models\medicine_categorie;
use App\Models\unit_name;
use Illuminate\Http\Request;

class MedicinemasterController extends Controller
{
    // medicine master
    public function index()
    {
        $categor = medicine_categorie::latest()->paginate(15);

        return view('backend.Admin.medicinemaster', compact('categor'));
    }
    public function mediupdate(Request $request)
    {
        $request->validate([
            'category_name' => 'required',
        ]);
        $category = medicine_categorie::findOrFail($request->id);

        $category->update([
                'category_name' => $request->category_name,
                'created_by' => 'Doctor',
                'updated_by' => 'Doctor',
            ]);
            
        return redirect()->back()->with('success', 'Category updated Successfully');
    }



    // category
    public function category()
    {
        $category = medicine_categorie::latest()->paginate(15);

        return view('backend.Admin.medicinecategory', compact('category'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name.*' => 'required',
        ]);

        foreach ($request->category_name as $category) {

            medicine_categorie::create([
                'category_name' => $category,
                'created_by' => 'Admin',
                'updated_by' => 'Admin',

            ]);

        }

        return back()->with('success', 'Category Added Successfully');
    }

    public function edit($id)
    {
        $p_id = $id;

        return view('backend.Admin.editcategory', compact('p_id'));
    }

    public function delete($id)
    {
        $p_id = $id;
        $category = medicine_categorie::find($p_id);

        if (! $category) {
            return redirect()->back()->with('error', 'Record not found.');
        }

        $category->delete();

        return redirect()->back()->with('success', 'Record deleted successfully.');
    }
    public function update(Request $request)
    {
        $request->validate([
            'category_name' => 'required',
        ]);
        $category = medicine_categorie::findOrFail($request->id);

        $category->update([
                'category_name' => $request->category_name,
                'created_by' => 'Doctor',
                'updated_by' => 'Doctor',
            ]);
            
        return redirect()->back()->with('success', 'Category updated Successfully');
    }




    // dosage
    public function dosage()
    {
        $dos = dosage_name::latest()->paginate(15);
        $categ = medicine_categorie::all();
        $uni = unit_name::all();

        return view('backend.Admin.medicinedosage',compact('dos','categ','uni'));
    }
    public function DosageAdd(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'unit' => 'required|string',
            'dosage' => 'required|string',
        ]);

        if ($request->filled('id')) {
            $dosage = dosage_name::find($request->id);
            if ($dosage) {
                $dosage->update([
                    'category_id' => $request->category,
                    'unit_id'     => $request->unit,
                    'dosage_name' => $request->dosage,
                ]);
                return redirect()->back()->with('success', 'Dosage updated successfully');
            }
        }

        dosage_name::create([
            'category_id' => $request->category,
            'unit_id'     => $request->unit,
            'dosage_name' => $request->dosage,
            'status'      => true,
        ]);

        return redirect()->back()->with('success', 'Record added successfully');
    }

    public function deletedosage($id)
    {
        $p_id = $id;
        $dosage = dosage_name::find($p_id);

        if (! $dosage) {
            return redirect()->back()->with('error', 'Record not found.');
        }

        $dosage->delete();

        return redirect()->back()->with('success', 'Record deleted successfully.');
    }
    
    // interval
    public function interval()
    {
        $interval = interval_name::latest()->paginate(15);

        return view('backend.Admin.medicineinterval', compact('interval'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'interval*' => 'required',
        ]);
        foreach ($request->interval as $interval) {
            interval_name::create([
                'interval_name' => $interval,
            ]);
        }

        return back()->with('success', 'Interval Added Successfully');
    }

    public function deleteinterval($id)
    {
        $p_id = $id;
        $interval = interval_name::find($p_id);

        if (! $interval) {
            return redirect()->back()->with('error', 'Record not found.');
        }

        $interval->delete();

        return redirect()->back()->with('success', 'Record deleted successfully.');
    }
    public function intervalupdate(Request $request)
    {
        $request->validate([
            'interval_name' => 'required',
        ]);
        $interval=interval_name::findOrFail($request->id);

        $interval->update([
                'interval_name' => $request->interval_name,
                'created_by' => 'Doctor',
                'updated_by' => 'Doctor',
            ]);
            
        return redirect()->back()->with('success', 'Interval updated Successfully');
    }




    // duration
    public function duration()
    {
        $duration = duration_name::latest()->paginate(15);

        return view('backend.Admin.medicineduration', compact('duration'));
    }

    public function deleteduration($id)
    {
        $p_id = $id;
        $duration = duration_name::find($p_id);

        if (! $duration) {
            return redirect()->back()->with('error', 'Record not found.');
        }

        $duration->delete();

        return redirect()->back()->with('success', 'Record deleted successfully.');
    }

    public function insert(Request $request)
    {
        $request->validate([
            'duration.*' => 'required',
        ]);

        foreach ($request->duration as $category) {

            duration_name::create([
                'duration_name' => $category,

            ]);

        }

        return back()->with('success', 'Duration Added Successfully');
    }
     public function durationupdate(Request $request)
    {
        $request->validate([
            'duration_name' => 'required',
        ]);
        $duration=duration_name::findOrFail($request->id);

        $duration->update([
                'duration_name' => $request->duration_name,
                'created_by' => 'Doctor',
                'updated_by' => 'Doctor',
            ]);
            
        return redirect()->back()->with('success', 'Duration updated Successfully');
    }




    // unit
    public function unit()
    {
        $unit = unit_name::latest()->paginate(15);

        return view('backend.Admin.medicineunit', compact('unit'));
    }

    public function unitinsert(Request $request)
    {
        $request->validate([
            'unit*' => 'required',
        ]);
        foreach ($request->unit as $unit) {
            unit_name::create([
                'unit_name' => $unit,
            ]);
        }

        return back()->with('success', 'Unit Added Successfully');
    }

    public function deleteunit($id)
    {
        $p_id = $id;
        $unit = unit_name::find($p_id);

        if (! $unit) {
            return redirect()->back()->with('error', 'Record not found.');
        }

        $unit->delete();

        return redirect()->back()->with('success', 'Record deleted successfully.');
    }
    public function unitupdate(Request $request)
    {
        $request->validate([
            'unit_name' => 'required',
        ]);
        $unit=unit_name::findOrFail($request->id);

        $unit->update([
                'unit_name' => $request->unit_name,
                'created_by' => 'Doctor',
                'updated_by' => 'Doctor',
            ]);
            
        return redirect()->back()->with('success', 'Unit updated Successfully');
    }







    // company
    public function company()
    {
        $company = company_name::latest()->paginate(15);

        return view('backend.Admin.medicinecompany', compact('company'));
    }

    public function companyadd(Request $request)
    {
        $request->validate([
            'company_name*' => 'required',
        ]);
        foreach ($request->company_name as $company) {
            company_name::create([
                'company_name' => $company,
            ]);
        }

        return back()->with('success', 'Company Added Successfully');

    }

    public function deletecompany($id)
    {
        $p_id = $id;
        $company = company_name::find($p_id);

        if (! $company) {
            return redirect()->back()->with('error', 'Record not found.');
        }

        $company->delete();

        return redirect()->back()->with('success', 'Record deleted successfully.');
    }
    public function companyupdate(Request $request)
    {
        $request->validate([
            'company_name' => 'required',
        ]);
        $company=company_name::findOrFail($request->id);

        $company->update([
                'company_name' => $request->company_name,
            ]);
            
        return redirect()->back()->with('success', 'Company updated Successfully');
    }

    
}
