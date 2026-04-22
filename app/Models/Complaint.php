<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Complaint extends Model
{
    use HasFactory;
    protected $table = 'sfdcomplaints';

    protected $primaryKey = 'ComplaintID';

    public $timestamps = false;

    protected $guarded=['ComplaintID'];





}
