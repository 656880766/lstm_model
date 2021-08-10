<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Locations;
use Illuminate\Http\Request;
use App\Models\Liked_Locations;

class LikedLocationsController extends Controller
{
    /**
     * Liker une categorie.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function liker(Request $request)
    {

        $request->validate([
            'customer_id' => 'required|numeric',
            'location_id' => 'required|numeric'
        ]);

        if (count(Liked_Locations::where('customer_id', $request->customer_id)
            ->where('location_id', $request->location_id)->get()) == 0) {
            $liker = Liked_Locations::create([
                'customer_id' => $request->customer_id,
                'location_id' => $request->location_id

            ]);
            $countLikes = (int)count(Liked_Locations::where('location_id', $request->location_id)->get());
            $location = Locations::find($request->location_id);
            $location->likes = $countLikes;
            $location->save();

            $userLike = User::select('name')->where('id', $request->customer_id)->get();
            $locationLike = Locations::select('name')->where('id', $request->location_id)->get();


            return response()->json([
                'type' => 'success',
                'name' => $userLike,
                'location liked' =>  $locationLike
            ], 200);
        } else {

            Liked_Locations::where('customer_id', $request->customer_id)
                ->where('location_id', $request->location_id)->delete();
            $userLike = User::select('name')->where('id', $request->customer_id)->get();
            $locationLike = Locations::select('name')->where('id', $request->location_id)->get();
            $countLikes = (int)count(Liked_Locations::where('location_id', $request->location_id)->get());
            $location = Locations::find($request->location_id);
            $location->likes = $countLikes;
            $location->save();
            return response()->json([
                'type' => 'dislike',
                'name' => $userLike,
                'location disliked' =>  $locationLike
            ], 200);
        }
    }
}
