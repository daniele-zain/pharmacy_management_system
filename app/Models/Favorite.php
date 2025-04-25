<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Favorite extends Model
{
    use HasFactory;
    protected $fillable=['user_id','medicine_id'];

    public function medicine(){
        return $this->belongsTo(medicine::class);
    }
    public function User(){
        return $this->belongsTo(User::class);
    }
}
