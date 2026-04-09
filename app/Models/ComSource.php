<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComSource extends Model
{
    protected $table = 'comsources';          // Table name
    protected $primaryKey = 'comsourcesid';   // Primary key
    public $timestamps = false;

    protected $fillable = [
        'comsourcesname'
    ];

    public function complaints()
{
    return $this->belongsToMany(
        Complaint::class,
        'complaint_sources',
        'comsource_id',
        'complaint_id'
    );
}
}