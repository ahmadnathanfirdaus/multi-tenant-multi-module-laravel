<?php

namespace App\Traits;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            // Skip scoping for superadmin users - but be defensive about Auth calls
            try {
                if (Auth::check()) {
                    $user = Auth::user();
                    if ($user && isset($user->role) && $user->role === 'superadmin') {
                        return;
                    }
                }
            } catch (\Exception) {
                // If there's any issue with Auth, continue with tenant scoping
            }

            if (app()->bound('tenant')) {
                $tenant = app('tenant');
                if ($tenant && isset($tenant->id)) {
                    $builder->where('tenant_id', $tenant->id);
                }
            }
        });

        static::creating(function (Model $model) {
            if (app()->bound('tenant')) {
                $tenant = app('tenant');
                if ($tenant && isset($tenant->id)) {
                    $model->tenant_id = $tenant->id;
                }
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
