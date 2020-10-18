<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class OrderController extends Controller{
    //

    public function list(){
        $data['title'] = 'Pincode List';
        $data['includes'] = array("datatable");

        return view('pages/orders',$data);
    }

    
    public function data(Request $request){

        $columns = array(
            0 => 'order_no', 
            1 => 'user_id', 
            2 => 'user_id', 
            3 => 'order_total_amount', 
            4 => 'discounted_amount',
            5 => 'coupon_id',
            6 => 'order_status',
            7 => 'payment_status',
            8 => 'date',
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
                    
                    if($dd->order_status == 'Pending'){
                        $payment_class = $order_class = 'warning';
                
                    } else if($dd->order_status == 'Failed'){
                        $payment_class = $order_class = 'danger';

                    } else if($dd->order_status == 'Success'){
                        $payment_class = $order_class = 'success';
                        
                    }
                    
                    $dd1[] = $dd->order_no;
                    $dd1[] = $dd->user_id;
                    $dd1[] = $dd->user_id;
                    $dd1[] = $dd->order_total_amount;
                    $dd1[] = $dd->discounted_amount;
                    $dd1[] = $dd->coupon_id;
                    $dd1[] = '<span class="badge-'.$order_class.' badge font-size-12">'.$dd->order_status.'</span>';
                    $dd1[] = '<span class="badge-'.$payment_class.' badge font-size-12">'.$dd->payment_status.'</span>';
                    $dd1[] = '<i class="bx bx-calendar mr-1 text-primary"></i>'.$dd->date;
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
        return $count = DB::table('orders')->count();
    }

    public function all_data($limit, $start, $col, $dir){
        
        $data = DB::table('orders')
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
    
        $data = DB::table('orders')
            ->where([['order_no','LIKE',"%".$search."%"]])
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
        $data = DB::table('orders')
            ->where([['order_no','LIKE',"%".$search."%"]])
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
