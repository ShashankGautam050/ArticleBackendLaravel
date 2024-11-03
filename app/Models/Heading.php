<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Heading extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'content', 'article_id'];
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
