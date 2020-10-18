<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\PincodeMaster;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;

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


        public function list(){
            $data['title'] = 'Pincode List';
            $data['includes'] = array("datatable");
    
            return view('pages/pincode_list',$data);
        }

        public function data(Request $request){

            $columns = array(
                0 => 'pincode', 
                1 => 'zone', 
                2 => 'state', 
                3 => 'city', 
                4 => 'address',
                5 => 'status'
            );
    
            $limit = $request->length;
            $start = $request->start;
            
            if( isset($request->order) && !empty($request->order) ){
                $order = $columns[$request->order[0]['column']];
                $dir = $request->order[0]['dir'];
            } else {
                $order = 'date';
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
                
                        $dd1[] = $dd->pincode;
                        $dd1[] = $dd->zone;
                        $dd1[] = $dd->state;
                        $dd1[] = $dd->city;
                        $dd1[] = $dd->address;
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
    
            /* $where_array = $this->where();
            if( !empty($where_array) ) { $this->db->where($where_array); }
            $query = DB::table('products');
            return $query->num_rows(); */
            
            return $count = DB::table('pincode_master')->count();
    
        }
    
        public function all_data($limit, $start, $col, $dir){
            
            $data = DB::table('pincode_master')
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
        
            $data = DB::table('pincode_master')
                ->where([['pincode','LIKE',"%".$search."%"]])
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
            $data = DB::table('pincode_master')
                ->where([['pincode','LIKE',"%".$search."%"]])
                ->offset($start)
                ->limit($limit)
                ->orderBy($col, $dir)
                ->count();        
           
            return $data;
        }
    
        function where(){
            $where_array = array();

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
        $query = PincodeMaster::select('pincode as Pincode','zone as Zone','state as State','city as City','address as address','status as Status')
            ->get()->toArray();
        $header = array_keys($query[0]);
        array_unshift($query, $header);
        array_to_csv($query, 'Pincode_list.csv');
	}
    
}
