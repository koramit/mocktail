<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Center extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'name_short',
    ];

    public static function findByName($name)
    {
        $centers = static::cache();
        $index = $centers->search(function ($item) use ($name) {
            return $item->name === $name;
        });

        return $index === false ? null : $centers[$index];
    }

    public static function findByNameShort($name)
    {
        $centers = static::cache();
        $index = $centers->search(function ($item) use ($name) {
            return $item->name_short === $name;
        });

        return $index === false ? null : $centers[$index];
    }

    public static function cache()
    {
        return Cache::rememberForever('centers', function () {
            return static::all();
        });
    }
}
