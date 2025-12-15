<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'pricing_id',
        'name',
        'email',
        'userpassword',
        'level',
        'db_database',
        'db_host',
        'db_port',
        'db_username',
        'db_password',
    ];


    // Relasi ke Pricing
    public function pricing()
    {
        return $this->belongsTo(Pricing::class);
    }
}
