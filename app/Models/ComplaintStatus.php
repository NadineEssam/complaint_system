<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ComplaintStatus extends Model
{
    use HasFactory;
    protected $table = 'compstatus';

    protected $primaryKey = 'statusID';

    public $timestamps = false;

    protected $guarded=['statusID'];





}
