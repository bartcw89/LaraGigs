<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Listing extends Model
{
    use HasFactory;

    public function scopeFilter(mixed $query, array $filters) {
        if ($filters['tag'] ?? false)
            $query->where('tags', 'like', '%' . request('tag') . '%');

        if ($filters['search'] ?? false) {
            $query->where('title', 'like', '%' . request('search') . '%')
                ->orWhere('description', 'like', '%' . request('search') . '%')
                ->orWhere('tags', 'like', '%' . request('search') . '%');
        }
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }
}
