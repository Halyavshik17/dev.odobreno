<?php

namespace Modules\Companies\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DataTableActionBtn;

// modules/Companies/Entities/Company.php
use App\Traits\ActionBtn;

class Company extends Model
{
    //use ActionBtn;
    use DataTableActionBtn;
    use HasFactory;

    protected $fillable = [
        'name',
        'ogrn',
        'inn',
        'kpp',
        'address_legal',
        'address_phys',
        'phone_number',
    ];
}