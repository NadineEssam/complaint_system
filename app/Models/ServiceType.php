<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    protected $table = 'srevicetypt';
    protected $primaryKey = 'srevicetyptid';
    public $timestamps = false;

    protected $fillable = [
        'srevicetyptname'
    ];
}