<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectType extends Model
{
    protected $table = 'sectors_ben';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        // 'activ_nama',
        'sector_nama',
    ];
}