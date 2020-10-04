<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\User;

use Session;

class LoginController extends Controller{

    public function index(Request $request){

        if(!empty(request('sign-in'))){

    		$password = request('password');
    		$email = request('email');

    		$validated = request()->validate([
				'email' => 'required',
				'password' => 'required'
			]);

			$user = User::where('email', $email)
					->where('role', '=', 'admin')
					->first();
					
			if(!empty($user)){

				if($user->password == $password){

					//========= Set Login session =========
					set_login_sessions($user);

					//dd($request->session()->get('role'));

					set_flashdata('message', 'Login Succssfully', 'success');

					return redirect('dashboard');

					/* $vars = [
						"id" => $user->id,
						"name" => $user->name,
						"user_id" => $user->user_id,
						"phone" => $user->phone,
						"role" => $user->role,
					];

					$session_data = Session::put('user_session', $vars);
					dd(session('user_session.name')); */


				} else {
					set_flashdata('message', 'Incorrect password entered', 'error');
					return back();
                }


			} else {
				set_flashdata('message', 'Incorrect password entered', 'error');
				return back();

			}
		} 
    	return view('pages/login');

	}
	

	public function logout(){
		session()->flush();
		return redirect('/');
	}
}
