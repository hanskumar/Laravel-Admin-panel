<?php
//============== Set json header ===============
function set_json_header(){
	@header("content-type: application/json");
}

//========= Empty check ==========
function blank_check($var, $name='') {
	if( !empty($name) ){
		if( empty($var) && $var!='0' ) {
			set_json_header();
			echo json_encode(array('status'=>'0', 'data'=>'', 'message'=>"Check Your Input (".$name.")"));
			exit;
		}  else {
			return $var;
		}
	} else {
		if( empty($var) ) {
			set_json_header();
			echo json_encode(array('status'=>'0', 'data'=>'', 'message'=>"Check Your Input."));
			exit;
		} else {
			return $var;
		}
	}
}

//======== Authentication Function =========
function authenticate($uniquecode, $token) {
	set_json_header();

	if( empty($uniquecode) ) {
		echo json_encode(array('status'=>'0', 'data'=>'', 'message'=>'Check Your Input.'));
		exit;
	}
	$op = DB::table('users')->where('uniquecode', $uniquecode)->where('token', $token)->first();
	if( !is_null($op) ){
		return $op;
	} else {
		$data = array('status'=>'-1', 'data'=>'', 'message'=>'Authentication Failed!');
		echo json_encode($data);
		exit;
	}
}

//======== Real escape string ============
function escape($text) {
	$db = get_instance()->db->conn_id;
	$text = mysqli_real_escape_string($db, $text);
	return $text;
}

//======== Keep json req and res ============
function keep_req_res($api = '', $json_input = '', $json_output = '', $uniquecode = '', $role = '', $device = '', $table_name = '', $table_id = '') {
	$CI =& get_instance();
	$insert_array = array(
		"api" => $api,
		"table_id" => $table_id,
		"table_name" => $table_name,
		"json_input" => $json_input,
		"json_output" => $json_output,
		"uniquecode" => $uniquecode,
		"role" => $role,
		"date" => date("Y-m-d"),
		"time" => date("H:i:s"),
		"device_info" => $device,
		"ip" => $_SERVER['REMOTE_ADDR']
	);
	$query_exception = $CI->db->insert('api_req_res', $insert_array);
	return true;
}

//======== Get user data ============
function get_data($uniquecode, $role = '') {
	$op = DB::table('users')->where('uniquecode', $uniquecode)->where('status', 'Approved')->first();
	return $op;
}

//======== Distance calculator ============
function distance($lat1, $lon1, $lat2, $lon2, $unit) {

	$theta = $lon1 - $lon2;
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	$dist = acos($dist);
	$dist = rad2deg($dist);
	$miles = $dist * 60 * 1.1515;
	$unit = strtoupper($unit);

	if ($unit == "K") {
		return ($miles * 1.609344);
	} else if ($unit == "N") {
		return ($miles * 0.8684);
	} else {
		return $miles;
	}
}

//======== change rupee format ============
function rupee_format($amount) {
	setlocale(LC_MONETARY, 'en_IN');
	if (ctype_digit($amount) ) {
		$amount = money_format('%!.0n', $amount);
	}
	else {
		$amount = money_format('%!i', $amount);
	}
	return $amount;
}

//======== ASIN check ============
function asin_active( $asin ) {
	$CI =& get_instance();
	$output = false;
	$CI->db->select('*');
	$CI->db->from('product_model');
	$CI->db->where('asin', $asin);
	$CI->db->where('status', 'Active');
	$query = $CI->db->get();
	if( $query->num_rows() > 0 ) {
		return true;
	} else {
		set_json_header();
		echo json_encode(array('status'=>'0', 'data'=>'', 'message'=>'Selected asin is not active.'));	
		exit();
	}
}

?>