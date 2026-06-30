<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Governorate extends Model
{
    use HasFactory;
    protected $table = 'gov_code';

    protected $primaryKey = 'GOVT_CODE';

    public $timestamps = false;

    protected $guarded=['GOVT_CODE'];





}
