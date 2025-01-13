<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiseaseDetection extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_path',
        'disease_name',
        'description',
        'remedy',
        'other_recommendations',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}