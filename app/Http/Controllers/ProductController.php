<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


use Illuminate\Support\Facades\DB;

class ProductController extends Controller {

    /*
      Add New Product
   */
   public function add(Request $request){

    if(request('submit')){

        $validator = Validator::make($request->all(), [
            'product_name' => 'required',
            'product_description' => 'required',
            'category'      => 'required',
            'company'       => 'required',
            'price'         => 'required',
            'product_type'  => 'required',
            'product_overview' =>'required', 
            'product_name' => 'required', 
            'product_image' => 'required|image|mimes:jpg,png,jpeg|max:2048|dimensions:min_width=100,min_height=100',

        ]);

        if ($validator->fails()) {
            set_flashdata('message', 'Please fill all required fields', 'error');
            return redirect('add-product')
                        ->withErrors($validator)
                        ->withInput();
        }

        $Product = new Product;

        $image_upload = false;    
        if ($request->hasFile('product_image')) {

            if ($request->file('product_image')->isValid()) {

                $file = $request->file('product_image');
                //$image = $file.'_'.time().'.'.$file->getClientOriginalExtension();

                $filename = str_replace(" ", "_", $file->getClientOriginalName());
                $image_name = date('mdYHis').'_'.uniqid().'_'.$filename;

                $destinationPath = 'assets/products/uploads/';
                $file->move($destinationPath,$image_name);

                $image_url = $destinationPath.$image_name;

                $image_upload = true;
            }         
        
            $Product->product_code  = random_code(8);
            $Product->product_name 	= $request->product_name;
            $Product->description   = $request->product_description;
            $Product->category_id 	= $request->category;
            $Product->category_name = get_value('category_name', 'categories', $request->category, 'category_id');
            $Product->company_id 	= $request->company;
            $Product->company_name 	= get_value('company_name', 'companies', $request->company, 'company_id');
            $Product->price 	= $request->price;  
            $Product->flags 	= $request->flags;
            $Product->product_type 	= $request->product_type;
            $Product->product_overview 	= !empty($request->product_overview)?$request->product_overview:'';
            $Product->product_image 	= $image_url;
            
            $saved = $Product->save();

            if($saved){
                set_flashdata('message', 'Product Saved Successfully', 'success');
            } else {
                set_flashdata('message', 'Somthing Went Wrong,Please Try Again', 'error');
            }

        } else {
            set_flashdata('message', 'There some problem in image uloading please try again', 'error');
        }

        return back();

        } else {

            $data['title'] = 'Add new product';
            $data['includes'] = array("datatable","select2");

            $companies = DB::table('companies')->select('id','company_id','company_name')->get();
            $categories = DB::table('categories')->select('category_id','category_name')->get();

            $data['companies'] = $companies;
            $data['categories'] = $categories;
            return view('pages/add_product',$data);
        }
    }
}
