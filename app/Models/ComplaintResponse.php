<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintResponse extends Model
{
    protected $table = 'complaint_responses';

    protected $fillable = [
        'complaint_id',
        'ComplaintStatus',
        'ComplaintText',
        'ComplaintService',
        'fk_close_reason_id',
        'fk_close_reason_classify_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function status()
    {
        return $this->belongsTo(
            CompStatus::class,
            'ComplaintStatus',
            'statusID'
        );
    }

    public function serviceType()
    {
        return $this->belongsTo(
            ServiceType::class,
            'ComplaintService',
            'srevicetyptid'
        );
    }

    public function closeReason()
    {
        return $this->belongsTo(
            CompCloseReason::class,
            'fk_close_reason_id',
            'close_reason_ID'
        );
    }

    public function classification()
    {
        return $this->belongsTo(
            CompCloseReasonClassify::class,
            'fk_close_reason_classify_id',
            'close_reason_classify_id'
        );
    }

    public function complaint()
    {
        return $this->belongsTo(
            Complaint::class,
            'complaint_id',
            'ComplaintID'
        );
    }
}