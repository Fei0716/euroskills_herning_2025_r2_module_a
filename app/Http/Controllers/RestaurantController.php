<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestaurantController extends Controller
{
    public function index(){
        $restaurant = Restaurant::join('reviews', 'reviews.restaurantId' , 'restaurants.id')->select('restaurants.*' , DB::raw('AVG(reviews.rating) AS rating'))
        ->groupBy('restaurants.id','restaurants.name','restaurants.city','restaurants.cuisine','restaurants.address', 'restaurants.zipCode' ,'restaurants.countryCode','restaurants.description' , 'restaurants.imageUrl')
            ->get();
        return response()->json( $restaurant, 200);
    }
}
