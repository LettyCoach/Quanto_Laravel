<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LP extends Model
{
    use HasFactory;

    protected $table = 'lps';

    public function statuses()
    {
        return $this->belongsTo(Status::class, 'status');
    }
}
