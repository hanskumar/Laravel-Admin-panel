<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\PincodeMaster;
use Illuminate\Support\Facades\Validator;

class PincodeController extends Controller {


    public function add(Request $request){

        if(request('submit')){
    
            $validator = Validator::make($request->all(), [
                'zone' => 'required',
                'state' => 'required',
                'city'  => 'required',
                'pincode' =>'required', 
                'address' => 'required', 
            ]);
    
            if ($validator->fails()) {
                set_flashdata('message', 'Please fill all required fields', 'error');
                return redirect('add-pincode')
                            ->withErrors($validator)
                            ->withInput();
            }
    
            $Pincode = new PincodeMaster;
    
                    
            $Pincode->zone 	= $request->zone;
            $Pincode->state   = $request->state;
            $Pincode->city 	= $request->city;
            $Pincode->pincode 	= $request->pincode;
            $Pincode->address 	= $request->address;  
            $Pincode->date 	= date('Y-m-d');  
            $Pincode->status 	= 1;

            $saved = $Pincode->save();

            if($saved){
                set_flashdata('message', 'Pincode Saved Successfully', 'success');
            } else {
                set_flashdata('message', 'Somthing Went Wrong,Please Try Again', 'error');
            }
    
            return back();
    
            } else {
    
                $data['title'] = 'Add new Pincode';
                $data['includes'] = array("select2");
                return view('pages/add_pincode',$data);
            }
        }
    
}
