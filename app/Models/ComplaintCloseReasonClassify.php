<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ComplaintCloseReasonClassify extends Model
{
    use HasFactory;
    protected $table = 'comp_close_reason_classify';

    protected $primaryKey = 'close_reason_classify_id';

    public $timestamps = false;

    protected $guarded=['close_reason_classify_id'];





}
