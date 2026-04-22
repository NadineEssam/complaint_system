<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ComplaintSources extends Model
{
    use HasFactory;
    protected $table = 'comsources';

    protected $primaryKey = 'comsourcesid';

    public $timestamps = false;

    protected $guarded=['comsourcesid'];





}
