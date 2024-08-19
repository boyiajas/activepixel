<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo_id',
        'photo_type',
        'file_name',
        'file_path',
        'extension',
    ];
}
