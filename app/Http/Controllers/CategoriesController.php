<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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
                        'message' => 'add is sucessfull',
                        'data' => $post_cat
                    ], 200, [], JSON_NUMERIC_CHECK);
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



    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function showAll()
    {
        $catAll = Categories::all();

        return response()->json([
            'type' => 'sucess',
            'message' => 'list of categories are:',
            'data'  => $catAll
        ], 200, [], JSON_NUMERIC_CHECK);
    }


    /**
     * Display a listing of the resource.
     * * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function showById(Request $request)
    {
        $request->validate(
            [
                'id' => 'required'
            ]
        );
        if (count(Categories::findOrFail($request->id)->get()) == 0) {

            return response()->json([
                'type' => 'error',
                'message' => 'the list of categories is empty!!!'
            ], 200, [], JSON_NUMERIC_CHECK);
        } else {
            $catById = Categories::findOrFail($request->id);
            return response()->json([
                'type' => 'sucess',
                'message' => 'this category is:',
                'data' => $catById
            ], 200, [], JSON_NUMERIC_CHECK);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateById(Request $request)
    {
        $request->validate(
            [
                'blanket' => 'required|file|image',
                'id' => 'required'

            ]
        );


        $id = $request->id;

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\(Request $request
     * @return \Illuminate\Http\Response
     */



    public function deleteById(Request $request)
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
