<?php

namespace App\Http\Controllers\api\v1;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use App\Http\Resources\api\v1\DataResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use function PHPUnit\Framework\isNull;

class UsersController extends Controller
{
    public function register(Request $request)
    {

        $reg = new CreateNewUser();
        $res = $reg->create($request->toArray());

        if (isset($res->errors) && !isNull($res->errors)){
            return $res;
        }

        return new DataResource([
            'user' => $res->makeHidden(['email_verified_at', 'email', 'created_at', 'updated_at', 'current_team_id', 'profile_photo_path']),
            'token' => $this->generateToken($request, $res),
            'message' => 'Registration successful. Enjoy!'
        ]);

    }

    public function login(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();

        if (!Hash::check($request->password, $user->password)) {
           return response([
               'message' => 'Invalid Credentials'
           ], 400);
        }

        return new DataResource([
            'user' => $user->makeHidden(['email_verified_at', 'email', 'created_at', 'updated_at', 'current_team_id', 'profile_photo_path']),
            'token' => $this->generateToken($request, $user),
            'message' => 'Login successful. Enjoy!'
        ]);
    }

    public function profile()
    {
        return new DataResource(auth()->user());
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();

        return response([
            'message' => 'Logout Successful. See you soon!'
        ]);
    }

    public function logoutAll()
    {
        auth()->user()->tokens()->delete();

        return response([
            'message' => 'Logout from all devices Successful. See you soon!'
        ]);
    }


    public function generateToken($request, $user)
    {
        $agent = ($request->header('User-Agent')) ? $request->header('User-Agent') : 'Unknown User Agent';

        return $user->createToken($user->first_name . '-' . $user->phone . ' - ' . $agent)
            ->plainTextToken;
    }
}
