<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class files extends Model
{
    //
        protected $fillable = [
        'user_id',
        'original_name',
        'stored_name',
        'path',
        'mime_type',
        'size',
    ];
}
