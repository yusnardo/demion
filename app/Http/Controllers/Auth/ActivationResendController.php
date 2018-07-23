<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Events\Auth\UserActivationEmail;


class ActivationResendController extends Controller
{
    public function showResendForm(){
    	return view('auth.resend');
    }

    public function resend(Request $request){
    	$this->validateResendRequest($request);
    	$user = User::where('email', $request->email)->first();
    	event(new UserActivationEmail($user));
    	if($user->active) {
    		return redirect()->route('login')->withSuccess('Your account is already active. You can login with your current email.');
    	}
    	else {
    		return redirect()->route('login')->withSuccess('Verification email has been resent.');
    	}	
    }

    protected function validateResendRequest(Request $request){
    	$this->validate($request, [
    		'email' => 'required|email|exists:users,email'
    	], [
    		'email.exists' => "This email is not registered."
    	]);
    }
}
