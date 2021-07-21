<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (count(Categories::where('name', $request->input('name'))->get()) === 0) {
            $post_cat =  Categories::create([
                "name" => $request->input('name'),
                'blanket' => $request->input('gender'),
                "description" => $request->input('name')

            ]);
            return Response()->json($post_cat, 200, [], JSON_NUMERIC_CHECK);
        } else {

            return Response()->json([
                "message" => "The given data was invalid.",
                "errors" => [
                    "name" =>  [
                        "this category name is already exist in our database please take other name."
                    ]
                ]
            ], 200, [], JSON_NUMERIC_CHECK);
        }
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAll()
    {
        $cat = Categories::all();

        return $cat;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function showById()
    {
        dd('je suis showById');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update()
    {

        dd('je suis update by Id');
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        dd('je suis update byid');
    }
}
