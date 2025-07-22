<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class CrmContact extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'company',
        'position',
        'address',
        'status',
        'deal_value',
        'last_contact_date',
        'user_id',
        'tenant_id',
        'custom_fields',
    ];

    protected $casts = [
        'deal_value' => 'decimal:2',
        'last_contact_date' => 'date',
        'custom_fields' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function scopeLeads($query)
    {
        return $query->where('status', 'lead');
    }

    public function scopeCustomers($query)
    {
        return $query->where('status', 'customer');
    }

    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'inactive');
    }
}
