<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ServiceCategory extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = ['category_name', 'category_desc', 'image'];
    public function services()
    {
        return $this->hasMany(Service::class, 'category_id');
    }
    
}
