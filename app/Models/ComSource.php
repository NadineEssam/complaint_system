<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComSource extends Model
{
    protected $table = 'comsources';
    protected $primaryKey = 'comsourcesid';
    public $timestamps = true;

    protected $fillable = [
        'comsourcesname',
        'validity',
        'created_by',
        'updated_by'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'ID');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'ID');
    }
}
