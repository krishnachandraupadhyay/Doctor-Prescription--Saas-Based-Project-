<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\presciption_data;
use App\Models\patient;
use App\Models\dosage_name;
use App\Models\duration_name;
use App\Models\interval_name;
use App\Models\medicine_categorie;
use App\Models\unit_name;
use App\Models\Medicine;
use App\Models\Suggestion;

class prescriptionController extends Controller
{
    //
    public function index($id){
       $patient = Patient::with('paymentCategory')->findOrFail($id);
       $presciption = presciption_data::with('paymentCategory')->where('patient_id',$id)->get();
       $dosage=dosage_name::all();
       $duration=duration_name::all();
       $interval=interval_name::all();
       $category=medicine_categorie::all();
       $medicine=medicine::all();
       $suggestion=suggestion::all();
       $unit=unit_name::all();
    //    dd($presciption);
       return view('frontend.Doctordashboard.prescription', compact('patient','presciption','unit','suggestion','medicine','category','interval','duration','dosage'));
    }
    public function downloadpdf(){
        $prescription=presciption_data::all();
        $patient = patient::where('doctor_id', Auth::guard('doctor')->id())->get();
        return view('frontend.Doctordashboard.downloadpdf', compact('prescription','patient'));
    }
}
