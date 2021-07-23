<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Locations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class LocationsController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'required|file|image'
        ]);

        $id = $request->id;
        $file = $request->image;

        if (File::size($file) < 1000000) {

            $extensionArray = ["image/jpg", "image/jpeg", "image/png"];

            if (in_array(File::mimeType($file), $extensionArray)) {

                $path = Storage::putFile('location_image', $file);

                if (count(Locations::where('id', $id)->get()) === 0) {
                    $user = Locations::create([
                        "place" => $request->input('place'),
                        'name' => $request->input('name'),
                        "description" => $request->input('description'),
                        'note_average' => $request->input('note_average'),
                        'stars' => $request->input('stars'),
                        'image' => $path,
                        'state' => $request->input('state'),
                        'owner_name' => $request->input('owner_name'),
                        'owner_phone' => $request->input('owner_phone'),
                        'category_id' => $request->input('category_id')


                    ]);

                    return Response()->json([
                        'type' => 'success',
                        'message' => 'a new location has been add',
                        'data' =>    $user
                    ], 200, [], JSON_NUMERIC_CHECK);
                } else {
                    return Response()->json([
                        "message" => "The given data was invalid.",
                        "errors" => [
                            "name" =>  [
                                "This location is already exist"
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
     *
     * @return \Illuminate\Http\Response
     */
    public function showAll()
    {


        $post_location = Locations::select(
            [
                'place',
                'name',
                'note_average',
                'stars',
                'image',
                'state',
                'owner_name',
                'owner_phone',
                'category_id'

            ]
        )->get();

        // dd($post_location);
        return response()->json([
            "type" => "Success",
            "message" => "list of locations",
            "data"  => $post_location
        ], 200, [], JSON_NUMERIC_CHECK);
        // $category  = new Categories();
        // $post_location->category()->associate(category);
        // $post_locations = Locations::with($category )->get();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function showBycategoriesId(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric'
        ]);

        $post_location = Locations::find($request->id);


        return response()->json([
            "type" => "Success",
            "message" => "This is list of one with your category",
            "data"  => $post_location
        ], 200, [], JSON_NUMERIC_CHECK);
        // $category  = new Categories();
        // $post_location->category()->associate(category);
        // $post_locations = Locations::with($category )->get();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function updateById(Request $request)
    {

        $request->validate([
            'id' => 'required',
            'image' => 'required|file|image'
        ]);

        $id = $request->id;
        $file = $request->image;
        if (File::size($file) < 1000000) {

            $extensionArray = ["image/jpg", "image/jpeg", "image/png"];

            if (in_array(File::mimeType($file), $extensionArray)) {

                $path = Storage::putFile('location_image', $file);

                if (count(Locations::where('id', $id)->get()) != 1) {

                    $updateLocation = Locations::findOrFail($id);
                    $request->input('place');
                    $request->input('name');
                    $request->input('description');
                    $request->input('stars');
                    $request->input('image');
                    $request->input('owner_name');
                    $request->input('owner_phone');
                    $request->input('category_id');
                    $updateLocation->update([
                        'place' => $request->input('place'),
                        'name' => $request->input('name'),
                        'description' => $request->input('description'),
                        'stars' => $request->input('stars'),
                        'image' => $request->input('image'),
                        'owner_name' => $request->input('owner_name'),
                        'owner_phone' => $request->input('owner_phone'),
                        'category_id' => $request->input('category_id')



                    ]);
                    return Response()->json([
                        'type' => 'success',
                        'message' => 'a  location has been update',
                        'data' =>  $updateLocation
                    ], 200, [], JSON_NUMERIC_CHECK);
                } else {
                    return Response()->json([
                        "message" => "The given data was invalid.",
                        "errors" => [
                            "name" =>  [
                                "This location does not  exist"
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
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        dd('je suis update byid');
    }
}
