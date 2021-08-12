<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Liked_Locations;
use App\Models\Locations;
use App\Models\Reserve;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Location;

class LocationsController extends Controller
{

    /**
     * enregistrer une categorie.
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
     * recuperer les emplacements par categorie.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLocationsWithCategory()
    {


        // $post_location = Locations::all();
        // // dd($post_location);
        // $category  = Categories::all();
        // $post_location->category()->associate($category);
        $post_locations = Locations::with('category')->get();


        // dd($post_location);
        return response()->json([
            "type" => "Success",
            "message" => "list of locations",
            "data"  => $post_locations

        ], 200, [], JSON_NUMERIC_CHECK);
    }


    /**
     * recuperer une location et sa categorie associée.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */

    public function getLocationBycategoriesId(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric'
        ]);

        $post_location = Locations::find($request->id);
        $post_location->load('category')->toArray();
        // $post_location->select('category->name');


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
     * modifier les informations sur un emplacement.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
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

                if (count(Locations::where('id', $id)->get()) != 0) {

                    $updateLocation = Locations::findOrFail($id);

                    $updateLocation->place = $request->input('place');
                    $updateLocation->name = $request->input('name');
                    $updateLocation->description = $request->input('description');
                    $updateLocation->stars = $request->input('stars');
                    $updateLocation->image = $path;
                    $updateLocation->owner_name = $request->input('owner_name');
                    $updateLocation->owner_phone = $request->input('owner_phone');
                    $updateLocation->category_id = $request->input('category_id');
                    $updateLocation->save();

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
     * modifier les informations sur un emplacement.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    /**
     *supprimer un emplacement.
     *
     * @param  \App\Models\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $id = $request->id;
        $del_cus = Locations::find($id)->delete();

        if ($del_cus) {

            return response()->json([
                'type' => 'success',
                'message' => 'the delete is successfull'
            ], 200, [], JSON_NUMERIC_CHECK);
        } else {

            return response()->json([
                'type' => 'error',
                'message' => 'a problem was find when one wanted delete'
            ], 200, [], JSON_NUMERIC_CHECK);
        }
    }
    /**
     * enregistrer le nombre de likes.
     *
     * @param  \App\Models\Request $request
     * @return \Illuminate\Http\Response
     */
    public static function storeLike(Request $request)
    {

        $request->validate([
            'id' => 'required|numeric'
        ]);
        $countLikes = (int)count(Liked_Locations::where('location_id', $request->id)->get());
        $location = Locations::find($request->id);
        $location->likes = $countLikes;
        $location->save();
    }
    /**
     * recuperer les emplacements favoris.
     *
     * @param  \App\Models\Request $request
     * @return \Illuminate\Http\Response
     */

    public function getFavoriteLocation()
    {


        $location = DB::select('select name,description, likes from locations where likes > ? order by likes desc', ['0']);

        return response()->json([
            'type' => 'success',
            'message' => 'this list of favorite locations are:',
            'data' =>  $location

        ], 200);
    }
    /**
     * recuperer les emplacements ignorer.
     * @return \Illuminate\Http\Response
     */

    public function getNotFavoriteLocation()
    {


        $location = DB::select('select name,description, likes from locations where likes = ? order by likes desc', ['0']);

        return response()->json([
            'type' => 'success',
            'message' => 'this list of  not favorite locations are:',
            'data' =>  $location

        ], 200);
    }
    /**
     * 
     * recuperer les utilsateurs ayant reserver un emplacement precis.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */

    public function getCustomerReserveByLocation(Request $request)
    {
        $request->validate(
            [
                'location_id' => 'required|numeric'
            ]
        );

        if (count(Locations::where('location_id', $request->location_id)->get()) != 0) {

            $result = DB::select('select count(customer_id) from reserves where location_id = ? and status = ? or ?', [$request->location_id, 1, 2]);
            return response()->json(
                [
                    'type' => 'success',
                    'data' => $result
                ],
                200

            );
        } else {
            return response()->json(
                [
                    'type' => 'error',
                    'message' => 'this location does not exist'
                ],
                404
            );
        }
    }
}
