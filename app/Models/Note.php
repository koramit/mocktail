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
}
