<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'title', 'description', 'date'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
