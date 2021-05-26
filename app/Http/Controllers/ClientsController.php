<?php

namespace App\Http\Controllers;


use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

use App\Client;

class ClientsController extends Controller
{

	public function index(){

    	return Client::all();
    }

    public function view($id){

    	$client = Client::find($id);

    	if($client){
    		return response()->json(['status'=>'success', 'message'=>'Client Found','data'=>$client],Response::HTTP_OK);
    	}

    	return response()->json(['status'=>'error', 'message'=>'client not found'],Response::HTTP_CREATED);
    }

    public function update(Request $request, $id){

    	$client = Client::find($id);

    	$validator = Validator::make($request->all(), Client::getEditValidationRule());
		if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], Response::HTTP_CREATED);            
        }

    	if($client){

    		if ($request->has('name'))
			    {
			    	$client->name = $request->input('name');
			    }

			if ($request->has('email'))
			    {
			    	$client->email = $request->input('email');
			    }
			if ($request->has('logo'))
			    {
			    	$client->logo = $request->file('logo');
			    }
			if ($request->has('website'))
			    {
			    	$client->website = $request->input('website');
                }
            if ($request->has('contact_person'))
			    {
			    	$client->contact_person = $request->input('contact_person');
			    }

			$client->save();

    		  return response()->json(['status'=>'success', 'message'=>'Client updated','data'=>$client],Response::HTTP_OK);

          
    	}

    	return response()->json(['status'=>'error', 'message'=>'client not found'],Response::HTTP_CREATED);
    }

    public function create(Request $request){
        
        $validator = Validator::make($request->all(), Client::getValidationRule());
		if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], Response::HTTP_CREATED);            
        }

        $client = new Client;
        if(!$request->hasFile('logo')){
            $response = Client::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'website' => $request->input('website'),
                'contact_person' => $request->input('contact_person')
            ]);
        }else{
            $response = Client::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'logo' => $request->file('logo')->store('public/logos'),
                'website' => $request->input('website'),
                'contact_person' => $request->input('contact_person')
            ]);
        }

        if($response)
            return response()->json(['status'=>'success', 'message'=>'client created','data'=>$response],Response::HTTP_OK);
        return response()->json(['status'=>'error', 'message'=>'client creation failed'],Response::HTTP_CREATED);
    }

    public function delete(Request $request, $id)
    {
        $client = Client::find($id);

        if($client){
        	$client->delete();

        	return response()->json(['status'=>'success', 'message'=>'client deleted'],Response::HTTP_OK);
    	}

        return response()->json(['status'=>'error', 'message'=>'client not found'],Response::HTTP_CREATED);
    }

    
}
