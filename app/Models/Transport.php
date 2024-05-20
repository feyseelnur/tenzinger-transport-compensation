<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transport extends Model
{

    public const TYPE_BIKE = 'Bike';
    public const TYPE_CAR = 'Car';
    public const TYPE_BUS = 'Bus';
    public const TYPE_TRAIN = 'Train';
    protected $fillable = ['type', 'rate'];

    public function commutes(): HasMany
    {
        return $this->hasMany(Commute::class);
    }
}
