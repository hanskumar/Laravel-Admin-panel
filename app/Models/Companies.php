<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
   /*  protected $fillable = [
        'company_id','company_name','description','contact_person_name','contact_no' 
    ]; */

    protected $guarded = [];

    //public $timestamps = false;

    const CREATED_AT = 'created_at';
    //const UPDATED_AT = 'last_update';
}
