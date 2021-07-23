<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Storage;

class CustomersController extends Controller
{
    protected $fillable = [
        'name',
        'gender',
        'phone',
        'password',
        'avatar'
    ];


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(CustomerRequest $request)
    {

        $response = Customers::postCustomer($request);

        return $response;
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAll()
    {
        $user = Customers::select(['name', 'gender', 'phone', 'avatar'])->get()->toArray();

        return new CustomerResource($user);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function showById(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $id = $request->id;
        $result = count($postCustomer = Customers::where('id', $id)->get());
        if ($result == 0) {
            return response()->json([
                'type' => 'error',
                'message' => 'is not exist a customer who has this id '
            ], 200, [], JSON_NUMERIC_CHECK);
        } else {

            $postCustomer = Customers::findOrFail($request->id);

            if ($postCustomer) {
                return response()->json([

                    'type' => 'success',
                    'message' => 'a customer has been find',
                    'data' =>  $postCustomer
                ], 200, [], JSON_NUMERIC_CHECK);
            } else {
                return response()->json([

                    'type' => 'error',
                    'message' => 'this has not  find'
                ], 200, [], JSON_NUMERIC_CHECK);
            }
        }
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

            'id' => 'required'
        ]);
        $id = $request->id;
        $result = count(Customers::where('id', $id)->get());
        if ($result == 0) {
            return response()->json(
                [
                    'type' => 'success',
                    'message' => 'is not exist a customer who has this id'
                ],
                200,
                [],
                JSON_NUMERIC_CHECK
            );
        } else {

            $updateCustomer = Customers::findOrFail($id);
            $request->input('name');
            $request->input('email');
            $request->input('phone');
            $request->input('password');
            $updateCustomer->update(
                [
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone'),
                    'password' => $request->input('password')



                ]
            );

            if ($updateCustomer) {
                return response()->json(
                    [
                        'type' => 'success',
                        'message' => 'the update is successfully'
                    ],
                    200,
                    [],
                    JSON_NUMERIC_CHECK
                );
            } else {
                return response()->json(
                    [
                        'type' => 'error',
                        'message' => 'the update has fail'
                    ],
                    200,
                    [],
                    JSON_NUMERIC_CHECK
                );
            }
        }
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Request $request
     * @return \Illuminate\Http\Response
     */
    public function deleteById(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $id = $request->id;
        $del_cus = Customers::find($id)->delete();

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
    public function destroy()
    {
        Customers::all()->delete();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Request $request
     * @return \Illuminate\Http\Response
     */

    public function update_avatar(Request $request)
    {

        $request->validate([
            'id' => 'required',
            'avatar' => 'required|file|image'
        ]);
        $id = $request->id;
        $file = $request->avatar;

        if (File::size($file) < 1000000) {

            $extensionArray = ["image/jpg", "image/jpeg", "image/png"];

            if (in_array(File::mimeType($file), $extensionArray)) {

                $path = Storage::putFile('customer_profil', $file);

                if ($path) {

                    $result = DB::update('update customers set avatar = ? where id = ?', [$path, $id]);

                    if ($result == 1) {
                        return response()->json([
                            'type' => "success",
                            'message' => "votre avatar a été bien mis à jours",
                            'profil_url' => $path
                        ], 200, [], JSON_NUMERIC_CHECK);
                    } else {
                        return response()->json([
                            'type' => "error",
                            'message' => "une erreur est survenue veuiller reessayer plus tard"
                        ], 200, [], JSON_NUMERIC_CHECK);
                    }
                } else {
                    return response()->json([
                        'type' => "error",
                        'message' => "une erreur est survenue lors de l'envoie de votre image"
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
}
