<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class FiltersController extends Controller{

    public function get_state(Request $request){

        $zone = $request->zone;

        $states = DB::table('citylist')
                ->select('statename')
                ->when($zone, function ($query) use ($zone) {
                    return $query->where("region",$zone);
                })
                ->groupBy('statename')
                ->get();

        return response()->json($states);

    }

    public function get_city(Request $request){

        //$zone = $request->zone;
        $state = $request->state;
        
        $city = DB::table('citylist')
                ->select('cityname')
                /* ->when($zone, function ($query) use ($zone) {
                    return $query->where("region",$zone);
                }) */
                ->when($state, function ($query) use ($state) {
                    return $query->where("statename",$state);
                })
                ->groupBy('cityname')
                ->get();
        return response()->json($city);        
    }


}
