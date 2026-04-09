<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompCloseReason extends Model
{
    protected $table = 'comp_close_reason';
    protected $primaryKey = 'close_reason_ID';
    public $timestamps = false;

    protected $fillable = [
        'close_reason_Name'
    ];

    public function classifications()
    {
        return $this->hasMany(
            CompCloseReasonClassify::class,
            'fk_close_reason_id'
        );
    }
}
