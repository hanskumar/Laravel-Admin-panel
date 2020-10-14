<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Notifications;
use Illuminate\Support\Facades\DB;
//use App\Models\User;

class NotificationController extends Controller{
    
    public function add(Request $request){

        if(request('submit')){

            $validator = Validator::make($request->all(), [
                'noti_type' => 'required',
                'title' => 'required',
                'message' => 'required',
            ]);
    
            if ($validator->fails()) {
                set_flashdata('message', 'Please fill all required fields', 'error');
                return redirect('add-category')
                            ->withErrors($validator)
                            ->withInput();
            }

            $Notifications = new Notifications;   

            $Notifications->date 	= date('Y-m-d');
            $Notifications->time = '';
            $Notifications->title 	= !empty($request->category_description)?$request->category_description:'';
            $Notifications->message 	= !empty($request->category_description)?$request->category_description:'';
            $saved = $Notifications->save();

            if($saved){
                set_flashdata('message', 'Saved Successfully', 'success');
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

}
