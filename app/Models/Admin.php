<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\AdminRequest;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'gender',
        'phone',
        'password',
        'avatar'
    ];


    public static function postAdmin(AdminRequest $request){
        $contact = $request->input('phone');
        if ($request->input('password') === $request->input('confirm_password')) {
            
            if (count(Admin::where('phone',$contact)->get()) === 0 ) {
                $user =  Admin::create([
                     "name" => $request->input('name'),
                     'gender' => $request->input('gender'),
                     "phone" => $contact,
                     'password' => $request->input('password'),
                     "avatar" => $request->input('avatar')
                 ]);
                 return Response()->json($user, 200, [], JSON_NUMERIC_CHECK);
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
