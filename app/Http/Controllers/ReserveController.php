<?php

namespace App\Http\Controllers;

use App\Models\Locations;
use App\Models\Notification;
use App\Models\Reserve;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Location;

class ReserveController extends Controller
{
    /**
     * initier une reservation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reserve(Request $request)
    {
        $request->validate(
            [
                'customer_id' => 'required|numeric',
                'location_id' => 'required|numeric',
                'start_day' => 'required|date',
                'finish_day' => 'required|date',
                'admin_id'  =>  'required|numeric'

            ]
        );
        // dd($request->start_day);
        $admin_id = $request->admin_id;
        $customer_id = $request->customer_id;
        $location_id = $request->location_id;

        if (count(User::where('id', $customer_id)->get()) != 0 && count(Locations::where('id', $location_id)->get()) != 0) {
            if (count(Locations::where('status', 0)->where('id', $location_id)->get()) != 0) {
                if (count(Reserve::where('customer_id', $customer_id)->where('location_id', $location_id)->where('status', 0)->get()) != 0) {
                    return response()->json([
                        'type' => 'error',
                        'message' => 'you have already a reservation in loading'
                    ], 403, [], JSON_NUMERIC_CHECK);
                } else {

                    DB::insert(
                        'insert into reserves(customer_id,location_id,start_day,finish_day) values(?,?,?,?)',
                        [$customer_id, $location_id, $request->start_day, $request->finish_day]
                    );
                    $location_name = Locations::find($location_id);
                    $location_name = $location_name->name;
                    $customer_name = User::select('name')->where('id', $customer_id);

                    Notification::create(
                        [
                            'description' => "a customer  made a reservation request",
                            'customer_name' => $customer_name,
                            'location_name' => $location_name,
                            'sender_id' => $customer_id,
                            'receiver_id' => $request->admin_id
                        ]
                    );
                    Notification::create(
                        [
                            'description' => "you have just made a reservation request for the period  we will get back to you in a maximum of 24 hours",
                            'period' => "$request->start_day to $request->finish_day  ",

                            'location_name' => $location_name,
                            'sender_id' => $admin_id,
                            'receiver_id' => $customer_id
                        ]
                    );








                    $customer = User::find($customer_id);
                    $customer = $customer->name;
                    $locationReserve =  DB::select('select * from locations where id = ? LIMIT 1', [$location_id]);

                    return response()->json([
                        'type' => 'success',
                        'message' => "$customer made  reservation request on the $request->start_day to the $request->finish_day ",
                        'location' => $locationReserve
                    ], 200, [], JSON_NUMERIC_CHECK);
                }
            } else {
                return response()->json([
                    'type' => 'error',
                    'message' => 'this location is not available',


                ], 200, [], JSON_NUMERIC_CHECK);
            }
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'customer id or location id are not exists'
            ], 403);
        }

        // $customer_id = $request->customer_id;
        // $location_id = $request->location_id;
        // if (count(Reserve::where('customer_id', $customer_id)->where('status', 0)->get()) > 0) {
        //     return response()->json([
        //         'type' => 'error',
        //         'message' => 'you have already a reservation in loading'
        //     ], 403, [], JSON_NUMERIC_CHECK);
        // } else if (count(Reserve::where('status', 1)->where('location_id', $location_id)->get()) > 0) {

        //     return response()->json([
        //         'type' => 'error',
        //         'message' => 'this  location has been  reserved'
        //     ], 403, [], JSON_NUMERIC_CHECK);
        // } else {

        //     $reserve = Reserve::create(
        //         [
        //             'customer_id' => $customer_id,
        //             'location_id' =>   $location_id,
        //         ]
        //     );
        //     $customer = User::select('name')->where('id', $customer_id)->get();
        //     $locationReserve = Locations::select('name')->where('id', $location_id)->get();
        //     return response()->json([
        //         'type' => 'success',
        //         'message' => 'initiation is sucessfull wait a confirmation',
        //         'user' => $customer,
        //         'location reserved' => $locationReserve

        //     ], 200, [], JSON_NUMERIC_CHECK);
        // }
    }


    /**
     * Confirmer  reservation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function confirm_reserve_admin(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|numeric',
            'location_id' => 'required|numeric',
            'admin_id' => 'required|numeric'
        ]);
        $customer_id = $request->customer_id;
        $location_id = $request->location_id;
        $admin_id = $request->admin_id;
        if (count(User::where('id', $customer_id)->get()) != 0 && count(Locations::where('id', $location_id)->get()) != 0) {
            if (count(Locations::where('status', 1)->where('id', $location_id)->get()) == 0) {
                if (count(Reserve::where('status', 1)->where('location_id', $location_id)->where('customer_id', $customer_id)->get()) != 0) {

                    return response()->json([
                        'type' => 'error',
                        'message' => 'this confirmation is not possible because this location has been reserved'
                    ], 403);
                } else  if (count(Reserve::where('status', 1)->where('location_id', $location_id)->where('customer_id', $customer_id)->get()) == 0) {


                    DB::update('update reserves set status= ? where location_id =? and  customer_id = ? ', ['1', $location_id, $customer_id]);
                    $location_name = Locations::find($location_id);
                    $name = $location_name->name;
                    Notification::create(
                        [
                            'description' => 'your reservation is accepted please confirmed yourself',
                            'location_name' => $name,
                            'sender_id' => $admin_id,
                            'receiver_id' => $customer_id
                        ]
                    );


                    return response()->json([
                        'type' => 'success',
                        'message' => 'your reservation request has been accepted'
                    ], 200);
                }
            } else {
                return response()->json([
                    'type' == 'error',
                    'this location is available'
                ], 403);
            }
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'customer id or location id are not exists'
            ], 403);
        }





        //     if (count($customer = Reserve::where('location_id', $request->location_id)->where('status', 1)->get()) > 0) {
        //         return response()->json([
        //             'type' => 'error',
        //             'message' => 'this confirmation is not possible because this location is already confirmed for a another customer'
        //         ], 200, [], JSON_NUMERIC_CHECK);
        //     } else

        //     if (count($customer = Reserve::where('location_id', $request->location_id)->where('customer_id', $request->customer_id)->where('status', 0)->get()) > 0) {

        //         $customer = DB::update('update reserves set status = ? where customer_id = ?', ['1', $request->customer_id]);

        //         return response()->json([
        //             'type' => 'success',
        //             'message' => ' reservation confirmed',
        //             'data' =>  $customer
        //         ], 200, [], JSON_NUMERIC_CHECK);
        //     } else 
        //     if (count(Reserve::where('customer_id', $request->customer_id)->where('status', 1)->get()) > 0) {
        //         return response()->json([
        //             'type' => 'error',
        //             'message' => 'this confirmation is not possible because this location is already confirmed for a another customer'
        //         ], 200, [], JSON_NUMERIC_CHECK);
        //     }
    }
    public function confirm_reserve_user(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|numeric',
            'location_id' => 'required|numeric',
            'admin_id' => 'required|numeric'
        ]);
        $admin_id = $request->admin_id;
        $customer_id = $request->customer_id;
        $location_id = $request->location_id;
        if (count(User::where('id', $customer_id)->get()) != 0 && count(Locations::where('id', $location_id)->get()) != 0) {
            if (count(Locations::where('status', 1)->where('id', $location_id)->get()) == 0) {
                if (count(Reserve::where('status', 1)->where('id', $location_id)->where('customer_id', $customer_id)->get()) == 0) {

                    return response()->json([
                        'type' => 'error',
                        'message' => 'this confirmation is not possible because this location has been reserved'
                    ], 403);
                } else if (count(Reserve::where('location_id', $location_id)->where('customer_id', $customer_id)->limit('1')->get()) == 0) {
                    return response()->json([
                        'type' => 'error',
                        'message' => 'this confirmation is not possible because this location has not ask'
                    ], 403);
                } else {

                    DB::update('update location set status= ? where location_id =?', ['1', $location_id]);
                    $location_name = Locations::find($location_id);
                    $name = $location_name->name;
                    Notification::create(
                        [
                            'description' => 'your reservation is confirmed definitly',
                            'location_name' => $name,
                            'sender_id' =>  $customer_id,
                            'receiver_id' =>  $admin_id
                        ]
                    );


                    return response()->json([
                        'type' => 'success',
                        'message' => 'your reservation request has been accepted'
                    ], 200);
                }
            } else {
                return response()->json([
                    'type' == 'error',
                    'this location is available'
                ], 403);
            }
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'customer id or location id are not exists'
            ], 403);
        }
    }

    /**
     * refuser une demande de reservation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function refuseReserve(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric',
            'location_id' => 'required|numeric',
            'customer_id' => 'required|numeric',
            'admin_id' => 'required|numeric'
        ]);
        $admin_id = $request->admin_id;
        $customer_id = $request->customer_id;
        $location_id = $request->location_id;

        if (count(Reserve::where('id', $request->id)->where('status', 1)->get()) == 0) {
            $res = Reserve::find($request->id);
            $data =  Reserve::find($request->id);
            $res->status = 3;
            $res->save();
            $location_name = Locations::find($location_id);
            $location_name = $location_name->name;

            Notification::create(
                [
                    'description' => 'reservations failed please choose another location ',
                    'location_name' => $location_name,
                    'sender_id' => $admin_id,
                    'receiver_id' => $customer_id
                ]
            );
            return response()->json([
                'type' => 'success',
                'message' => 'deletion confirmed with success',
                'data' =>  $data
            ], 200);
        } else {

            return response()->json([
                'type' => 'error',
                'message' => 'you cannot delete a reservation that has been confirmed',
            ], 200);
        }
    }


    /**
     * modifier la disponibilité d'un emplacement lorsque la durée 
     * de reservation arrive à son terme.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateStatusForEndReserve(Request $request)
    {
        $request->validate([
            'location_id' => 'required|numeric',
            'admin_id' => 'required|numeric',
            'customer_id' => 'required|numeric'
        ]);
        $admin_id = $request->admin_id;
        $customer_id = $request->customer_id;

        $location = Locations::where('id', $request->location_id)->where('status', 1)->get();
        if (count($location) == 0) {
            return response()->json([
                'type' => 'error',
                'message' => 'this location is already available'
            ], 403);
        } else {
            $location = Locations::find($request->location_id);
            $location->status = 0;
            $location->save();
            DB::update('update reserves set status = ? where location_id =?', ['2', $request->location_id]);
            $location_name = Locations::find($request->location_id);
            $location_name = $location_name->name;

            Notification::create(
                [
                    'description' => 'your reservation is finish this location is vailable for another customer',
                    'location_name' => $location_name,
                    'sender_id' => $admin_id,
                    'receiver_id' => $customer_id
                ]
            );
            return response()->json([
                "type" => 'success',
                'message' =>  "location $location->name has been delivered and is available again "
            ], 200);
        }
    }


    /**recuperer toutes les reservations.
     *  @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        if (count(Reserve::all()) == 0) {
            return response()->json([
                'type' => 'error',
                'message' => ' the list is empty'

            ], 200, [], JSON_NUMERIC_CHECK);
        } else {

            $reserve = DB::select('select  locations.name, locations.image,reserves.id,reserves.start_day, reserves.finish_day,reserves.status,reserves.location_id,reserves.customer_id from locations inner join reserves on reserves.location_id=locations.id ');

            return response()->json([
                'type' => 'success',
                'message' => 'list of reservations are:',
                'data', $reserve
            ], 200, [], JSON_NUMERIC_CHECK);
        }
    }


    /**
     * recuperer les reservations par categorie.
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
     * supprimer une reservation.
     *
     * @param  \App\Models\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $request->validate([
            'customer_id' => 'required',
            'location_id' => 'required'
        ]);

        $customer_id = $request->customer_id;
        $location_id = $request->location_id;

        if (count(Reserve::where('customer_id', $customer_id)->where('location_id', $location_id)->get()) == 0) {
            return response()->json([
                'type' => 'error',
                'message' => 'a problem has been finded  when one wanted delete'
            ], 200, [], JSON_NUMERIC_CHECK);
        } else {
            $del_cus = Reserve::where('customer_id', $customer_id)->where('location_id', $location_id);
            $res = $del_cus->get();
            $del_cus->delete();

            return response()->json([
                'type' => 'success',
                'message' => 'the delete is successfull',
                'data' => $res
            ], 200, [], JSON_NUMERIC_CHECK);
        }
    }

    /**
     * recuperer les reservations d'un emplacement.
     *
     * @param  \App\Models\Request $request
     * @return \Illuminate\Http\Response
     */

    public function getReservationsByLocation(Request $request)
    {

        $request->validate([
            'location_id' => 'required|numeric'

        ]);

        $location_id = $request->location_id;

        if (count(Locations::where('location_id', $location_id)->get()) != 0) {

            $number = count(Reserve::where('location_id', $location_id)->where('status', 1)->orwhere('status', 2)->get());

            return response()->json([
                'type' => 'success',
                'data' =>  $number
            ], 200);
        } else {

            return response()->json([
                'type' => 'error',
                'message' => 'this location is not exist in our lists'
            ], 404);
        }
    }
}
