<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    use HasFactory;

    protected $table = 'pricings'; // Nama tabel jika berbeda dari default

    protected $fillable = [
        'codepaket',
        'namapaket',
        'harga_paket',
        'durasi',
        'email',
        'status',
        'bukti_transfer',
        'reminder_sent_at', // ← wajib ditambahkan
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'reminder_sent_at' => 'datetime',
    ];


    public function renewals()
    {
        return $this->hasMany(Renewal::class);
    }

    public function package()
    {
        return $this->belongsTo(\App\Models\Package::class, 'codepaket');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'email', 'email');
    }
}
