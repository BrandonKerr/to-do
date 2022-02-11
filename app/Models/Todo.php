<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Todo extends Model
{
    use HasFactory, SoftDeletes;

    public function checklist() 
    {
        return $this->belongsTo(Checklist::class);
    }

    public function getUserAttribute() 
    {
        return $this->checklist->user;
    }

    public function scopeIncomplete($query)
    {
        $query->where('is_complete', false);
    }

    protected $fillable = ['title', 'is_complete'];

    protected $casts = [
        'is_complete' => 'boolean'
    ];
}
