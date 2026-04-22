<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintType extends Model
{
    use HasFactory;
    protected $table = 'complainttype';

    protected $primaryKey = 'comtypeid';

    public $timestamps = false;

    protected $guarded=['comtypeid'];





}
