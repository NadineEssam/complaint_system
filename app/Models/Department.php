<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';
    protected $primaryKey = 'dep_id';
    public $timestamps = false;
   
protected $fillable = [
        'depname_ar'
        
    ];
    protected $guarded = ['dep_id'];

    public function sector()
    {
        return $this->belongsTo(
            Sector::class,
            'sector_code', 
            'sector_code'  
        );
    }
}