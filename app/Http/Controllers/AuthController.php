<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
class AuthController extends Controller
{
    public function index( Request $request )
{
    return response()->json( [
    'status' => 'success',
     ]);
  
}
    public function login(Request $request) {
        
       
        $validator = Validator::make($request->all(),[
            "email" => "required|email",
            "password" => "required|min:6"
       ],[
            "email.required" => "Email boş bırakılamaz",
            "email.email" => "Geçerli bir email adresi giriniz",
            "password.required" => "Şifre boş bırakılamaz",
            "password.min" => "Şifre en az 6 karakter olmalıdır"
       ]);
      if($validator->fails()) {
        return response()->json([
            'status'=> 'error',
            'errors'=> $validator->errors()
        ],422);
      }
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            
            $user = Auth::user();
            $name = $user->name;
            $token = $user->createToken('token')->plainTextToken;
    
            return response()->json([
                'status' => 'success',
                'message' => 'Giriş başarılı',
                'user' => $user,
                'token'=> $token,
                'name'=> $name
            ]);
        } else {
            return response()->json([
                'status' => 'fail',
                'message' => 'Bu e-posta adresi veya şifre geçersiz.',
            ]);
       }
    }
    

    public function registration(Request $request) { // register
        $validator = Validator::make($request->all(),[
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|min:6|confirmed"
        ], [
            "name.required" => "İsim boş bırakılamaz.",
            "email.required" => "Email boş bırakılamaz.",
            "email.email" => "Geçerli bir email adresi giriniz.",
            "email.unique" => "Bu email zaten kayıtlı.",
            "password.required" => "Şifre boş bırakılamaz.",
            "password.min" => "Şifre en az 6 karakter olmalıdır.",
            "password.confirmed" => "Şifreler eşleşmiyor."
        ]);
        if($validator->fails()) {
            return response()->json([
                'status'=> 'error',
                'errors'=> $validator->errors()
            ],422);
          }

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        if (!$user) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Kayıt başarısız oldu, tekrar deneyin.'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Başarılı bir şekilde kayıt oldunuz!',
            'user' => $user
        ]);
    }

    public function logout(Request $request) {
        
        $token = $request->bearerToken();

        if($token){
            $userToken =PersonalAccessToken::findToken($token);

            if($userToken){

                $userToken->delete();
                Auth::logout();
                return response()->json([
                    'status'=> 'success',
                    'message'=>'Başarılı Bir Şekilde Çıkış Yaptınız !'
                ],200);
            }
        }
        return response()->json([
            'status'=> 'error',
            'message'=>'Çıkış yaparken bir sorun oluştu !'
        ],200);
    
}

}