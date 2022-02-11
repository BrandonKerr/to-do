<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected static function boot() {
        parent::boot();
    
        // database cascading deletes don't work for soft deletes, so handle that here
        static::deleting(function($user) {
            $user->checklists()->delete();
        });

        static::restoring(function($user) {
            $user->checklists()->restore();
        });
    }

    public function checklists() 
    {
        return $this->hasMany(Checklist::class);
    }

    public function todos() 
    {
        return $this->hasManyThrough(Todo::class, Checklist::class);
    }

    public function getRoleDisplayAttribute()
    {
        return  $this->is_admin ? 'Admin' : 'User';
    }
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean'
    ];
    
}
