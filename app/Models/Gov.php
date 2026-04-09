<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gov extends Model
{
    protected $table = 'govs';          // table name

    protected $primaryKey = 'govsid';   // primary key

    public $timestamps = false;         

    protected $fillable = [
        'govname'
    ];

  public function offices()
{
    return $this->hasMany(Office::class, 'FK_GOVT_CODE', 'govsid');
}
}