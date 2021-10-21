<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public function children()
    {
        return $this->hasMany(Comment::class, 'parent');
    }

    public function scopeRoots($query)
    {
        return $query->whereNull('parent');
    }
}
