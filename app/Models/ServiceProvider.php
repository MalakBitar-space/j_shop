<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceProvider extends Model
{
    //
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
