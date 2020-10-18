<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Categories;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;

class CategoryController extends Controller {

    public function index(){

        $data['title'] = 'Category List';
        $data['includes'] = array("datatable");
        return view('pages/category_list',$data);
    }

    /*
   AJAX request
   */
    public function getCategries(Request $request){
            $columns = array(
                0 => 'category_id', 
                1 => 'category_name', 
                2 => 'category_image', 
                3 => 'created_at', 
                4 => 'categroy_status',
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
                
                        $dd1[] = $dd->category_id;
                        $dd1[] = $dd->category_name;
                        $dd1[] = $dd->category_image;
                        $dd1[] = $dd->created_at;
                        $dd1[] = $dd->categroy_status;
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
            return $count = DB::table('categories')->count();
        }
    
        public function all_data($limit, $start, $col, $dir){
            
            $data = DB::table('categories')
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
        
            $data = DB::table('categories')
                ->where([['category_name','LIKE',"%".$search."%"]])
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
            $data = DB::table('categories')
                ->where([['category_name','LIKE',"%".$search."%"]])
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
   

   /*
    Add New Category
   */
   public function add(Request $request){

        if(request('submit')){

            $validator = Validator::make($request->all(), [
                'category_name' => 'required',
            ]);
    
            if ($validator->fails()) {
                set_flashdata('message', 'Please fill all required fields', 'error');
                return redirect('add-category')
                            ->withErrors($validator)
                            ->withInput();
            }

            $Categories = new Categories;

            $Categories->category_name 	= $request->category_name;
            $Categories->category_image = '';
            $Categories->category_description 	= !empty($request->category_description)?$request->category_description:'';

            $saved = $Categories->save();

            if($saved){
                set_flashdata('message', 'Saved Successfully', 'success');
            } else {
                set_flashdata('message', 'Somthing Went Wrong,Please Try Again', 'error');
            }

            return back();

        } else {

            $data['title'] = 'Add new Category';
            $data['includes'] = array("datatable");

            return view('pages/add_category',$data);
        }
    }

}
