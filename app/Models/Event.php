<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'slug',
        /* 'start_date',
        'end_date', */
        'location',
        'event_image',
    ];

    protected $dates = ['start_date', 'end_date'];

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_event');
    }

    public function getEventImageUrlAttribute()
    {
        return $this->event_image ? asset($this->event_image) : asset('assets/img/placeholder.png');
    }
}
