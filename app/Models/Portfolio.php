<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    public function provider()
{
    return $this->belongsTo(ServiceProvider::class, 'provider_id');
}

}
