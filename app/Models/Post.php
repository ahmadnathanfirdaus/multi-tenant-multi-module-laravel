<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Post extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'title',
        'content',
        'user_id',
        'tenant_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
