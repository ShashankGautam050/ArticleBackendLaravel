<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    use HasFactory;
    protected $fillable = ['heading_id', 'image_path'];

    public function heading()
    {
        return $this->belongsTo(Heading::class);
    }
}
