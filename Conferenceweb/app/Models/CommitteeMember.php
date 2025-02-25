<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommitteeMember extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'name', 'designation', 'role'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}

