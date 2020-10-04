<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Product;
use Illuminate\Support\Facades\Validator;

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
            'product_overview' =>'required'
        ]);

        if ($validator->fails()) {
            set_flashdata('message', 'Please fill all required fields', 'error');
            return redirect('add-product')
                        ->withErrors($validator)
                        ->withInput();
        }

        $Product = new Product;

        $Product->product_name 	= $request->product_name;
        $Product->description   = $request->description;

        $Product->category_id 	= $request->category;
        $Product->category_name = '';

        $Product->company_id 	= $request->company;
        $Product->company_name 	= '';


        $Product->price 	= $request->price;  
        $Product->flags 	= $request->flags;

        $Product->product_type 	= $request->product_type;
        
        $Product->product_overview 	= !empty($request->product_overview)?$request->product_overview:'';

        $saved = $Product->save();

        if($saved){
            set_flashdata('message', 'Saved Successfully', 'success');
        } else {
            set_flashdata('message', 'Somthing Went Wrong,Please Try Again', 'error');
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
