<?php

namespace App\Models;

use App\Traits\DataCryptable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReferCase extends Model
{
    use HasFactory, SoftDeletes, DataCryptable;

    protected $guarded = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['status'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function referer()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function note()
    {
        return $this->belongsTo(Note::class);
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

    /**
     * Set field 'hn'.
     *
     * @param string $value
     */
    public function setPatientNameAttribute($value)
    {
        $this->attributes['patient_name'] = $this->encryptField($value);
    }

    /**
     * Get field 'hn'.
     *
     * @return string
     */
    public function getPatientNameAttribute()
    {
        return $this->decryptField($this->attributes['patient_name']);
    }

    public function getStatusAttribute()
    {
        return $this->submitted_at ? 'ส่งแล้ว' : 'ร่าง';
    }

    public function scopeWithFilterUserCenter($query, $userCenterId)
    {
        if ($userCenterId !== 1) {
            $query->whereHas('center', function ($query) use ($userCenterId) {
                $query->where('centers.id', $userCenterId);
            });
        }
    }
}
