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
                'blanket' => $request->input('blanket'),
                "description" => $request->input('description')

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
        $catAll = Categories::all();

        return $catAll->toArray();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function showById(Request $request)
    {
        $catById = Categories::findOrFail($request->id);
        return response()->json($catById, 200, [], JSON_NUMERIC_CHECK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $uptCat = Categories::find($request->id);
        dd($request->id);
        $request->input('name');
        $request->input('blanket');
        $request->input('description');
        $uptCat->update(
            [
                'name' => $request->input('name'),
                'blanket' => $request->input('blanket'),
                'description' =>  $request->input('description'),


            ]
        );

        return response()->json("mise à jour effectuée", 200, [], JSON_NUMERIC_CHECK);
    }



    public function delete($id)
    {

        $del_cat = Categories::find($id);
        $del_cat->delete();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $del_cat = Categories::all();
        $del_cat->delete();
    }
}
