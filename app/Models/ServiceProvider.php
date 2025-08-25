<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ServiceProvider extends Model implements HasMedia
{
    use InteractsWithMedia;

    // Add fillable fields for mass assignment
    protected $fillable = [
        'user_id',
        'service_id',
        'specialization',
        'professional_desc',
        'years_of_experience',
        'min_price',
        'phone_number',
        'profile_img',
        'identity_img',
        'address',
        'cover_img',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'provider_id');
    }

    public function publishRequests()
    {
        return $this->hasMany(PublishRequest::class, 'provider_id');
    }

    public function portfolio()
    {
        return $this->hasOne(Portfolio::class, 'provider_id');
    }
}
