<?php

namespace App\Http\Controllers;

use App\Reconciliation;
use App\Requisition;
use App\User;
use Validator;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class ReconciliationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Reconciliation::with('requisition')->orderBy('created_at','asc')->get();
    }

    public function view($id){
    	$reconciliation = Reconciliation::with('requisition')->find($id);

    	if($reconciliation){
    		return response()->json(['status'=>'success', 'message'=>'Reconciliation Found','data'=>$reconciliation],Response::HTTP_OK);
    	}

    	return response()->json(['status'=>'error', 'message'=>'Reconciliation not found'],Response::HTTP_CREATED);
    }
   
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), Reconciliation::getValidationRule());
		if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], Response::HTTP_CREATED);            
        }

        $reconciliation = new Reconciliation;
        if(!$request->hasFile('cover_image')){
            $response = Reconciliation::create([
                'amount' => $request->input('amount'),
                'requisition' => $request->input('requisition'),
                'date' => $request->input('date')
            ]);
        }else{
            $response = Reconciliation::create([
                'amount' => $request->input('amount'),
                'requisition' => $request->input('requisition'),
                'cover_image' => $request->file('cover_image')->store('public/cover_images'),
                'date' => $request->input('date')
            ]);
        }

        if($response)
            return response()->json(['status'=>'success', 'message'=>'Reconciliation created','data'=>$response],Response::HTTP_OK);
        return response()->json(['status'=>'error', 'message'=>'Reconciliation creation failed'],Response::HTTP_CREATED);
    }

    public function update(Request $request, $id){

    	$reconciliation = Reconciliation::find($id);

    	$validator = Validator::make($request->all(), Reconciliation::getEditValidationRule());
		if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], Response::HTTP_CREATED);            
        }

    	if($reconciliation){

    		if ($request->has('amount'))
			    {
			    	$reconciliation->amount = $request->input('amount');
			    }

			if ($request->has('date'))
			    {
			    	$reconciliation->date = $request->input('date');
			    }
			if ($request->has('cover_image'))
			    {
			    	$reconciliation->cover_image = $request->file('cover_image');
			    }

			$reconciliation->save();

    		  return response()->json(['status'=>'success', 'message'=>'reconciliation updated','data'=>$reconciliation],Response::HTTP_OK);

          
    	}

    	return response()->json(['status'=>'error', 'message'=>'reconciliation not found'],Response::HTTP_CREATED);
    }

    public function delete(Request $request, $id)
    {
        $reconciliation = Reconciliation::find($id);

        if($reconciliation){
        	$reconciliation->delete();

        	return response()->json(['status'=>'success', 'message'=>'reconciliation deleted'],Response::HTTP_OK);
    	}

        return response()->json(['status'=>'error', 'message'=>'reconciliation not found'],Response::HTTP_CREATED);
    }

}
