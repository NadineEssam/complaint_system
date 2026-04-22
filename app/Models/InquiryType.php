<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class InquiryType extends Model
{
    use HasFactory;
    protected $table = 'inquirytypes';

    protected $primaryKey = 'inquiryID';

    public $timestamps = false;

    protected $guarded=['inquiryID'];





}
