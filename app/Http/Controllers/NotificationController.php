<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * recupere toutes les notification des clients.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getNotificationsCustomers(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|numeric'
        ]);
        $customer_id = $request->customer_id;

        $notifs =  Notification::select('description', 'location_name', 'created_at', 'status')->where('receiver_id', $customer_id)->get();
        $numNotifs = count(Notification::where('receiver_id',  $customer_id)->where('status', 0)->get());

        return response()->json([
            'type' => 'success',
            'data' => $notifs,
            'DoesNotReads' => $numNotifs
        ], 200);
    }

    /**
     * recupere toutes les notification de l'admin.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getNotificationsAdmin(Request $request)
    {
        $request->validate([
            'admin_id' => 'required|numeric'
        ]);
        $admin_id = $request->admin_id;

        $notifs =  Notification::select('description', 'location_name', 'created_at', 'status')->where('receiver_id', $admin_id)->get();
        return response()->json([
            'type' => 'success',
            'data' => $notifs
        ], 200);
    }
    /**
     * lire une notification.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */

    public function readNotification(Request $request)
    {

        $request->validate([
            'id' => 'required|numeric'
        ]);
        if (count(Notification::where('id', $request->id)->get()) != 0) {
            $read = Notification::find($request->id);
            $read->status = 1;
            $read->save();
            return response()->json(
                ['type' => 'read'],
                200
            );
        } else {
            return response()->json(
                ['type' => 'error', 'message' => 'this notification is not exist'],
                200
            );
        }
    }
}
