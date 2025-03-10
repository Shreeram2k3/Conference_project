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

    protected $fillable = [
        'event_name', 'description', 'start_date', 'end_date', 'location', 'max_participants','sample_paper','category','image'
    ];
    
    public function timelines()
    {
        return $this->hasMany(Timeline::class);
    }
    

  
public function committeeMembers()
{
    return $this->hasMany(CommitteeMember::class, 'event_id');
}

    
}
