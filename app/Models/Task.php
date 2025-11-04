<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'author_id',
        'assignee_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    // Проєкт, до якого належить задача
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    // Автор задачі
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Виконавець задачі
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    // Коментарі до задачі
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
