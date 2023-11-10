<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpreadsheetFlag extends Model
{
    use HasFactory;

    protected $table = 'spreadsheet_flags';

    protected $guarded = []; //this will give us the ability to mass assign properties to the model
}
