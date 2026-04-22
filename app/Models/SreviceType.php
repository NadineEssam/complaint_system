<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SreviceType extends Model
{
    use HasFactory;
    protected $table = 'srevicetypt';

    protected $primaryKey = 'srevicetyptid';

    public $timestamps = false;

    protected $guarded=['srevicetyptid'];





}
