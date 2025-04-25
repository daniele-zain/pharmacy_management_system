<?php

namespace App\Models;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'warehouse_manager_id',
        'category_id',
        'generic_name',
        'scientific_name',
        'description',
        'price',
        'quantity',
        'company',
        'class',
        'expiration_date'
    ];

    public function warehouse_manager(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

}
