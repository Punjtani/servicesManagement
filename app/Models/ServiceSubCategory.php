<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceSubCategory extends Model
{
    protected $table = 'service_sub_categories';
    use HasFactory;

    public function Services()
    {
        return $this->belongsTo(Service::class, 'parent_category_id', 'id');
    }
    public function ServiceSubCategoryAppartmentPrice()
    {
        return $this->hasMany(ServiceSubCategoryAppartmentPrice::class, 'sub_service_id', 'id');
    }
    public function ClientPricing()
    {
        return $this->hasMany(ClientPricing::class, 'sub_service_id', 'id');
    }
}
