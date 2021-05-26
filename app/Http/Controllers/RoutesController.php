<?php

namespace App\Http\Controllers;

use App\Route;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Activation;

class RoutesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return Route::with('activation')->get();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $route = Route::with('trip')->find($id);

        if($route) {
            return response()->json(['status'=>'success',
            'message'=>'Route found','data'=>$route],Response::HTTP_OK);
        }

        return response()->json(['status'=>'error', 'message'=>'No Route'],Response::HTTP_CREATED);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), Route::getValidationRule());
		if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], Response::HTTP_CREATED);            
        }

        $route = new Route;
        $route->name = $request->input('name');
        $route->date = $request->input('date');
        $route->activation= $request->input('activation');
        $route->save();

        if($route)
            return response()->json(['status'=>'success', 'message'=>'route created','data'=>$route],Response::HTTP_OK);
        return response()->json(['status'=>'error', 'message'=>'route creation failed'],Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RequisitionItem  $requisitionItem
     * @return \Illuminate\Http\Response
     */
    public function show(Route $route)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RequisitionItem  $requisitionItem
     * @return \Illuminate\Http\Response
     */
    public function edit(Route $route)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RequisitionItem  $requisitionItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $route = Route::find($id);

    	$validator = Validator::make($request->all(), Route::getEditValidationRule());
		if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], Response::HTTP_CREATED);            
        }

    	if($route){

    		if ($request->has('name'))
			    {
			    	$route->name = $request->input('name');
			    }

			if ($request->has('date'))
			    {
			    	$route->date = $request->input('date');
			    }
			if ($request->has('trip'))
                {
                    $route->trip = $request->input('trip');
                }

			$route->save();

    		return response()->json(['status'=>'success', 'message'=>'route updated','data'=>$route],Response::HTTP_OK);
    	}

    	return response()->json(['status'=>'error', 'message'=>'route not found'],Response::HTTP_CREATED);
    }

    /**nothing is arj
     * Remove the specified resource from storage.
     *
     * @param  \App\RequisitionItem  $requisitionItem
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Request $request, $id)
    // {
    //     $requisition_item = RequisitionItem::find($id);

    //     if($requisition_item){
    //     	$requisition_item->delete();

    //     	return response()->json(['status'=>'success', 'message'=>'requisition_item_deleted'],Response::HTTP_OK);
    // 	}

    //     return response()->json(['status'=>'error', 'message'=>'requisition_item_not_found'],Response::HTTP_CREATED);
    // }
}
