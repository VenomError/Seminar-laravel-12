<?php

namespace App\Models;

use App\Enum\SeminarStatus;
use App\Models\User;
use App\Models\Registration;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class Seminar extends Model
{
    use HasSlug;
    protected $fillable = [
        'created_by',
        'title',
        'slug',
        'description',
        'location',
        'date_start',
        'quota',
        'price',
        'status',
        'thumbnail',
    ];

    protected $casts = [
        'date_start' => 'datetime',
        'price' => 'decimal:2',
        'status' => SeminarStatus::class
    ];
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    // Relasi: Pembuat seminar
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi: Daftar peserta
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    // Helper untuk cek apakah berbayar
    public function isPaid()
    {
        return $this->price > 0;
    }
}