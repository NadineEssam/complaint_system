<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectType extends Model
{
    protected $table = 'projecttype';

    protected $primaryKey = 'projecttypeid';

    public $timestamps = false;

    protected $fillable = [
        'projecttypeid',
        'projecttypename',
    ];

    
    public function complaints()
    {
        return $this->hasMany(
            \App\Models\Complaint::class,   
            'ComplaintProjectType',          
            'projecttypeid'                 
        );
    }
}