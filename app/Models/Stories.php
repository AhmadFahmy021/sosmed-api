<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stories extends Model
{
    //

    protected $table = 'stories';
    protected $fillable = ['foto', 'user_id', 'caption'];
}
