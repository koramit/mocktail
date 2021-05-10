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
     * Get the patient.
     */
    public function patient()
    {
        return $this->hasOneThrough(
            Patient::class, // target
            Admission::class, // via
            'id', // selected key on the via table...
            'id', // selected key on the target table...
            'admission_id', // link key this table => via table...
            'patient_id', // link key via table => target table...
        );
    }
}
