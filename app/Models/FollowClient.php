<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowClient extends Model
{
    use HasFactory;

    public function buyer() {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function supplyer() {
        return $this->belongsTo(User::class, 'supplyer_id');
    }
}
