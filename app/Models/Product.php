<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'Dim_Products';

    protected $primaryKey = 'ProductID'; 


    public function orders()
    {
        return $this->hasMany(Order::class, 'ProductID');
    }
}
