<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'type',
        'thumbnail_path'
    ];

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }
}