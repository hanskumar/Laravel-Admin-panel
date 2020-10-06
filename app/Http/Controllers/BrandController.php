<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Companies;
use App\Models\User;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller{

    public function add(Request $request){

        if(request('submit')){

            $validator = Validator::make($request->all(), [
                'brand_name' => 'required',
                'brand_email' => 'required',
                'brand_phone'  => 'required',
                'contact_person'  => 'required',
                'shipping_type'   => 'required',
                'min_order_value'  => 'required',
                'shipping_charges' =>'required', 
                'address' => 'required', 
                'brand_image' => 'required|image|mimes:jpg,png,jpeg|max:2048|dimensions:min_width=100,min_height=100',
    
            ]);
    
            if ($validator->fails()) {
                set_flashdata('message', 'Please fill all required fields', 'error');
                return redirect('add-brand')
                            ->withErrors($validator)
                            ->withInput();
            }
    
            $User = new User;
    
            $image_upload = false;    
            if ($request->hasFile('brand_image')) {
    
                if ($request->file('brand_image')->isValid()) {
    
                    $file = $request->file('brand_image');
    
                    $filename = str_replace(" ", "_", $file->getClientOriginalName());
                    $image_name = date('mdYHis').'_'.uniqid().'_'.$filename;
    
                    $destinationPath = 'assets/Company/uploads/';
                    $file->move($destinationPath,$image_name);
    
                    $image_url = $destinationPath.$image_name;
    
                    $image_upload = true;
                }         
                
                $User->User_id  = generate_UserId();
                $User->password ='';
                $User->name 	= $request->brand_name;
                $User->email    = $request->brand_email;
                $User->phone 	= $request->brand_phone;
                $User->role = 'brand';
                $User->date 	= date('Y-m-d');
                $saved = $User->save();


                $Companies = new Companies;    

                $Companies->company_id  = generate_UserId();
                $Companies->company_name = $request->brand_name;
                $Companies->description  = '';
                $Companies->contact_person_name = $request->contact_person;
                $Companies->contact_no	 = $request->brand_phone;
                $Companies->image 	= $image_url;
                $Companies->shipping_slab_type 	= $request->shipping_type;
                $Companies->min_order_value 	=  !empty($request->min_order_value)?$request->min_order_value:'';
                $Companies->shipping_charges 	= $request->shipping_charges;
                $Companies->date 	= date('Y-m-d');
                $Companies->company_address 	= $request->address;

                $saved2 = $Companies->save();

                if($saved && $saved2){
                    set_flashdata('message', 'Brand Saved Successfully', 'success');
                } else {
                    set_flashdata('message', 'Somthing Went Wrong,Please Try Again', 'error');
                }
    
            } else {
                set_flashdata('message', 'There some problem in image uloading please try again', 'error');
            }
    
            return back();
    
            } else {
    
                $data['title'] = 'Add new Brand';
                $data['includes'] = array("datatable","select2");
    
                return view('pages/add_brand',$data);
            }
    
    }
    
}
