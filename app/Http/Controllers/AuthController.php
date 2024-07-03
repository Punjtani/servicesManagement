<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
class AuthController extends Controller
{
    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email' => ['required','email', Rule::unique('users')->where('is_active', 1)],
            'password' => 'required|min:3|confirmed',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        $user = new User();
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->is_active = 1;
        $user->save();
        return response()->json(['status' => 'success'], 200);
    }

    public function login(Request $request)
    {
       
        $credentials = $request->only('email', 'password');
        JWTAuth::factory()->setTTL(8000);
        if ($token = $this->guard()->attempt($credentials, ['exp' => Carbon::now()->addDays(5)->timestamp])) {
            // if(Auth::user()->is_active && Auth::user()->user_activated){
            // dd(Auth::user());
                    return response()->json(['status' => 'success','password_updated' => Auth::user()->password_updated ?? null, 'token' => $token, 'role'=>Auth::user()->role, 'parent'=>Auth::user()->staff?Auth::user()->staff->parent_id:null], 200)->header('Authorization', $token);

            // }
        }
        return response()->json(['error' => 'login_error'], 401);
    }

    public function logout()
    {
        $this->guard()->logout();
        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
        ], 200);
    }

    public function user(Request $request)
    {
        $user = User::with('client','roles','staff', 'employee')->find(Auth::user()->id);
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function refresh()
    {
        if ($token = $this->guard()->refresh()) {
            return response()
                ->json(['status' => 'successs'], 200)
                ->header('Authorization', $token);
        }
        return response()->json(['error' => 'refresh_token_error'], 401);
    }

    private function guard()
    {
        return Auth::guard();
    }

    public function checkToken()
    {
        return response()->json(['success' => true, 200]);
    }
    public function sendResetPasswordLink(Request $request){
        $request->validate(['email' => 'required|email']);
        $email = $request->only('email');
        $status = Password::sendResetLink($email);
        if($status === Password::RESET_LINK_SENT ){
            return response()->json([
                'msg' => "Password reset link has been sent",
            ], 200);
        }else{
            return response()->json([
                'errormsg' => 'Something is not right, Please check your information and try again!',
            ], 200);
        }
    }
    public function resetPassword(Request $request){
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);
        $status = Password::reset(
            $request->only('email','password', 'confirm_password', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => \Hash::make($password)
                ]);
                $user->save();
                // event(new PasswordReset($user));
            }
        );
        if($status === Password::PASSWORD_RESET){
            return response()->json([
                'msg' => "Password reset successful",
            ], 200);
        }else{
            return response()->json([
                'errormsg' => 'Something is not right, Please check your information and try again!',
            ], 200);
        }
    }
    public function updatePassword(Request $request){

        $request->validate([
            'new_password' => 'required',
        ]);
           $user =  Auth::user();

            $user->password = Hash::make($request->new_password);
            $user->password_updated = 1;
            $user->save();

            return response()->json([
                'message' => "Password updated successfully",
                'status' => "success",
            ], 200);


    }
}

