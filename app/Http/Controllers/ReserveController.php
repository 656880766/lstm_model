<?php

namespace App\Http\Controllers;

use App\Models\Reserve;
use Illuminate\Http\Request;

class ReserveController extends Controller
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
                'customer_id' => 'required',
                'location_id' => 'required'
            ]
        );
        $customer_id = $request->customer_id;
        $location_id = $request->location_id;
        if (count(Reserve::where('customer_id', $customer_id)->where('status', 0)->get()) > 0) {
            return response()->json([
                'type' => 'error',
                'message' => 'you have already a reservation in loading'
            ], 200, [], JSON_NUMERIC_CHECK);
        } else {

            $reserve = Reserve::create(
                [
                    'customer_id' => $customer_id,
                    'location_id' =>   $location_id,
                ]
            );
            return response()->json([
                'type' => 'success',
                'message' => 'initiation is sucessfull wait a confirmation'

            ], 200, [], JSON_NUMERIC_CHECK);
        }
    }

    public function confirm_reserve(Request $request)
    {
        $request->validate([
            'customer_id' => 'required'
        ]);

        if (count(Reserve::where('customer_id', $request->customer_id)->get()) == 0) {
            return response()->json([
                'type' => 'error',
                'message' => 'this rervation can not possible try later'
            ], 200, [], JSON_NUMERIC_CHECK);
        } else {
            $customer = Reserve::findOrFail($request->customer_id);

            $customer->status = 1;
            $customer->save();
            return response()->json([
                'type' => 'success',
                'message' => ' reservation confirmed',
                'data' =>  $customer
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
        if (count(Reserve::all()) == 0) {
            return response()->json([
                'type' => 'error',
                'message' => ' the list is empty'

            ], 200, [], JSON_NUMERIC_CHECK);
        } else {
            return response()->json([
                'type' => 'success',
                'message' => 'list of reservations are:',
                'data', Reserve::all()
            ], 200, [], JSON_NUMERIC_CHECK);
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function showByCustomerId(Request $request)
    {
        $request->validate([
            'customer_id' => 'required'
        ]);

        if (count(Reserve::where('id', $request->id)->get()) == 0) {

            return response()->json([
                'type' => 'error',
                'message' => ' this revervation is not exist'

            ], 200, [], JSON_NUMERIC_CHECK);
        } else {
            return response()->json([
                'type' => 'success',
                'data', Reserve::find($request->id)
            ], 200, [], JSON_NUMERIC_CHECK);
        }
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $request->validate([
            'customer_id' => 'required'
        ]);

        $id = $request->customer_id;

        $del_cus = Reserve::find($id);

        if ($del_cus == 1) {
            $del_cus->delete();

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
