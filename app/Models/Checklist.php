<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Checklist
 *
 * @property int $id
 * @property string $title
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Todo> $todos
 * @property-read int|null $todos_count
 * @property-read \App\Models\User $user
 * @method static Builder|Checklist complete()
 * @method static Builder|Checklist incomplete()
 * @method static Builder|Checklist newModelQuery()
 * @method static Builder|Checklist newQuery()
 * @method static Builder|Checklist onlyTrashed()
 * @method static Builder|Checklist query()
 * @method static Builder|Checklist whereCreatedAt($value)
 * @method static Builder|Checklist whereDeletedAt($value)
 * @method static Builder|Checklist whereId($value)
 * @method static Builder|Checklist whereTitle($value)
 * @method static Builder|Checklist whereUpdatedAt($value)
 * @method static Builder|Checklist whereUserId($value)
 * @method static Builder|Checklist withTrashed()
 * @method static Builder|Checklist withoutTrashed()
 * @mixin \Eloquent
 */
class Checklist extends Model {
    use HasFactory;
    use SoftDeletes;

    protected static function boot() {
        parent::boot();

        // database cascading deletes don't work for soft deletes, so handle that here
        static::deleting(function ($checklist) {
            $checklist->todos()->delete();
        });

        static::restoring(function ($checklist) {
            $checklist->todos()->restore();
        });
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function todos() {
        return $this->hasMany(Todo::class);
    }

    public function scopeIncomplete($query) {
        $query->whereDoesntHave("todos")
        ->orWhereHas("todos", function (Builder|Todo $query) {
            $query->incomplete();
        })->get();
    }

    public function scopeComplete($query) {
        $query->whereHas("todos")
            ->whereDoesntHave("todos", function (Builder|Todo $query) {
                $query->incomplete();
            })
            ->get();
    }

    protected $fillable = ["title"];
}
