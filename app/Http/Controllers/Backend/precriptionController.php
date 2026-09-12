<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class precriptionController extends Controller
{
    public function index(){
          return view('backend.Admin.prescription');
    }
    public function Designoneform(){
        return view('backend.Admin.writeprescriptiondesign1');
    }
        
    //
}
