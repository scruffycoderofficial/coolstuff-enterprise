<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpreadsheetData extends Model
{
    use HasFactory;

    protected $table = 'data';

    protected $guarded = [];

    public $timestamps = false; //disable time stamps for this
}
