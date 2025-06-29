<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public function category()
{
    return $this->belongsTo(ServiceCategory::class, 'category_id');
}

public function providers()
{
    return $this->hasMany(ServiceProvider::class, 'service_id');
}


}
