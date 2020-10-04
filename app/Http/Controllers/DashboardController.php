<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller {
    public function index(){

        $data['title'] = 'Dashboard';
        $data['includes'] = array("dashboard");
        
        //return view('pages.dashboard', $data);

        return view('pages/dashboard',$data);
    }
}
