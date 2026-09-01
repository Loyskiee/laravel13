<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Console\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable('user_id', 'name', 'description')]
#[Hidden('user_id')]
class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
