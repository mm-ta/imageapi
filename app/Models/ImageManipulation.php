<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageManipulation extends Model
{
    use HasFactory;

    const TYPE_RESIZE = 'resize';
    const UPDATED_AT = false;

    protected $fillable = [
        'name',
        'path',
        'output_path',
        'type',
        'data',
        'album_id',
        'user_id'
    ];
}
