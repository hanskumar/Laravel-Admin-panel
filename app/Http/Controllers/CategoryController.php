<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Categories;

use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller {

    public function index(){

        $data['title'] = 'Category List';
        $data['includes'] = array("datatable");
        
        /* $offers = Offers_master::all();
        $data['offers'] = $offers;
        return view('pages.offers_list', $data); */

        return view('pages/category_list',$data);
    }

    /*
   AJAX request
   */
   public function getCategries(Request $request){

        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = Categories::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Categories::select('count(*) as allcount')->where('category_name', 'like', '%' .$searchValue . '%')->count();

       // Fetch records
       $records = Categories::orderBy($columnName,$columnSortOrder)
       //->where('category_name', 'like', '%' .$searchValue . '%')
       ->select('*')
       //->skip($start)
       //->take($rowperpage)
       ->get();

       $data_arr = array();

       foreach($records as $record){
            $category_id = $record->category_id;
            $category_name = $record->category_name;
            $category_image = $record->category_image;
            $category_status = $record->category_status;

            $data_arr[] = array(
                "category_id" => $category_id,
                "category_name" => $category_name,
                "category_image" => $category_image,
                "category_status" => $category_status
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        //dd($response);

        echo json_encode($response);
        exit;

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
