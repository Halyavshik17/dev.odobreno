<?php

namespace Modules\Employee\Entities;

use App\Traits\FormatTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Modules\Companies\Entities\Company;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Position extends Model
{
    use FormatTimestamps;
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_positions', 'position_id', 'company_id');
    }

    public function primaryCompany(): ?Company
    {
        return $this->companies()->first();
    }
}
