<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompCloseReasonClassify extends Model
{
     protected $table = 'comp_close_reason_classify';
    protected $primaryKey = 'close_reason_classify_id';
    public $timestamps = true;
    
    protected $fillable = [
        'close_reason_classify_Name',
        'fk_close_reason_id',
        'validity',
        'created_by',
        'updated_by'
    ];
   public function closeReason()
    {
        return $this->belongsTo(CompCloseReason::class, 'fk_close_reason_id', 'close_reason_ID');
    }
 
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
 
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
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