<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Client;
use App\Models\Task;
use App\Models\User;
use App\Enums\ProjectStatus;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'client_id',
        'title',
        'description',
        'status',
        'end_date',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function endDate(): Attribute
    {
        return Attribute::make(
            get: fn($value) => date('d/m/Y', strtotime($value)),
        );
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => date('d/m/Y', strtotime($value)),
        );
    }

    protected function casts(): array
    {
        return [
            'status' => ProjectStatus::class,
        ];
    }
}
