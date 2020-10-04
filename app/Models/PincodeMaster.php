<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PincodeMaster extends Model
{
    protected $fillable = [
        'pincode','state','city','address','status' 
    ];

    protected $table = 'pincode_master';

    public $timestamps = false;
}
