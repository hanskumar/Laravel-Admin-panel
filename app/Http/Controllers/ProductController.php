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

    public function list(){

        $data['title'] = 'Product List';
        $data['includes'] = array("datatable");

        $companies = DB::table('companies')->select('id','company_id','company_name')->get();
        $categories = DB::table('categories')->select('category_id','category_name')->get();

        $data['companies'] = $companies;
        $data['categories'] = $categories;

        return view('pages/product_list',$data);

    }

    public function data(Request $request){

		$columns = array(
			0 => 'product_name', 
			1 => 'product_image', 
			2 => 'price', 
			3 => 'flags', 
			4 => 'product_type',
			5 => 'category_name', 
			6 => 'company_name', 
			7 => 'product_status'
		);

		$limit = $request->length;
		$start = $request->start;
		
		if( isset($request->order) && !empty($request->order) ){
		    $order = $columns[$request->order[0]['column']];
		    $dir = $request->order[0]['dir'];
		} else {
		    $order = 'created_at';
		    $dir = 'asc';
		}

			$totalData = $this->all_data_count();

			$totalFiltered = $totalData; 
				
			if( empty($request->serach['value']) ) {
				$data = $this->all_data($limit, $start, $order, $dir);
			} else {
				$search = $request->serach['value']; 
				$data =  $this->data_search($limit, $start, $search, $order,$dir);
				$totalFiltered = $this->data_search_count($search);
			}

			$data_array = array();
			if( !empty($data) ) {
				foreach ($data as $dd) {
					$dd1 = array();
			
					$dd1[] = $dd->product_name;
					$dd1[] = '<img src="'.$dd->product_image.'" style="height:60px;width: 60px;">';
                    $dd1[] = '<i style="font-size: 1rem;" class="la la-inr"></i> '.$dd->price;
					$dd1[] = $dd->flags;
					$dd1[] = $dd->product_type;
                    $dd1[] = $dd->category_name;
                    $dd1[] = $dd->company_name;
                    $dd1[] = $dd->product_status;
					$data_array[] = $dd1;
				}
			}

		$json_data = array(
			"draw" => intval($request->draw),
			"recordsTotal" => intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data" => $data_array
		);
		echo json_encode($json_data);
    }

    public function all_data_count(){

        /* $where_array = $this->where();
		if( !empty($where_array) ) { $this->db->where($where_array); }
		$query = DB::table('products');
        return $query->num_rows(); */
        
        return $count = DB::table('products')->count();

    }

    public function all_data($limit, $start, $col, $dir){
        
        $data = DB::table('products')
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($col, $dir)
                    ->get();
        //dd(DB::getQueryLog());

        if($data){
            return $data;
        } else {
            return array();
        }
    }
    
    function data_search($limit, $start, $search, $col, $dir){
	
        $data = DB::table('products')
            ->where([['product_name','LIKE',"%".$search."%"]])
            ->offset($start)
            ->limit($limit)
            ->orderBy($col, $dir)
            ->get();        
	   
		if( $data) {
			return $data;  
		} else {
			return array();
		}
	}

	function data_search_count($search) {
		$data = DB::table('products')
            ->where([['product_name','LIKE',"%".$search."%"]])
            ->offset($start)
            ->limit($limit)
            ->orderBy($col, $dir)
            ->count();        
	   
		return $data;
	}

	function where(){
		$where_array = array();

		if( isset($_POST['start_date']) && !empty($_POST['start_date']) ){
			$where_array['date>='] = date('Y-m-d', strtotime($_POST['start_date']));
		}
		if( isset($_POST['end_date']) && !empty($_POST['end_date']) ){
			$where_array['date<='] = date('Y-m-d', strtotime($_POST['end_date']));
		}
		if(isset($_POST['zone']) && !empty($_POST['zone'])) {
			$this->db->where_in('zone', explode(',', $_POST['zone']));
		}
		if(isset($_POST['state']) && !empty($_POST['state'])) {
			$this->db->where_in('state', explode(',', $_POST['state']));
		}
		if(isset($_POST['city']) && !empty($_POST['city'])) {
			$this->db->where_in('city', explode(',', $_POST['city']));
		}
		if(isset($_POST['retailer']) && !empty($_POST['retailer'])) {
			$this->db->where_in('retailer_code', explode(',', $_POST['retailer']));
		}
		if(isset($_POST['product_category']) && !empty($_POST['product_category'])) {
			$this->db->where_in('category', explode(',', $_POST['product_category']));
		}
		if( get_session('role') == 'am' || get_session('role') == 'dam' ){
		    $this->db->where(get_session('role').'_code', get_session('uniquecode'));
		}
		
		if(isset($_POST['channel_type']) && !empty($_POST['channel_type'])) {
			$this->db->where_in('channel', explode(',', $_POST['channel_type']));
		}
		
		if(isset($_POST['channel_type_sub']) && !empty($_POST['channel_type_sub'])) {
			$this->db->where_in('channel_sub', explode(',', $_POST['channel_type_sub']));
		}
		
		/*if(isset($_POST['cpu_group_code']) && !empty($_POST['cpu_group_code'])) {
			$this->db->where_in('cpu_group_code', explode(',', $_POST['cpu_group_code']));
		}*/

		return $where_array;
    }
    
    public function download(){

        $query = Product::select('product_name as Product Name','category_name as Category Name','company_name as Company Name','price as price','flags as Flags','product_type as Product Type','product_overview as Product Overview','product_status as Status')
            ->get()->toArray();
        $header = array_keys($query[0]);
        array_unshift($query, $header);
        array_to_csv($query, 'Products_list.csv');
	}
}
