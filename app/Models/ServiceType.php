<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    use HasFactory;

    protected $table = 'srevicetypt';
    protected $primaryKey = 'srevicetyptid';
    public $timestamps = true;

    protected $fillable = [
        'srevicetyptname',
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
