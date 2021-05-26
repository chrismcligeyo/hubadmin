<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use Illuminate\Http\Response;
use App\Activation;
use Illuminate\Support\Facades\DB;
use App\ActivationReport;
use Illuminate\Http\Request;

class ActivationReportsController extends Controller
{
    
    public function index()
    {
        return ActivationReport::with('activation', 'user')->orderBy('created_at','asc')->get();
    }

    public function view($id){
    	$ActivationReport = ActivationReport::with('activation', 'user')->find($id);
        $requisition = DB::table('requisitions')->where('activation', $id)->get();

        $data = [
            'ActivationReport' => $ActivationReport,
            'requisition' => $requisition
        ];

        return response()->json($data);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), ActivationReport::getValidationRule());
		if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], Response::HTTP_CREATED);            
        }

        $ActivationReport = new ActivationReport;
        if(!$request->hasFile('cover_image')){
            $response = ActivationReport::create([
                'activation' => $request->input('activation'),
                'what_worked' => $request->input('what_worked'),
                'what_failed' => $request->input('what_failed'),
                'feedback' => $request->input('feedback'),
            ]);
        }else{
            $response = ActivationReport::create([
                'activation' => $request->input('activation'),
                'what_worked' => $request->input('what_worked'),
                'what_failed' => $request->input('what_failed'),
                'feedback' => $request->input('feedback'),
                'cover_image' => $request->file('cover_image')->store('public/cover_images'),
                'cover_image_two' => $request->file('cover_image_two')->store('public/cover_images'),
                'cover_image_three' => $request->file('cover_image_three')->store('public/cover_images'),
               
            ]);
        }

        if($response)
            return response()->json(['status'=>'success', 'message'=>'Activation Reports created','data'=>$response],Response::HTTP_OK);
        return response()->json(['status'=>'error', 'message'=>'Activation Report creation failed'],Response::HTTP_CREATED);
    }

    public function update(Request $request, $id){

    	$ActivationReport = ActivationReport::find($id);

    	$validator = Validator::make($request->all(), ActivationReport::getEditValidationRule());
		if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], Response::HTTP_CREATED);            
        }

    	if($ActivationReport){

    		if ($request->has('activation'))
			    {
			    	$ActivationReport->acivation = $request->input('actiation');
			    }

			if ($request->has('what_worked'))
			    {
			    	$ActivationReport->what_worked = $request->input('what_worked');
			    }
			if ($request->has('what_failed'))
			    {
			    	$ActivationReport->what_failed = $request->file('what_failed');
                }
            if ($request->has('feedback'))
			    {
			    	$ActivationReport->feedback = $request->file('feedback');
                }
            if ($request->has('cover_image'))
			    {
			    	$ActivationReport->cover_image = $request->file('cover_image');
                }
            if ($request->has('cover_image_two'))
			    {
			    	$ActivationReport->cover_image_two = $request->file('cover_image_two');
                }
            if ($request->has('cover_image_three'))
			    {
			    	$ActivationReport->cover_image_three = $request->file('cover_image_three');
			    }

			$ActivationReport->save();

    		  return response()->json(['status'=>'success', 'message'=>'Activation Report updated','data'=>$ActivationReport],Response::HTTP_OK);

    	}

    	return response()->json(['status'=>'error', 'message'=>'Activation Report not found'],Response::HTTP_CREATED);
    }

    public function delete(Request $request, $id)
    {
        $ActivationReport = ActivationReport::find($id);

        if($ActivationReport){
        	$ActivationReport->delete();

        	return response()->json(['status'=>'success', 'message'=>'report deleted'],Response::HTTP_OK);
    	}

        return response()->json(['status'=>'error', 'message'=>'report not found'],Response::HTTP_CREATED);
    }
}    