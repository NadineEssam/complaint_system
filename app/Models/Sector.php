<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $table = 'sector';          // Table name
    protected $primaryKey = 'sector_id';  // Primary key
    public $timestamps = false;

    protected $fillable = [
        'sector_name',
        'sector_manager',
        'sector_type'
    ];
}