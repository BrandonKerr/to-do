<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checklist extends Model
{
    use HasFactory, SoftDeletes;

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    // public function todos() 
    // {
    //     return $this->hasMany(Todo::class);
    // }

    public function getIsCompleteAttribute()
    {
        return true;
        // TODO make scopeComplete for todos
        // return $this->todos()->complete()->count() == $this->todos()->count();
    }

    // TODO might be able to do this with subquery scope where the list doesnthave todos that are incomplete
    // public function scopeIncomplete($query)
    // {
    //     $query->where('is_complete', false);
    // }


    protected $fillable = ['title'];

    protected $appends = ['is_complete'];
}
