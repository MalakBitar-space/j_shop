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
