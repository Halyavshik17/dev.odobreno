<?php

namespace Modules\VehicleManagement\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Modules\Companies\Entities\Company;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Insurance extends Model
{
    use HasFactory;

    protected $fillable = [
        'insurance_company_id',
        'vehicle_id',
        'policy_number',
        'start_date',
        'end_date',
        'charge_payable',
        'deductible',
        'recurring_date',
        'recurring_period_id',
        'status',
        'add_reminder',
        'remarks',
        'policy_document_path',
    ];

    protected static function newFactory()
    {
        return \Modules\VehicleManagement\Database\factories\InsuranceFactory::new();
    }

    public function company()
    {
        return $this->belongsTo(VehicleInsuranceCompany::class, 'insurance_company_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function recurring_period()
    {
        return $this->belongsTo(VehicleInsuranceRecurringPeriod::class);
    }

    /**
     * Get the file url attribute.
     *
     * @return ?string
     */
    public function getPolicyDocumentUrlAttribute(): ?string
    {
        return $this->policy_document_path ? storage_asset($this->policy_document_path) : null;
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_insurances', 'insurance_id', 'company_id');
    }

    public function primaryCompany(): ?Company
    {
        return $this->companies()->first();
    }
}
