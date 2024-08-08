<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Restaurant;
use App\Models\User;
use App\Models\UserRestaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class RegistrationController extends Controller
{
    public function store(Request $request){
        $validated = $request->validate([
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'planId' => 'required|exists:plans,id',//check whether the plan exists
            'restaurants' => 'required|array',
            'restaurants.*.name' => 'required',//check for the object properties in the object array of restaurants
            'restaurants.*.city' => 'required',
            'restaurants.*.cuisine' => 'required',
            'restaurants.*.address' => 'required',
            'restaurants.*.zipCode' => 'required',
            'restaurants.*.countryCode' => 'required',
        ]);

        //check whether the plan match with the number of restaurants provided

        $plan = Plan::find($validated['planId']);
        if($plan->maxNumberOfRestaurants < count($validated['restaurants'])){
            return response()->json(['message' => 'The number of restaurants exceeds the limit of the selected plan'], 400);
        }

        //create the user
        $owner = new User();
        $owner->firstName = $validated['firstName'];
        $owner->lastName = $validated['lastName'];
        $owner->email = $validated['email'];
        $owner->password = Hash::make($validated['password']);
        $owner->isActive = 1;
        $owner->roleId = 2;
        $owner->planId = $validated['planId'];
        $owner->annualPayment = 0;
        $owner->save();

        //create the restaurants
        foreach($validated['restaurants'] as $r){
            $latestRestaurant = (int)Restaurant::latest('id')->first()->id + 1;
            $restaurant = new Restaurant();
            $restaurant->id = $latestRestaurant;
            $restaurant->name = $r['name'];
            $restaurant->city = $r['city'];
            $restaurant->cuisine = $r['cuisine'];
            $restaurant->address = $r['address'];
            $restaurant->zipCode = $r['zipCode'];
            $restaurant->countryCode = $r['countryCode'];
            $restaurant->save();

            //attach the restaurant to the user
            $userRestaurant = new UserRestaurant();
            $userRestaurant->userId = $owner->id;
        $userRestaurant->restaurantId =   $latestRestaurant;
            $userRestaurant->save();
        }
        return response()->json('User, restaurants, and plan created' , 201);

    }

    public function resetDB(){
//        drop all the table
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE users');
        DB::statement('DROP TABLE plans');
        DB::statement('DROP TABLE restaurants');
        DB::statement('DROP TABLE reviews');
        DB::statement('DROP TABLE roles');
        DB::statement('DROP TABLE userrestaurant');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

//        import back the sql file
        $sql = Storage::get('dineease.sql');
        DB::unprepared($sql);
        return response()->json('Done', 201);

    }
}
