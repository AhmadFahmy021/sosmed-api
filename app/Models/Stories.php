<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stories extends Model
{
    use HasFactory;

    protected $fillable = [
        'foto', 'user_id', 'caption'
    ];

    // Relasi belongsTo dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
