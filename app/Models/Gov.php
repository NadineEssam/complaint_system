<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gov extends Model
{
    protected $connection = 'ben';
    protected $table = 'GOV_CODE';

    protected $primaryKey = 'GOVT_CODE';

    public $incrementing = false;   // not an auto-increment integer
    protected $keyType = 'string';  // key is a string, not int

    public $timestamps = false;

    protected $fillable = [
        'GOVT_NAMA'
    ];

    public function offices()
    {
        return $this->hasMany(Office::class, 'FK_GOVT_CODE', 'GOVT_CODE');
    }
}

