<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    // 👇 IMPORTANT: your table name is NOT default
    protected $table = 'sfdcomplaints';

    // 👇 Primary key is NOT 'id'
    protected $primaryKey = 'ComplaintID';

    // 👇 Your table does NOT use Laravel timestamps
    public $timestamps = false;

    // 👇 Optional: allow mass assignment
    protected $fillable = [
        'ComplaintTitle',
        'ComplaintText',
        'ComplaintType',
        'ComplainerName',
        'ComplainerEmail',
        'ComplainerPhone',
        'ComplaintDate',
        'ComplaintStatus',
        'ComplaintNationalID',
        'ComplainerGender',
        'RequestType',
        'office',
        'department',
        'ComplaintGovernorate',
        'fk_close_reason_id',
        'fk_close_reason_classify_id',

    ];

    protected $attributes = [
        'fk_close_reason_id' => 0,
        'fk_close_reason_classify_id' => 0,
    ];

    public function sources()
    {
        return $this->belongsToMany(
            Comsource::class,
            'complaint_sources',
            'complaint_id',
            'comsource_id'
        );
    }
    public function responses()
    {
        return $this->hasMany(ComplaintResponse::class, 'complaint_id', 'ComplaintID');
    }
}
