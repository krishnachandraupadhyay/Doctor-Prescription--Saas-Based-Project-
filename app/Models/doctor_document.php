<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class doctor_document extends Model
{
     protected $table = 'doctors_document';
    //
    protected $fillable=[
        "doctor_id",
        "document_type",
        "document_name",	
        "document_number",
        "document_file",
        "issue_date",
        "expiry_date",
        "document_step",
        "document_completed"	
   
    ];
}
