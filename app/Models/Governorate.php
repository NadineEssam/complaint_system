<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Governorate extends Model
{
    use HasFactory;
    protected $table = 'govs';

    protected $primaryKey = 'govsid';

    public $timestamps = false;

    protected $guarded=['govsid'];





}
