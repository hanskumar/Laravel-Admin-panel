<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;

class UserController extends Controller {

    public function list(){

        $data['title'] = 'Users List';
        $data['includes'] = array("datatable");

        return view('pages/user_list',$data);

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
			
					$dd1[] = $dd->user_id;
					$dd1[] = $dd->name;
					$dd1[] = $dd->email;
                    $dd1[] = $dd->phone;
                    $dd1[] = '<img src="'.$dd->profile_image.'" style="height:60px;width: 60px;">';
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
        return $count = DB::table('users')->where('role','user')->count();
    }

    public function all_data($limit, $start, $col, $dir){
        
        $data = DB::table('users')
                    ->where('role','user')    
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
            ->where('role','user')
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
            ->where('role','user')
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
