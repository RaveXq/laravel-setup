<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'task_id',
        'author_id',
        'body',
    ];

    // Задача, до якої належить коментар
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    // Автор коментаря
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
