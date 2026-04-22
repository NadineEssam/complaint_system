<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ProjectType extends Model
{
    use HasFactory;
    protected $table = 'projecttype';

    protected $primaryKey = 'projecttypeid';

    public $timestamps = false;

    protected $guarded=['projecttypeid'];





}
