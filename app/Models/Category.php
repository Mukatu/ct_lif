<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug'
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function trainings(): HasMany
    {
        return $this->hasMany(Training::class);
    }
}