<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller; 
use App\Library\AfricasTalkingGateway;
use App\Library\AfricasTalkingGatewayException;
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Validation\ValidationException;
use Validator;


class AuthController extends Controller
{
	/** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(Request $request){ 

        $validator = Validator::make($request->all(), User::getValidationRule());
		if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], Response::HTTP_UNAUTHORIZED);            
        }

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $success=  $user->createToken('RSMhub')-> accessToken; 
            $id = $user->id;
            $activated = $user->activated;

            $info = [
            'token' => $success,
            'id' => $id,
            'activated' => $activated
            ];
            
            return response()->json(['status'=>'success','message'=>'Login successful','data' => $info], Response::HTTP_OK); 
        } 
        else{ 
            return response()->json(['status'=>'error','message'=>'Email or Password is incorrect'], Response::HTTP_UNAUTHORIZED); 
        } 
    }
	/** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        $password = rand(1000,9999);

            $user = User::create([
                    'phone_number'            => $request->input('phone_number'),
                    'name'       => $request->input('name'),
                    'email'            => $request->input('email'),
                    'id_number'            => $request->input('id_number'),
                    'password'         => bcrypt($password),
                    'token'            => str_random(64),
                    'activated'        => 1,
                ]);
            
            $user->save();

             $message = "Welcome to RSM App\nEmail: $user->email\nPassword:$password";
             $this->sendSMS($user->phone_number, $message);
             
            $to = "$user->email";
            $subject = "RSM App Login Credentials";
            $txt = "Email: $user->email\nPassword:$password";
            $headers = "From: info@roadshowmasters.com" . "\r\n" .
            "CC: ken@roadshowmasters.com";
            
            mail($to,$subject,$txt,$headers);
        

		return response()->json(['status'=>'success', 'message'=>'User Created Successfully', 'data'=>$user], Response::HTTP_OK); 
    }
	/** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], Response::HTTP_OK); 
    } 

    public function sendSMS($phone_number, $message){

        $username   = "roadshowm";
        $apikey     = "b5caaa0cba2f6dd62d51e755a0e4ae7cec2a169ee542a192e608482e4e75d030";

        $phone_number = substr($phone_number, 1);
        $recipients = "+254$phone_number";

        $gateway = new AfricasTalkingGateway($username, $apikey);
        try
        {
            // Thats it, hit send and we'll take care of the rest.
            $results = $gateway->sendMessage($recipients, $message);

            foreach($results as $result) {

                $status = $result->status;
                if($status=='Success'){

                }
                if($status==0){

                }
            }
        }
        catch ( AfricasTalkingGatewayException $e )
        {

        }


    }

}
