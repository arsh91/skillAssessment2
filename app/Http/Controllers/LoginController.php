<?php

namespace App\Http\Controllers;

use Validator;
use Session;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function getLogin()
    {
        return view('login');
    }

    /**
     * Show the application loginprocess.
     *
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
      
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);
        // ['email' => "admin@admin.com", 'password' => "password"]
        $credential=$request->only('email', 'password');
        // dd($request->email);
        if(Auth::attempt($credential)){ 
            $user = Auth::user();
            $username = $user->name;
            return response()->json([
              'status'   => 'success',
              'user' => $username,
            ]); 
          } else { 
            return response()->json([
              'status' => 'error',
              'user'   => 'Unauthorized Access'
            ]); 
          } 

    }

    /**
     * Show the application logout.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::logout();
        \Session::flush();      
        return redirect(route('usersLogin'));
    }
    public function home()
    {
    }
}
?>