<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintResponse extends Model
{ 
    public $timestamps = false;
    protected $table = 'complaint_responses';

    protected $fillable = [
        'complaint_id',
        'ComplaintStatus',
        'ComplaintText',
        'ComplaintService',
        'fk_close_reason_id',
        'fk_close_reason_classify_id',
    ];

    protected $casts = [
    'created_at' => 'datetime',
];
public function serviceType()
{
    return $this->belongsTo(ServiceType::class, 'ComplaintService', 'srevicetyptid');
}

public function getComplaintServiceNameAttribute()
{
    return $this->serviceType?->srevicetyptname ?? '-';
}

public function closeReason()
{
    return $this->belongsTo(CompCloseReason::class, 'fk_close_reason_id', 'close_reason_ID');
}

public function classification()
{
    return $this->belongsTo(CompCloseReasonClassify::class, 'fk_close_reason_classify_id', 'close_reason_classify_id');
}
    
}
