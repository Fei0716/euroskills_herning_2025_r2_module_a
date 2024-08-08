<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(){
        $reviews = Review::select('id','restaurantId', 'name', 'rating', 'comment')->orderBy('createdAt' ,'desc')->get();
        return response()->json( $reviews , 200);
    }

    public function destroy($id){
        $review = Review::find($id);
        if($review){
            $review->delete();
            return response( 'Review deleted', 204);
        }
        return response()->json(['message' => 'Review not found'], 404);
    }
}
