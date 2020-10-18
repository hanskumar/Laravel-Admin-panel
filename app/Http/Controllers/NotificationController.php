<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Notifications;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class NotificationController extends Controller{
    
    public function add(Request $request){

        if(request('submit')){

            $validator = Validator::make($request->all(), [
                'app' => 'required',
                'noti_type' => 'required',
                'title' => 'required',
                'message' => 'required',
                //'Selected_user' => 'required',
            ]);
    
            if ($validator->fails()) {
                set_flashdata('message', 'Please fill all required fields', 'error');
                return redirect('add-category')
                            ->withErrors($validator)
                            ->withInput();
            }

            //$registrationIds = DB::table('users')->select('id','device_token')->where('user_id',$request->user_id)->get();

            //$noti_send= send_notification($registrationIds['device_token'],'user',$request->message,$request->title);

            $Notifications = new Notifications;   

            $Notifications->user_id = '';
            $Notifications->role    = $request->app;
            $Notifications->date 	= date('Y-m-d');
            $Notifications->time    = date("h:i:sa");
            $Notifications->title 	= !empty($request->title)?$request->title:'';
            $Notifications->message = !empty($request->message)?$request->message:'';
            $saved = $Notifications->save();

            if($saved){
                set_flashdata('message', 'Notification Send Successfully', 'success');
            } else {
                set_flashdata('message', 'Somthing Went Wrong,Please Try Again', 'error');
            }

            return back();

        } else {

            $data['title'] = 'Send a Notification';
            $data['includes'] = array("datatable");

            $User = DB::table('users')->select('user_id','name','phone')->where('role','user')->get();

            $data['users'] = $User;

            return view('pages/send_notification',$data);
        }
    }
    
    public function list(){

        $data['title'] = 'Notification List';
        $data['includes'] = array("datatable");

        return view('pages/noti_list',$data);
    }

    public function data(Request $request){

        $columns = array(
            0 => 'title', 
            1 => 'message', 
            2 => 'role', 
            3 => 'date', 
            4 => 'time',
            5 => 'view_status'
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
            
                    $dd1[] = $dd->title;
                    $dd1[] = $dd->message;
                    $dd1[] = $dd->role;
                    $dd1[] = $dd->date;
                    $dd1[] = $dd->time;
                    $dd1[] = $dd->view_status;
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
        return $count = DB::table('notifications')->count();
    }

    public function all_data($limit, $start, $col, $dir){
        
        $data = DB::table('notifications')
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
    
        $data = DB::table('notifications')
            ->where([['title','LIKE',"%".$search."%"]])
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
        $data = DB::table('notifications')
            ->where([['title','LIKE',"%".$search."%"]])
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

}
