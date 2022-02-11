<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checklist extends Model
{
    use HasFactory, SoftDeletes;

    protected static function boot() {
        parent::boot();
    
        // database cascading deletes don't work for soft deletes, so handle that here
        static::deleting(function($checklist) {
            $checklist->todos()->delete();
        });

        static::restoring(function($checklist) {
            $checklist->todos()->restore();
        });
    }

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function todos() 
    {
        return $this->hasMany(Todo::class);
    }

    public function scopeIncomplete($query)
    {
        $query->whereDoesntHave('todos', function (Builder $query) {
            $query->incomplete();
        })->get();
    }

    protected $fillable = ['title'];

    protected $appends = ['is_complete'];
}
