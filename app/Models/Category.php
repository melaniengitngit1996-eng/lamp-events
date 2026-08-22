<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Category extends Model
{
    protected $fillable = [
        'name',
        'min_age',
        'max_age',
    ];

    public static function getByAge(int $age)
    {
        return static::query()
            ->whereNotNull('min_age')
            ->where('min_age', '<=', $age)
            ->where(function ($query) use ($age) {
                $query->whereNull('max_age')
                    ->orWhere('max_age', '>=', $age);
            })
            ->first();
    }

    public static function getByBirthdate(?string $birthdate): ?self
    {
        if (empty($birthdate)) {
            return null;
        }

        $age = Carbon::parse($birthdate)->age;

        return static::query()
            ->whereNotNull('min_age')
            ->where('min_age', '<=', $age)
            ->where(function ($query) use ($age) {
                $query->whereNull('max_age')
                    ->orWhere('max_age', '>=', $age);
            })
            ->first();
    }
}
