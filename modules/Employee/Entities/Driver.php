<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Employee\Database\factories\DriverFactory;

use Modules\Companies\Entities\Company;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'driver_code',
        'phone',
        //'license_type_id',
        'license_num',
        'license_issue_date',
        'nid',
        'license_expiry_date',
        'authorization_code',
        'dob',
        'joining_date',
        'working_time_slot',
        'leave_status',
        'present_address',
        'permanent_address',
        'avatar_path',
        'is_active',
    ];

    protected static function newFactory()
    {
        return DriverFactory::new();
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($driver) {
            $driver->driver_code = 'DRI'.\str_pad($driver->id, 4, '0', STR_PAD_LEFT);
            $driver->save();
        });
    }

    /**
     * Get the file url attribute.
     *
     * @return ?string
     */
    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar_path ? storage_asset($this->avatar_path) : null;
    }

    public function licenseTypes()
    {
        return $this->belongsToMany(LicenseType::class, 'driver_license_types', 'driver_id', 'license_type_id');
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_drivers', 'driver_id', 'company_id');
    }

    public function primaryCompany(): ?Company
    {
        return $this->companies()->first();
    }
}
