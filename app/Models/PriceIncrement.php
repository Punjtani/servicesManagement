<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceIncrement extends Model
{
    protected $table = 'price_increment';
    use HasFactory;
    public function service()
    {
        return $this->hasOne(Service::class, 'id', 'service_id');
    }
    // public function client()
    // {
    //     return $this->hasOne(Client::class, 'id', 'client_id');
    // }
    public function property()
    {
        return $this->hasOne(ClientProperties::class, 'id', 'property_id');
    }
}
