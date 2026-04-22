<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ComplaintResponse extends Model
{
    use HasFactory;
    protected $table = 'complaint_responses';

    protected $primaryKey = 'id';

    protected $guarded=['id'];





}
