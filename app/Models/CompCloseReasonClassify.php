<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompCloseReasonClassify extends Model
{
    protected $table = 'comp_close_reason_classify';
    protected $primaryKey = 'close_reason_classify_id';
    public $timestamps = false;

    protected $fillable = [
        'close_reason_classify_Name',
        'fk_close_reason_id'
    ];
}