<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserDetailResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //Get brief data of all users
    public function index(){
        $user = User::select('id', 'firstName','lastName','email','isActive','roleId')->get();
        return response()->json( $user, 200);
    }
    //Get detailed information about a selected user
    public function show($id , Request $request){
        $user = User::find($id);
        if($user){
            return response()->json( new UserDetailResource($user), 200);
        }
        return response()->json(['message' => 'User not found'], 404);
    }
    // Disable or activate a user
    public function update($id, Request $request){
        $user = User::find($id);
        if($user){
            $user->isActive = !$user->isActive;
            $user->save();
            return response( 'User activation status updated', 200);
        }
        return response()->json(['message' => 'User not found'], 404);
    }
}
