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
        'sector_id',
        'department',
        'ComplaintGovernorate',
        'fk_close_reason_id',
        'fk_close_reason_classify_id',
        'created_by',
        'updated_by',
        'UpdateUser',
        'username',
        'complaint_type',
        'ComplaintProjectType',
        

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
        'sector_id',
        'sec_id'
    );
}


public function departmentInfo()
{
    return $this->belongsTo(
        Department::class,
        'department',
        'dep_id'
    );
}

public function gov()
{
    return $this->belongsTo(
        Gov::class,
        'ComplaintGovernorate',
        'GOVT_CODE'
    );
}

public function complainerGov()
{
    return $this->belongsTo(Gov::class, 'ComplainerGovernorate', 'GOVT_CODE');
}

public function office_info()
{
    return $this->belongsTo(
        Office::class,
        'office',
        'ID'
    );
}
public function projectTypes()
{
    return $this->belongsTo(
        ProjectType::class,
        'ComplaintProjectType',
        'ID'
    );
}
}
