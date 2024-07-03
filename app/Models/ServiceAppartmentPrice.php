<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceAppartmentPrice extends Model
{
    protected $table = 'client_service_appartment_pricing';
    public $timestamps = false;
    use HasFactory;
}
