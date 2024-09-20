<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'slug', 'category_type', 'location'];

    /**
     * Constants for category types.
     */
    const CATEGORY_TYPE_CLUB = 'Club';
    const CATEGORY_TYPE_NO_CLUB = 'No Club';

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}
