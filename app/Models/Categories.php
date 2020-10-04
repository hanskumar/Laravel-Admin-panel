<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model {

    protected $primaryKey = 'category_id';

    protected $fillable = [
        'category_name','category_slug','categroy_status' 
    ];

    public $timestamps = false;

    const CREATED_AT = 'created_at';
}
