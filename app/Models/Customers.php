<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;

class Customers extends Model
{
    use HasFactory;



    protected $fillable = [
        'name',
        'gender',
        'phone',
        'password',
        'avatar'
    ];

    public static function loginCustomer()
    {
        echo "bonjour";
    }




    public static function postCustomer(CustomerRequest $request)
    {
        $contact = $request->input('phone');
        if ($request->input('password') === $request->input('confirm_password')) {

            if (count(Customers::where('phone', $contact)->get()) === 0) {
                $user =  Customers::create([
                    "name" => $request->input('name'),
                    'gender' => $request->input('gender'),
                    "phone" => $contact,
                    'password' => $request->input('password'),

                ]);
                return Response()->json([
                    'type' => 'success',
                    'message' => 'inscription reussie suivez le lien de connexion ci dessous',
                    'data' =>    $user
                ], 200, [], JSON_NUMERIC_CHECK);
            }

            return Response()->json([
                "message" => "The given data was invalid.",
                "errors" => [
                    "name" =>  [
                        "This phone number already exist"
                    ]
                ]
            ], 200, [], JSON_NUMERIC_CHECK);
        }


        return Response()->json([
            "message" => "The given data was invalid.",
            "errors" => [
                "name" =>  [
                    "The two passwords should be the same."
                ]
            ]
        ], 200, [], JSON_NUMERIC_CHECK);
    }
}
