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

    public function referer()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function note()
    {
        return $this->belongsTo(Note::class);
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
}
