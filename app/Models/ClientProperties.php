<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientProperties extends Model
{
    protected $table = 'client_properties';
    use HasFactory, SoftDeletes;
//    static function boot(){
//        parent::boot();
//         static::addGlobalScope('active', function (Builder $builder) {
//            $builder->where('isActive', true);
//        });
//    }
    public function taxType(){
        return $this->hasOne(TaxType::class, 'id','tax_type_id');
    }

    public function client(){

        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

     public function appartment_types()
    {
        return $this->belongsToMany(AppartmentType::class);
    }

    public function quoteAttatchment(){

        return $this->hasMany(QuoteAttatchment::class, 'client_properties_id', 'id');
    }

    // public function manager(){

    //     return $this->hasMany(User::class, 'id', 'staff_id');
    // }

    public function paint_spec()
    {
        return $this->hasMany(ClientpaintSpec::class,'property_id', 'id');
    }

    public function pricing()
    {
        return $this->hasMany(ClientPricing::class,'property_id', 'id');
    }

    public function client_pricing()
    {
        return $this->hasMany(ClientServicePricing::class,'property_id', 'id');
    }

    public function refinish_spec()
    {
        return $this->hasMany(ClientpaintSpec::class,'property_id', 'id');
    }

    public function jobs()
    {
        return $this->hasMany(Job::class,'property_id', 'id');
    }

    public function staff(){

        return $this->belongsToMany(Staff::class, 'staff_client_properties')->with(['staff_roles','user']);
    }
}
