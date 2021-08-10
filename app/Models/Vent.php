<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vent extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'vent_content',
        'allow_comments'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'allow_comments' => 'boolean',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function ventViews() {
        return $this->hasMany(VentView::class);
    }

    public function ventComments() {
        return $this->hasMany(VentComment::class);
    }

    public function reactions() {
        return $this->belongsToMany(Reaction::class);
    }
}
