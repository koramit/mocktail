<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [];
    protected $casts = ['contents' => 'array'];

    public function referCase()
    {
        return $this->hasOne(ReferCase::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    /**
     * Get the case's center.
     */
    public function center()
    {
        return $this->hasOneThrough(
            Center::class, // target
            User::class, // via
            'id', // selected key on the via table...
            'id', // selected key on the target table...
            'user_id', // link key this table => via table...
            'center_id', // link key via table => target table...
        );
    }
}
