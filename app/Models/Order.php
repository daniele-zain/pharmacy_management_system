<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'pharmacist_id',
        'medicine_id',
        'status_id',
        'quantity',
        'price',
        'payment'
    ];

    public function Pharmacist(){
        return $this->belongsTo(User::class);
    }

    public function status(){
        return $this->belongsTo(Status::class);
    }
}
