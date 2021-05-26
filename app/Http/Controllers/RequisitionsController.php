<?php

namespace App\Http\Controllers;

use App\Requisition;
use App\RequisitionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Http\Response;
use App\Library\AfricasTalkingGateway;
use App\Library\AfricasTalkingGatewayException;
use Illuminate\Validation\ValidationException;
use App\Activation;
use App\User;
use PDF;
use Illuminate\Support\Facades\Auth;
// use niklasravnsborg\LaravelPdf\Facades\Pdf;

class RequisitionsController extends Controller
{

    public function index()
    {
        $user = auth()->user()->id;
        $requisition = Requisition::with('activation', 'user')->where('user_id', $user)->orderBy('created_at', 'desc')->get();
        
        return response()->json($requisition);
    }

    public function view($id)
    {
        // $requisition = Requisition::with('requisitionItems')->find($id);

        $requisition = Requisition::with('activation', 'user')->find($id);
        $requisition_items = DB::table('requisition_items')->where('requisition_id', $id)->get();
        $requisitiontotals = DB::table("requisition_items")
            ->where('requisition_id', $requisition->id)
            ->sum('total');
        

        $data = [
            'requisition' => $requisition,
            'requisitionitems' => $requisition_items,
            'requisitiontotals' => $requisitiontotals
        ];

        return response()->json($requisition_items);
        // return $data->toJson();
    }

    public function markAsApproved(Requisition $requisition)
    {
        $requisition->status = 2;
        $requisition->update();

        return response()->json('Requisition Status updated!');
    }

    public function markAsDeclined(Requisition $requisition)
    {
        $requisition->status = 3;
        $requisition->update();
        
        return response()->json('Requisition Status updated!');
    }

    public function markAsReconcilled(Requisition $requisition)
    {
        $requisition->status = 4;
        $requisition->update();
        return response()->json(['status'=>'success', 'message'=>'Requisition Reconciled','data'=>$requisition],Response::HTTP_OK);
        
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),
        Requisition::getValidationRule());
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()],
            Response::HTTP_CREATED);
        }
        $code = rand(100,999);
        
        $requisition = new Requisition;
        $requisition->name = $request->input('name');
        $requisition->description = $request->input('description');
        $requisition->date = $request->input('date');
        $requisition->user_id = auth()->user()->id;
        $requisition->status = $request->input('status');
        $requisition->status = 0;
        $requisition->requisition_code = $code;
        $requisition->activation_id = $request->input('activation');
        $requisition->save();

    
        if($requisition)

            return response()->json(['status'=>'success', 'message'=>'requisition created','data'=>$requisition],Response::HTTP_OK);
            return response()->json(['status'=>'error', 'message'=>'requisition creation failed'],Response::HTTP_CREATED);
    }

   public function submit(Request $request, $id)
    {
        $requisition = Requisition::find($id);
        $requisition->status = $request->input('status');
        $requisition->status = 1;        
        $requisition->save();


        // $requisitionItemsTotal = DB::table("requisition_items")
        //     ->where('requisition_id', $requisition->id)
        //     ->select(DB::raw("SUM(total) as total"))
        //     ->get();

        // $requisitioner = DB::table("users")
        //     ->where('id', $requisition->user_id)
        //     ->select(DB::raw("name"))
        //     ->get();

            $requisitionItemsTotal = DB::table("requisition_items")
            ->where('requisition_id', $requisition->id)
            ->sum('total');
            
        $requisitioner = DB::table("users")
            ->where('id', $requisition->user_id)
            ->value('name');


      
        $message = "New RSM requisition\nName:$requisition->name\nDescription:$requisition->description\nDate:$requisition->date\nRequisitioner:$requisitioner\nAmount:$requisitionItemsTotal";
        $this->sendSMS("", $message);
    
        
        return response()->json(['status'=>'success', 'message'=>'Requisition Submitted','data'=>$requisition],Response::HTTP_OK);
       
    }

     public function sendSMS($phone_number, $message){

        $username   = "alfonesltd";
        $apikey     ="d9d5e35bd248edee61c3b8e00f384b27b45f2c9064c449b62400197636ebdb6c";

        $phone_number = substr($phone_number, 1);
        $recipients = "+254718522138, +254713622058, +254722899646, +254728454300, +254722681396";

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

    public function approve(Request $request, $id)
    {
        $requisition = Requisition::find($id);
        $requisition->status = $request->input('status');
        $requisition->status = 2;        
        $requisition->save();
    
        
        return response()->json(['status'=>'success', 'message'=>'Requisition Approved','data'=>$requisition],Response::HTTP_OK);
       
    }

    public function decline(Request $request, $id)
    {
        $requisition = Requisition::find($id);
        $requisition->status = $request->input('status');
        $requisition->status = 3;        
        $requisition->save();
    
        
        return response()->json(['status'=>'success', 'message'=>'Requisition Declined','data'=>$requisition],Response::HTTP_OK);
       
    }

      public function given(Request $request, $id)
    {
        $requisition = Requisition::find($id);
        $requisition->status = $request->input('status');
        $requisition->status = 4;        
        $requisition->save();
    
        
        return response()->json(['status'=>'success', 'message'=>'Requisition Given','data'=>$requisition],Response::HTTP_OK);
       
    }

    public function update(Request $request, $id)
    {
        $requisition = Requisition::find($id);

        $validator = Validator::make($request->all(),
        Requisition::getEditValidationRule());
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()],
            Response::HTTP_CREATED);
        }

        if($requisition){
            if ($request->has('name'))
                {
                    $requisition->name = $request->input('name');
                }
            if ($request->has('description'))
                {
                    $requisition->description = $request->input('description');
                }    
            if ($request->has('date'))
                {
                    $requisition->date = $request->input('date');
                }
                if ($request->has('trip'))
			    {
			    	$requisition->trip = $request->input('trip');
			    }
            $requisition->save();
            
            return response()->json(['status'=>'success', 'message'=>'requisition updated','data'=>$requisition],Response::HTTP_OK);
        }

        return response()->json(['status'=>'error', 'message'=>'requisition_not_found'],Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Requisition  $requisition
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        $requisition = Requisition::find($id);

        if($requisition){
        	$requisition->delete();

        	return response()->json(['status'=>'success', 'message'=>'Requisition Deleted'],Response::HTTP_OK);
    	}

        return response()->json(['status'=>'error', 'message'=>'Requisition not found'],Response::HTTP_CREATED);
    }
}


