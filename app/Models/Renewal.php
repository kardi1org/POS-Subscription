<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Renewal extends Model
{
    use HasFactory;

    protected $fillable = [
        'pricing_id',
        'old_package',
        'new_package',
        'duration',
        'total_price',
        'status',
        'bukti_transfer',
        'old_end_date',
        'new_end_date',
        'approved_by',
    ];


    protected $dates = [
        'old_end_date',
        'new_end_date',
    ];

    // public function pricing()
    // {
    //     return $this->belongsTo(Pricing::class);
    // }


    public function package()
    {
        return $this->belongsTo(\App\Models\Package::class, 'new_package', 'id');
    }

    public function pricing()
    {
        return $this->belongsTo(Pricing::class, 'pricing_id');
    }

    /**
     * Relasi user diambil dari Pricing
     */
    public function user()
    {
        // lewat relasi pricing -> user
        return $this->hasOneThrough(
            User::class,       // model tujuan
            Pricing::class,    // model perantara
            'id',              // foreign key di Pricing (id Pricing)
            'id',              // primary key di User
            'pricing_id',      // foreign key di Renewal
            'user_id'          // foreign key di Pricing
        );
    }
}
