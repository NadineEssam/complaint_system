<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $table = 'office';           // Table name
    protected $primaryKey = 'ID';          // Primary key
    public $timestamps = false;

    protected $fillable = [
        'FK_GOVT_CODE',
        'REG_OFFIC_NAMA',
        'OFF_NAME',
        'validity'
    ];
    
}