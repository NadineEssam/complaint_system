<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompStatus extends Model
{
    protected $table = 'compstatus';
    protected $primaryKey = 'statusID';
    public $timestamps = false;

    protected $fillable = [
        'statusText'
    ];
}