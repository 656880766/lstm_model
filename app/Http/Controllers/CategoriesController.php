<?php

namespace App\Http\Controllers;

use App\Models\Locations;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CategoriesController extends Controller
{

    /**
     * creer une categorie.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate(
            [
                'blanket' => 'required|file|image'
            ]
        );

        $file = $request->blanket;

        if (FILE::size($file) < 1000000) {
            $extensionArray = ["image/jpg", "image/jpeg", "image/png"];
            if (in_array(File::mimeType($file), $extensionArray)) {
                $path = Storage::putFile('Categories_image', $file);
                if (count(Categories::where('name', $request->input('name'))->get()) === 0) {
                    $post_cat =  Categories::create([
                        "name" => $request->input('name'),
                        'blanket' => $path,
                        "description" => $request->input('description')

                    ]);
                    return Response()->json([

                        'type' => 'success',
                        'message' => 'adding category is sucessfull',
                        'data' => $post_cat
                    ], 200, [], JSON_NUMERIC_CHECK);
                } else {

                    return Response()->json([
                        "type" => "error",
                        "message" => "The given data was invalid.",
                        "errors" => [
                            "name" =>  [
                                "this category name is already exist in our database please take other name."
                            ]
                        ]
                    ], 200, [], JSON_NUMERIC_CHECK);
                }
            } else {
                return response()->json([
                    'type' => "error",
                    'message' => "l'extension de l'image n'es pas autorisé"
                ], 200, [], JSON_NUMERIC_CHECK);
            }
        } else {
            return response()->json([
                'type' => "error",
                'message' => "votre image est trop grande"
            ], 200, [], JSON_NUMERIC_CHECK);
        }
    }



    /** recuperer toutes les catégories 
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        $catAll = Categories::all();

        return response()->json([
            'type' => 'sucess',
            'message' => 'list of categories are:',
            'data'  => $catAll
        ], 200, [], JSON_NUMERIC_CHECK);
    }
    /**
     * recuperer une categories et le emplacements associés .
     * @return \Illuminate\Http\Response
     * @param Request $request
     */
    public function getCategoryWithLocations(Request $request)
    {
        $request->validate([
            'id' => 'required|max:100'
        ]);
        $locations = Locations::select('name', 'description', 'note_average', 'stars', 'image', 'owner_phone')->where('category_id', $request->id)->get()->toArray();

        $category = Categories::select('name')->where('id', $request->id)->get();

        if (count($category) === 0) {
            return response()->json([
                'type' => 'error',
                'message' => 'this category is not exist'

            ], 404);
        } else {
            return response()->json([
                'type' => 'success',
                'category_name' => $category,
                'locations of this category' => $locations
            ], 200);
        }
    }



    /**
     * modifier une categorie.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate(
            [
                'blanket' => 'file|image',
                'id' => 'required'

            ]
        );


        $id = $request->id;

        if ($request->blanket) {

            $file = $request->blanket;

            if (FILE::size($file) < 1000000) {
                $extensionArray = ["image/jpg", "image/jpeg", "image/png"];
                if (in_array(File::mimeType($file), $extensionArray)) {
                    $path = Storage::putFile('Categories_image', $file);
                    if (count(Categories::where('id', $request->id)->get()) != 0) {
                        $post_cat = Categories::find($id);
                        $post_cat->update([
                            "name" => $request->input('name'),
                            'blanket' => $path,
                            "description" => $request->input('description')

                        ]);
                        return Response()->json([

                            'type' => 'success',
                            'message' => 'update is sucessfull',
                            'data' => $post_cat
                        ], 200, [], JSON_NUMERIC_CHECK);
                    } else {

                        return Response()->json([
                            "message" => "The given data was invalid.",
                            "errors" => [
                                "name" =>  [
                                    "this category id is not exist."
                                ]
                            ]
                        ], 200, [], JSON_NUMERIC_CHECK);
                    }
                } else {
                    return response()->json([
                        'type' => "error",
                        'message' => "l'extension de l'image n'es pas autorisé"
                    ], 200, [], JSON_NUMERIC_CHECK);
                }
            } else {
                return response()->json([
                    'type' => "error",
                    'message' => "votre image est trop grande"
                ], 200, [], JSON_NUMERIC_CHECK);
            }
        }
        if (count(Categories::where('id', $request->id)->get()) != 0) {
            $post_cat = Categories::find($id);
            $post_cat->update([
                "name" => $request->input('name'),
                "description" => $request->input('description')

            ]);
            return Response()->json([

                'type' => 'success',
                'message' => 'update is sucessfull',
                'data' => $post_cat
            ], 200, [], JSON_NUMERIC_CHECK);
        } else {

            return Response()->json([
                "message" => "The given data was invalid.",
                "errors" => [
                    "name" =>  [
                        "this category id is not exist."
                    ]
                ]
            ], 200, [], JSON_NUMERIC_CHECK);
        }
    }



    /**
     * supprimer une categorie.
     *
     * @param  \App\Models\(Request $request
     * @return \Illuminate\Http\Response
     */

    public function delete(Request $request)
    {

        $request->validate([
            'id' => 'required'
        ]);
        $id = $request->id;
        $del_cus = Categories::find($id)->delete();

        if ($del_cus) {

            return response()->json([
                'type' => 'success',
                'message' => 'the delete is successfull'
            ], 200, [], JSON_NUMERIC_CHECK);
        } else {

            return response()->json([
                'type' => 'error',
                'message' => 'a problem has been finded  when one wanted delete'
            ], 200, [], JSON_NUMERIC_CHECK);
        }
    }
}
