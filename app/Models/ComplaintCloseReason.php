<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ComplaintCloseReason extends Model
{
    use HasFactory;
    protected $table = 'comp_close_reason';

    protected $primaryKey = 'close_reason_ID';

    public $timestamps = false;

    protected $guarded=['close_reason_ID'];





}
