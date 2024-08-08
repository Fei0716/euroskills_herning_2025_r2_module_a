<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index(){
        $plans = Plan::get();
        return response()->json( $plans , 200);
    }

    public function update($id , Request $request){
        $validated  = $request->validate([
            'name' => 'required',
            'monthlyFee'=> 'required|min:0',
            'yearlyFee'=> 'required|min:0',
            'maxNumberOfRestaurants' => 'required|min:0',
            'description' => 'required',
        ]);
        $plan = Plan::find($id);

        if($plan){
            $plan->name = $validated['name'];
            $plan->monthlyFee = $validated['monthlyFee'];
            $plan->yearlyFee = $validated['yearlyFee'];
            $plan->maxNumberOfRestaurants = $validated['maxNumberOfRestaurants'];
            $plan->description = $validated['description'];
            $plan->save();
            return response( 'Plan updated', 200);
        }
        return response()->json(['message' => 'Plan not found'], 404);
    }
}
