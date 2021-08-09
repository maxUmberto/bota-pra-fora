<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function passwordResets() {
        return $this->hasMany(PasswordReset::class);
    }

    public function vents() {
        return $this->hasMany(Vent::class);
    }

    public function ventViews() {
        return $this->hasMany(VentView::class);
    }
    
    public function ventComments() {
        return $this->hasMany(VentComment::class);
    }

    public function reactions() {
        return $this->hasMany(Reaction::class);
    }

    public function ventReactions() {
        return $this->belongsToMany(Vent::class)->with();
    }
}
