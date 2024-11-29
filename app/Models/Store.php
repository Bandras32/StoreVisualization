<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = 'Dim_Stores';

    protected $primaryKey = 'StoreID';

    public function orders()
    {
        return $this->hasMany(Order::class, 'StoreID');
    }

}
