<?php

namespace App\Http\Controllers;

use App\Models\Locations;
use App\Models\User;
use App\Models\Reserve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class UsersController extends Controller
{
    /**
     * inscrire un utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'password' => 'required|string',
        ]);
        $contact = $request->input('phone');
        $email = $request->email;
        if ($request->input('password') === $request->input('confirm_password')) {

            if (count(User::where('email', $email)->get()) === 0) {
                $user =  User::create([
                    "name" => $request->input('name'),
                    "first_name" => $request->input('first_name'),
                    "email" => $email,
                    'gender' => $request->input('gender'),
                    "phone" => $contact,
                    'password' => Hash::make($request->input('password'))

                ]);
                return Response()->json([
                    'type' => 'success',
                    'message' => 'user register successfully',
                    'data' =>    $user

                ], 201, [], JSON_NUMERIC_CHECK);
            } else {
                return Response()->json([
                    "type" => "error",
                    "message" => "this email is already exist"
                ], 200, [], JSON_NUMERIC_CHECK);
            }
        } else {

            return Response()->json([
                "type" => "error",
                "message" => "The two passwords should be the same."

            ], 200, [], JSON_NUMERIC_CHECK);
        }
    }

    /**
     * connecter un client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:191',
            'password' => 'required|string',
        ]);

        $user =  User::where('email', $request->email)->first();


        if (!$user) {
            return response()->json([
                'type' => 'error',
                'message' => 'invalid email'
            ]);
        } else if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'type' => 'error',
                'message' => 'invalid password'
            ]);
        } else {
            $token =  $user->createToken('yvesFoyetTokenLogin')->plainTextToken;
            return response()->json([
                'type' => 'success',
                'message' => 'user connected successfully',
                'user' => $user,
                'token' => $token
            ], 200);
        }
    }

    /**
     * deconnecter un utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function logout(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $tokenId = $request->user_id;
        $user = User::find($tokenId);
        $user->tokens()->where('tokenable_id', $tokenId)->delete();
        if ($user) {
            return response()->json([
                'type' => 'success',
                'message' => 'deconnected successfully'
            ], 200);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'check your network'
            ], 403);
        }
    }

    /**
     * envoyer un mail pour un mot de passe oublié.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return response
     */

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? ['status' => __($status)]
            : ['email' => __($status)];
    }

    /**
     * modifier un mot de passe oublié.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return response
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]); //->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
    /**
     * recuperer les clients de la bd.
     * @return response
     */

    public function getUsers()
    {
        $user = User::select(['name', 'gender', 'phone', 'avatar'])->get();
        return response()->json([
            'type' => 'success',
            'data' => $user
        ], 200);
    }
    /**
     * recuperer un utilisateur par son id.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */

    public function getUserById(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $id = $request->id;
        $result = count($postCustomer = User::where('id', $id)->get());
        if ($result == 0) {
            return response()->json([
                'type' => 'error',
                'message' => 'is not exist a customer who has this id '
            ], 403, [], JSON_NUMERIC_CHECK);
        } else {

            $postCustomer = User::findOrFail($request->id)->get();

            if ($postCustomer) {
                return response()->json([

                    'type' => 'success',
                    'message' => 'a customer has been find',
                    'name' =>  $postCustomer[0]->name,
                    'first_name' =>  $postCustomer[0]->first_name,
                    'gender' =>  $postCustomer[0]->gender,
                    'password' =>  $postCustomer[0]->password,
                    'avatar' =>  $postCustomer[0]->avatar
                ], 200, [], JSON_NUMERIC_CHECK);
            } else {
                return response()->json([

                    'type' => 'error',
                    'message' => 'this has not  find'
                ], 404, [], JSON_NUMERIC_CHECK);
            }
        }
    }
    /**
     *modifier les parametres .
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([

            'id' => 'required'
        ]);
        $id = $request->id;
        $result = count(User::where('id', $id)->get());
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

            $updateCustomer = User::find($id);
            $name = $request->input('name');
            $email = $request->input('email');
            $phone = $request->input('phone');
            $password = $request->input('password');
            $confirm_password = $request->input('confirm_password');
            if ($password === $confirm_password) {

                $updateCustomer->name = $name;
                $updateCustomer->email = $email;
                $updateCustomer->phone = $phone;
                $updateCustomer->password = $password;
                // $updateCustomer->save();
            } else {

                return response()->json(
                    [
                        'type' => 'error',
                        'message' => 'passowrd is not same'

                    ],
                    404,
                    [],
                    JSON_NUMERIC_CHECK
                );
            }


            if ($updateCustomer) {
                return response()->json(
                    [
                        'type' => 'success',
                        'message' => 'the update is successfully',
                        'data' => $updateCustomer
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
     *supprimer un utilisateur.
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
        $del_cus = User::find($id)->delete();

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
     * modifier la photo de profil.
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

                    $result = DB::update('update users set avatar = ? where id = ?', [$path, $id]);

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

    /**
     * recuperer un utilisateur et le nommbre de reservation qu'il a effectuées.
     *
     * @param  \App\Models\Request $request
     * @return \Illuminate\Http\Response
     */

    public function getReservationsByUser(Request $request)
    {

        $request->validate(

            [
                'customer_id' => 'required|numeric'

            ]

        );

        if (count(User::where('user_id', $request->customer_id)->get()) != 0) {

            $result = count(Reserve::where('customer_id', $request->customer_id)->where('status', 1)->orwhere('status', 2)->get());
            return response()->json(
                [
                    'type' => 'success',
                    'message' => "you have do $result reservation this year"
                ],
                200
            );
        } else {

            return response()->json(
                [
                    'type' => 'error',
                    'message' => 'this customer does not exist'
                ],
                200
            );
        }
    }
}
