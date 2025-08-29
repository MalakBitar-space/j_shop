<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Service extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'category_id',
        'service_title',
        'service_desc',
        'service_creator_name',
        'service_creator_address',
        'service_creator_phone_number',
        'service_price',
    ];

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function providers()
    {
        return $this->hasMany(ServiceProvider::class, 'service_id');
    }
}
