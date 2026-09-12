<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\presciption_data;


class patientHistory extends Controller
{
    //
  public function history($id)
{
    $patient = Patient::with([
        'presciption_data' => function ($q) {
            $q->orderByDesc('created_at');
        },
        'presciption_data.medicine',
        'presciption_data.dosageName',
        'presciption_data.unitName',
        'presciption_data.intervalName',
        'presciption_data.durationName',
    ])->findOrFail($id);

    return view('frontend.Doctordashboard.patientHistory', compact('patient'));
}
}
