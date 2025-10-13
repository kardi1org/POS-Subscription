<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Renewal extends Model
{
    use HasFactory;

    protected $fillable = [
        'pricing_id',
        'duration',
        'old_end_date',
        'new_end_date',
        'approved_by',
    ];

    protected $dates = [
        'old_end_date',
        'new_end_date',
    ];

    public function pricing()
    {
        return $this->belongsTo(Pricing::class);
    }
}
