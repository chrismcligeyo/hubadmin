<?php

namespace App\Http\Controllers;


use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\RequisitionItem;
use App\Requisition;
use App\Activation;

class RequisitionItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return RequisitionItem::with('requisition')->get();

    }

    public function total()
    {
        $requisition_items = DB::table("requisition_items")
            ->with(DB::raw('requisition'))
            ->select(DB::raw("SUM(total) as count"))
            ->groupBy(DB::raw("requisition"))
            ->get();

        $requisition_items = $json_array;
        return json_encode($requisition_items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $requisition_item = RequisitionItem::with('requisition')->find($id);

        if($requisition_item) {
            return response()->json(['status'=>'success',
            'message'=>'requisition item found','data'=>$requisition_item],Response::HTTP_OK);
        }

        return response()->json(['status'=>'error', 'message'=>'requisition_item_not_found'],Response::HTTP_CREATED);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), RequisitionItem::getValidationRule());
		if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], Response::HTTP_CREATED);            
        }

        $requisition_item = new RequisitionItem;
        $requisition_item->itemname = $request->input('itemname');
        $requisition_item->supplier = $request->input('supplier');
        $requisition_item->unit_cost = $request->input('unit_cost');
        $requisition_item->quantity = $request->input('quantity');
        $requisition_item->requisition_id = $request->input('requisition_id');
        $requisition_item ->total = $requisition_item->unit_cost * $requisition_item->quantity; 
        $requisition_item->save();

        if($requisition_item)
            return response()->json(['status'=>'success', 'message'=>'requisition item created','data'=>$requisition_item],Response::HTTP_OK);
            return response()->json(['status'=>'error', 'message'=>'requisition item creation failed'],Respponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RequisitionItem  $requisitionItem
     * @return \Illuminate\Http\Response
     */
    public function show(RequisitionItem $requisitionItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RequisitionItem  $requisitionItem
     * @return \Illuminate\Http\Response
     */
    public function edit(RequisitionItem $requisitionItem)
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
        $requisition_item = RequisitionItem::find($id);

    	$validator = Validator::make($request->all(), RequisitionItem::getEditValidationRule());
		if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], Response::HTTP_CREATED);            
        }

    	if($requisition_item){

    		if ($request->has('itemname'))
			    {
			    	$requisition_item->itemname = $request->input('itemname');
			    }

			if ($request->has('supplier'))
			    {
			    	$requisition_item->supplier = $request->input('supplier');
			    }
			if ($request->has('unit_cost'))
			    {
			    	$requisition_item->unit_cost = $request->input('unit_cost');
			    }
			if ($request->has('quantity'))
			    {
			    	$requisition_item->quantity = $request->input('quantity');
			    }
			if ($request->has('requisition'))
                {
                    $requisition_item->requisition = $request->input('requisition');
                }

			$requisition_item->save();

    		return response()->json(['status'=>'success', 'message'=>'requisition_item_updated','data'=>$requisition_item],Response::HTTP_OK);
    	}

    	return response()->json(['status'=>'error', 'message'=>'requisition_item_not_found'],Response::HTTP_CREATED);
    }

    /**nothing is arj
     * Remove the specified resource from storage.
     *
     * @param  \App\RequisitionItem  $requisitionItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $requisition_item = RequisitionItem::find($id);

        if($requisition_item){
        	$requisition_item->delete();

        	return response()->json(['status'=>'success', 'message'=>'requisition_item_deleted'],Response::HTTP_OK);
    	}

        return response()->json(['status'=>'error', 'message'=>'requisition_item_not_found'],Response::HTTP_CREATED);
    }
}
