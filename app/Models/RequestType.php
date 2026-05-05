<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestType extends Model
{
    protected $table = 'requesttype';
    protected $primaryKey = 'requesttypeid';
    public $timestamps = false;


public function complaints()
{
    return $this->hasMany(\App\Models\Complaint::class, 'RequestType', 'requesttypeid');
}
}