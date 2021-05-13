<?php

namespace App\Models;

use App\Managers\PatientManager;
use App\Traits\DataCryptable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReferCase extends Model
{
    use HasFactory, SoftDeletes, DataCryptable;

    protected $guarded = [];

    protected $casts = [
        'submitted_at' => 'datetime',
        'meta' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'status',
        'updated_at_for_humans',
    ];

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

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'draft' => 'ร่าง',
            'submitted' => 'รอ',
            'admitted' => 'แอดมิท',
            'discharged' => 'จำหน่าย',
            'canceled' => 'ยกเลิก',
        ];

        return $statuses[$this->meta['status']];
    }

    public function setStatusAttribute($value)
    {
        return $this->updat(['meta->status', $value]);
    }

    public function getStatusAttribute()
    {
        return $this->meta['status'];
    }

    public function getUpdatedAtForHumansAttribute()
    {
        return $this->updated_at->locale('th_TH')->diffForHumans(now());
    }

    public function getNameAttribute()
    {
        return $this->patient ? $this->patient->full_name : ($this->patient_name ?? 'ยังไม่มีชื่อ');
    }

    public function getHnAttribute()
    {
        return $this->patient ? $this->patient->hn : ($this->hn ?? null);
    }

    public function getAnAttribute()
    {
        return $this->admission ? $this->admission->an : ($this->an ?? null);
    }

    public function scopeWithFilterUserCenter($query, $userCenterId)
    {
        if ($userCenterId !== config('app.main_center_id')) {
            $query->whereHas('center', function ($query) use ($userCenterId) {
                $query->where('centers.id', $userCenterId);
            });
        }
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('meta->status', $status);
        })->when($filters['center'] ?? null, function ($query, $center) {
            $query->whereHas('center', function ($query) use ($center) {
                $query->where('centers.name', $center);
            });
        });
    }

    public function updateHn($hn)
    {
        $oldHn = $this->patient ? $this->patient->hn : null;
        if ($oldHn === $hn) {
            return true;
        }

        $patient = (new PatientManager())->manage($hn);

        if (! $patient['found']) {
            $this->patient_name = 'HN '.$hn.' not found';
            $this->patient_id = null;
            $this->save();

            return false;
        }

        $this->patient_name = $patient['patient']->full_name;
        $this->patient_id = $patient['patient']->id;

        return $this->save();
    }

    public function cancel($actor, $reason)
    {
        $meta = $this->meta;
        $meta['status'] = 'canceled';
        $meta['cancel_by'] = $actor;
        $meta['cancel_reason'] = $reason;
        $meta['canceled_at'] = now()->format('Y-m-d H:i:s');
        $this->meta = $meta;

        return $this->save();
    }
}
