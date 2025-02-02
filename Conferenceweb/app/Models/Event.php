<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }
    
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
    
}
