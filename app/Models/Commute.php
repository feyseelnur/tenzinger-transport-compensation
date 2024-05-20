<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commute extends Model
{
    use HasFactory;
    protected $fillable = ['employee_id', 'transport_id', 'distance', 'workdays_per_week'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
    public function transport(): BelongsTo
    {
        return $this->belongsTo(Transport::class);
    }
}
