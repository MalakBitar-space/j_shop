<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

public function provider()
{
    return $this->belongsTo(ServiceProvider::class, 'provider_id');
}

}
