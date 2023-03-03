<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Todo
 *
 * @property int $id
 * @property string $title
 * @property bool $is_complete
 * @property int $checklist_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Checklist $checklist
 * @property-read mixed $user
 * @method static \Illuminate\Database\Eloquent\Builder|Todo incomplete()
 * @method static \Illuminate\Database\Eloquent\Builder|Todo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Todo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Todo onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Todo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Todo whereChecklistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Todo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Todo whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Todo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Todo whereIsComplete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Todo whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Todo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Todo withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Todo withoutTrashed()
 * @mixin \Eloquent
 */
class Todo extends Model {
    use HasFactory;
    use SoftDeletes;

    public function checklist() {
        return $this->belongsTo(Checklist::class);
    }

    public function getUserAttribute() {
        return $this->loadMissing("checklist")->checklist->user;
    }

    public function scopeIncomplete($query) {
        $query->where("is_complete", false);
    }

    protected $fillable = ["title", "is_complete"];

    protected $casts = [
        "is_complete" => "boolean",
    ];
}
