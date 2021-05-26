<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use App\User;
use App\Client;
use App\Activation;
use App\Route;
use App\Requisition;

class ActivationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Activation::with('client', 'user')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {   
        $activation = Requisition::with('client', 'user')->find($id);
        
        if($activation){
            return response()->json(['status'=>'success', 'message'=>'Activation found','data'=>$activation],Response::HTTP_OK);
        }

        return response()->json(['status'=>'error', 'message'=>'Activation not found'],Response::HTTP_CREATED);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function create(Request $request)
    {
       

        $validator = Validator::make($request->all(),
        Activation::getValidationRule());
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()],
            Response::HTTP_CREATED);
        }        
        $activation = new Activation;
        $activation->name = $request->input('name');
        $activation->start_date = $request->input('start_date');
        $activation->end_date = $request->input('end_date');
        $activation->user = auth()->user()->id;
        $activation->client = $request->input('client');
        $activation->status = $request->input('status');
        $activation->status = 0;
        $activation->save();

        if($activation)
            return response()->json(['status'=>'success', 'message'=>'Activation created','data'=>$activation],Response::HTTP_OK);
            return response()->json(['status'=>'error', 'message'=>'Activation creation failed'],Response::HTTP_CREATED);
    }


    public function update(Request $request, $id)
    {
        $activation = Actvation::find($id);

    	$validator = Validator::make($request->all(), Activation::getEditValidationRule());
		if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], Response::HTTP_CREATED);            
        }

    	if($activation){

    		if ($request->has('name'))
			    {
			    	$activation->name = $request->input('name');
                }
                
                if ($request->has('start_date'))
			    {
			    	$activation->from_when = $request->input('start_date');
                }
                
                if ($request->has('end_date'))
			    {
			    	$activation->to_when = $request->input('end_date');
                }
                if ($request->has('client'))
			    {
			    	$activation->client = $request->input('client');
			    }

			$activation->save();

    		return response()->json(['status'=>'success', 'message'=>'Activation updated','data'=>$activation],Response::HTTP_OK);
    	}

    	return response()->json(['status'=>'error', 'message'=>'Activation not found'],Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Activation  $activation
     * @return \Illuminate\Http\Response
     */

    
    public function edit(Activation $activation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Activation  $activation
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        $activation = Activation::find($id);

        if($activation){
        	$activation->delete();

        	return response()->json(['status'=>'success', 'message'=>'Activation deleted'],Response::HTTP_OK);
    	}

        return response()->json(['status'=>'error', 'message'=>'Activation not found'],Response::HTTP_CREATED);
    }
}
