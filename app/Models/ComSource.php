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


}