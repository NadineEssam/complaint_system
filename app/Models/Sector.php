<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Sector extends Model
{
    use HasFactory;
    protected $table = 'sector';

    protected $primaryKey = 'sector_id';

    public $timestamps = false;

    protected $guarded=['sector_id'];





}
