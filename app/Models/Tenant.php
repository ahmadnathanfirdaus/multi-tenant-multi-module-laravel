<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'subdomain',
        'domain',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'tenant_modules')
                    ->withPivot('is_enabled', 'settings')
                    ->withTimestamps();
    }

    public function enabledModules()
    {
        return $this->modules()->wherePivot('is_enabled', true);
    }

    public function getRouteKeyName()
    {
        return 'subdomain';
    }
}
