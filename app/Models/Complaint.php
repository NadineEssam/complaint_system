<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{

    protected $table = 'sfdcomplaints';

    protected $primaryKey = 'ComplaintID';

    public $timestamps = false;

    protected $fillable = [
        'ComplaintTitle',
        'ComplaintText',
        'ComplaintType',
        'ComplainerName',
        'ComplainerEmail',
        'ComplainerPhone',
        'ComplainerGovernorate',
        'ComplaintDate',
        'ComplaintStatus',
        'ComplaintNationalID',
        'ComplainerGender',
        'ComplaintSources',
        'RequestType',
        'office',
        'department',
        'ComplaintGovernorate',
        'fk_close_reason_id',
        'fk_close_reason_classify_id',
        'created_by',
        'updated_by',
        'UpdateUser',
        'username'

    ];

    protected $attributes = [
        'fk_close_reason_id' => 0,
        'fk_close_reason_classify_id' => 0,
        'ComplaintStatus' => 3,
    ];


    public function responses()
    {
        return $this->hasMany(ComplaintResponse::class, 'complaint_id', 'ComplaintID');
    }

    public function status()
    {
        return $this->belongsTo(
            CompStatus::class,
            'ComplaintStatus',
            'statusID'
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

    public function departmentData()
    {
        return $this->belongsTo(
            Department::class,
            'department',        
            'department_id'      
        );
    }


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function sources()
{
    return $this->belongsToMany(
        ComSource::class,
        'complaint_sources',
        'complaint_id',
        'comsource_id'
    );
}
public function requestType()
{
    return $this->belongsTo(
        RequestType::class,
        'RequestType',
        'requesttypeid'
    );
}

public function complaintType()
{
    return $this->belongsTo(
        ComplaintType::class,
        'ComplaintType',
        'comtypeid'
    );
}

public function sector()
{
    return $this->belongsTo(
        Sector::class,
        'department',
        'sector_id'
    );
}

public function gov()
{
    return $this->belongsTo(
        Gov::class,
        'ComplaintGovernorate',
        'govsid'
    );
}

public function complainerGov()
{
    return $this->belongsTo(Gov::class, 'ComplainerGovernorate', 'govsid');
}

public function office_info()
{
    return $this->belongsTo(
        Office::class,
        'office',
        'ID'
    );
}
}
