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

    public function list(){

        $data['title'] = 'Brand List';
        $data['includes'] = array("datatable");

        return view('pages/brand_list',$data);
    }

    public function data(Request $request){

		$columns = array(
			0 => 'user_id', 
			1 => 'name', 
			2 => 'email', 
			3 => 'phone', 
			4 => 'profile_image',
			5 => 'oauth_provider', 
			6 => 'location', 
            7 => 'created_at',
            8 => 'status'
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
                    
                    $icon =  DB::table('companies')->select('id','company_id','icon')->where('company_id',$dd->user_id)->first();

					$dd1[] = $dd->user_id;
					$dd1[] = $dd->name;
					$dd1[] = $dd->email;
                    $dd1[] = $dd->phone;
                    $dd1[] = '<img src="'.$icon->icon.'" style="height:60px;width: 60px;">';
                    $dd1[] = $dd->oauth_provider;
                    $dd1[] = $dd->location;
                    $dd1[] = $dd->created_at;
                    $dd1[] = $dd->status;
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
        return $count = DB::table('users')->where('role','brand')->count();
    }

    public function all_data($limit, $start, $col, $dir){
        
        $data = DB::table('users')
                    ->where('role','brand')    
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($col, $dir)
                    ->get();
        if($data){
            return $data;
        } else {
            return array();
        }
    }
    
    function data_search($limit, $start, $search, $col, $dir){
	
        $data = DB::table('users')
            ->where([['name','LIKE',"%".$search."%"]])
            ->where('role','brand')
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
		$data = DB::table('users')
            ->where([['name','LIKE',"%".$search."%"]])
            ->where('role','brand')
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
		return $where_array;
    }
    
    public function download(){

        $query = User::select('name as Name','email as Email','phone as Phone','location as location','created_at as Reg at','status as Status')
             ->where('role','user')
            ->get()->toArray();
        $header = array_keys($query[0]);
        array_unshift($query, $header);
        array_to_csv($query, 'Users_list.csv');
	}
    
}
