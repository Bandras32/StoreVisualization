<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'Dim_Customers';

    
    protected $primaryKey = 'CustomerID';

    public function customers()
    {
        return $this->hasMany(Order::class, 'CustomerID');
    }
}
