<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintSource extends Model
{
    protected $table = 'complaint_sources';   // Pivot table
    protected $primaryKey = 'id';             // Primary key
    public $timestamps = false;

    protected $fillable = [
        'complaint_id',
        'comsource_id'
    ];

 
}