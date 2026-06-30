<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $connection = 'new_po';
    protected $table = 'sectors';
    protected $primaryKey = 'sec_id';
    public $timestamps = false;

    protected $fillable = [
        'sector_ar',
        'sector_manager',
        'sector_type'
    ];

    public function departments()
    {
        return $this->hasMany(
            Department::class,
            'sector_code', 
            'sector_code'  
        );
    }
}