<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function my_favorites(){

        if (Auth::user()->type == 0){
            return response('You do not have permission',400);
        }

        $favorites=DB::table('medicines')
        ->join('favorites','medicines.id','=','favorites.medicine_id')
        ->select('*')
        ->where('favorites.user_id',Auth::user()->id)
        ->get();
        if(count($favorites)==0){
            return response("You have No Favorite medicines ",400);
        }
        return response($favorites,200);
    }

    public function change_to($medicine_id){

        if (Auth::user()->type == 0){
            return response('You do not have permission',400);
        }

        $med= Medicine::find($medicine_id);
        if(is_null($med)){
            return response("medicine Not Found",400);
        }

        $check=Favorite::where('medicine_id',$medicine_id)
        ->where('user_id',Auth::user()->id)->first();
        //IF not found then it is not added to fav before
        //then clicking the love-button will add it to fav
        if(is_null($check)){
            Favorite::create([
                'user_id'=>Auth::user()->id,
                'medicine_id'=>$medicine_id
            ]);
            return response("medicine Added To Favorites Successfully",200);
        }
        else{
            $check->delete();
            return response("medicine Has Been Removed From Favorites Successfully",200);

        }
    }
}
