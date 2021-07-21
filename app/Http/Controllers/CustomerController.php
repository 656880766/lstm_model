<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
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

        $response = Customer::postCustomer($request);

        return $response;
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAll()
    {
        $user = Customer::select(['name', 'gender', 'phone', 'avatar'])->get()->toArray();

        return new CustomerResource($user);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function showById($id)
    {
        $postCustomer = Customer::findOrFail($id);
        return $postCustomer->toArray();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update($id, CustomerRequest $request)
    {
        $postCustomer = Customer::findOrFail($id);
        $request->input('name');
        $request->input('phone');
        $request->input('password');
        $request->input('avatar');
        $postCustomer->update(
            [
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'password' =>  $request->input('password'),
                'avatar' => $request->input('avatar')

            ]
        );

        return response()->json("mise à jour effectuée", 200, [], JSON_NUMERIC_CHECK);
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $del_cus = Customer::find($id)->delete();
        return $del_cus;
    }
    public function destroy()
    {
        Customer::all()->delete();
    }
}
