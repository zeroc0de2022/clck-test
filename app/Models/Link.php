<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'original_url'];

    // Связь: У одной ссылки много посещений
    public function visits()
    {
        return $this->hasMany(LinkVisit::class);
    }
}

