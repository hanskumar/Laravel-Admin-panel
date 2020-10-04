<?php
//============= Get txn id ==============
function get_txn_id($code, $uniqecode, $role, $status='', $device_info='', $platform='') {

    $type = DB::table('db_transaction_code')->where('code', $code)->value('type');

	$op = DB::table('db_transaction')->where('txn_id', 'like', $code.'%')->orderBy('id', 'desc')->limit(1)->get();
    if( !empty($op[0]->txn_id) ){
		$txn_id = $code.date("dmy",time()).(substr($op[0]->txn_id, -10)+1);
	} else {
		$txn_id = $code.date("dmy",time()).'1000000001';
	}
	
	insert_txn_id($txn_id, $type, $uniqecode, $role, $status, $device_info, $platform);
	return $txn_id;
}

//============= Insert master txn table ========
function insert_txn_id($txn_id, $type, $uniquecode, $role, $status='', $device_info, $platform) {

	$insert_array = array(
		"uniquecode" => $uniquecode,
		"role" => $role,
		"txn_id" => $txn_id,
		"txn_type" => $type,
		"status" => (empty($status))?'0':$status,
		"date" => date("Y-m-d"),
		"time" => date("H:i:s"),
		"platform" => (empty($platform))?'web':$platform,
		"device_info" => (empty($device_info))?get_session('agent'):$device_info,
	);
	DB::table('db_transaction')->insert($insert_array);
	return true;
}

//======================== User Login Log ====================
function user_activity($class, $method, $url, $referer='', $text='', $txn='') {
	$output = false;
	if( get_session('username') != '' ){
		$array = array(
			"username" => get_session('username'),
			"uniquecode" => get_session('uniquecode'),
			"name" => get_session('name'),
			"role" => get_session('role'),
			"controller" => $class,
			"method" => $method,
			"url" => $url,
			"referer" => $referer,
			"text" => $text,
			"date" => date("Y-m-d"),
			"time" => date("H:i:s"),
			"ip" => $_SERVER["REMOTE_ADDR"],
			"browser" => $_SERVER["HTTP_USER_AGENT"],
			"token" => get_session('token'),
			"txn" => $txn
		);
		DB::table('user_activity_log')->insert($array);
		return true;
	}
	return true;
}

//============= Insert log data ========
function insert_logs($table, $id, $old_data, $new_data, $device='', $name='', $uniqecode='', $role='') {
	$wsd = json_encode(array('agent' => get_session('agent'), 'uniquecode' => get_session('uniquecode'), 'role' => get_session('role')));

	$insert_array = array(
		'table' => $table,
		'table_id' => $id,
		'old_json' => json_encode($old_data),
		'new_json' => json_encode($new_data),
		'name' => (empty($name))?get_session('name'):$name,
		'uniquecode' => (empty($uniqecode))?get_session('uniquecode'):$uniqecode ,
		'role' =>(empty($role))?get_session('role'):$role,
		'date' => date('Y-m-d'),
		'time' => date('H:i:s'),
		'session' => (empty($device))?$wsd:$device
	);
	DB::table('logs')->insert($insert_array);
	return true;
}

//======== generate  uniquecode =========
function generate_uniquecode($role){

	$op = DB::table('users')->select('uniquecode')->where('uniquecode', 'like', $role.'%')->orderBy('id', 'DESC')->first();
	if( !is_null($op) ){
		$code = explode('_', $op->uniquecode);
		$c = (int)$code[1]+1;
		return $code[0].'_'.$c;
	} else {
		return $role.'_101';
	}
}

function validateDate($date, $format = 'Y-m-d'){
	$d = DateTime::createFromFormat($format, $date);
	return $d && $d->format($format) === $date;
}

//======================== Cron Job history ====================
function cron_job_history($name, $url, $rows, $desc, $file_name='', $time='') {
	
	$array = array(
		"name" => $name,
		"url" => $url,
		"rows_affected" => $rows,
		"time_taken" => $time,
		"file_name" => $file_name,
		"description" => $desc,
		"date" => date('Y-m-d'),
		"time" => date('H:i:s'),
	);
	DB::table('cron_job_history')->insert($array);
	return true;
}

//======================================== Menu active function only ============================
function menu($name){
	
	if( in_array($name, get_session('menu')) ){
		return '';
	} else {
		return 'hide';
	}
}

function submenu($name){
	
	if( in_array($name, get_session('submenu')) ){
		return '';
	} else {
		return 'hide';
	}
}

function submenu2($name){
	
	if( in_array($name, get_session('submenu2')) ){
		return '';
	} else {
		return 'hide';
	}
}

//================ State ==============
function filter_state($zone='', $state=''){
	$output = false;
	
	$CI->db->distinct();
	$CI->db->select('statename');
	$CI->db->from('citylist');
	if( !empty($zone) ){
		if(is_array($zone) && count($zone) > 0) {
			$CI->db->where_in('region', explode(',', $zone));
		} else {
			$CI->db->where('region', $zone);
		}
	}
	$query = $CI->db->get();
	if( $query->num_rows() > 0  ) { 
		$result = $query->result_array();
		foreach($result as $re) {
			$select = '';
			if( !empty($state) && ($re['statename'] == $state) ){
				$select = 'selected="selected"';
			}
			echo '<option value="'.$re['statename'].'"'.$select.' >'.$re['statename'].'</option>';
		}
	}
	return false;
}

//================ City ==============
function filter_city($zone='', $state='', $city=''){
	$output = false;
	
	$CI->db->distinct();
	$CI->db->select('cityname');
	$CI->db->from('citylist');
	if( !empty($zone) ){
		if(is_array($zone) && count($zone) > 0) {
			$CI->db->where_in('region', $zone);
		} else {
			$CI->db->where('region', $zone);
		}
	}
	if( !empty($state) ){
		if(is_array($state) && count($state) > 0) {
			$CI->db->where_in('statename', explode(',', $state));
		} else {
			$CI->db->where('statename', $state);
		}
	}
	$query = $CI->db->get();
	if( $query->num_rows() > 0  ) { 
		$result = $query->result_array();
		foreach($result as $re) {
			$select = '';
			if( !empty($city) && ($city == $re['cityname']) ){
				$select = 'selected';
			}
			echo '<option value="'.$re['cityname'].'"'.$select.' >'.$re['cityname'].'</option>';
		}
	}
	return false;
}

//================ Retailer ==============
function filter_retailer($zone='', $state='', $city='', $retailer=''){
	$output = false;
	
	$CI->db->select('name as retailer_name,uniquecode as retailer_code,mobile,email');
	$CI->db->from('user');
	$CI->db->where('status', 'Approved');
	$CI->db->where('role', 'retailer');
	$CI->db->where_not_in('trade_type', array('B2B', 'activity'));
	if( !empty($zone) ){
		if(is_array($zone) && count($zone) > 0) {
			$CI->db->where_in('zone', $zone);
		} else {
			$CI->db->where('zone', $zone);
		}
	}
	if( !empty($state) ){
		if(is_array($state) && count($state) > 0) {
			$CI->db->where_in('state', $state);
		} else {
			$CI->db->where('state', $state);
		}
	}
	if( !empty($city) ){
		if(is_array($city) && count($city) > 0) {
			$CI->db->where_in('city', $city);
		} else {
			$CI->db->where('city', $city);
		}
	}
	$query = $CI->db->get();
	if( $query->num_rows() > 0  ) { 
		$result = $query->result_array();
		foreach($result as $re) {
			$select = '';
			if( !empty($retailer) && ($retailer == $re['retailer_code']) ){
				$select = 'selected';
			}
			echo '<option value="'.$re['retailer_code'].'"'.$select.' >'.$re['retailer_code'].' | '.$re['retailer_name'].'</option>';
		}
	}
	return false;
}

//======================= Exception entry =========================
function exception($code='', $api='', $input='', $msg='', $role='', $device='') {
	$insert_array = array(
		"api" => $api,
		"json_input" => $input,
		"error_msg" => $msg,
		"uniquecode" => $code,
		"role" => $role,
		"date" => date("Y-m-d"),
		"time" => date("H:i:s"),
		"device_info" => $device,
		"ip" => $_SERVER['REMOTE_ADDR'],
	);
	DB::table('exception_log')->insert($insert_array);
	return true;
}

//=============== Query Exception =====================
function query_exception($code='', $api='', $query='', $input='', $msg='', $role='', $device='', $master_txn='', $sql_query='') {
	$insert_array = array(
		"uniquecode" => $code,
		"role" => $role,
		"api" => $api,
		"query" => json_encode($query),
		"sql_query" => $sql_query,
		"json_input" => $input,
		"message" => $msg,
		"date" => date("Y-m-d"),
		"time" => date("H:i:s"),
		"device_info" => $device,
		"status" => "Pending",
		"group_txn" => $master_txn,
		"ip" => $_SERVER['REMOTE_ADDR'],
	);
	DB::table('query_log')->insert($insert_array);
	// $email_array = array('sahil@triadweb.in');
	//sendmail('Query Exception', 'Query Exception occured in api '.$api.' kindly check.', $email_array);
	return true;
}

function send_notification($registrationIds,$role,$message='',$title,$type='') {

	$RET_ACCESS_KEY = 'AAAA7kSNSdk:APA91bFIpGZ4N1m8ZkRZo8aaDtuBF1Kr_KsrSwMb0F3kLWzd8d-jrGZWC8cWXgnReekB05Lo31ME4-Xw9YuMVzCY6YnLwQe24giD5DDuS7D72R1jDBxYK-CzgYmrafAtIzfTOCNecnJS';
	$RM_ACCESS_KEY = 'AAAA5w8GjIY:APA91bEkQSNaEfmuZMkl7D_Kxb9sYQloDXNf4VeRcFDZbTfiXiSsz03v9qInNKUGnigjv_6Np75A-3LYMPmQsL7FGCbLLLvDvTg_hMYe01yWrjmJ-5Z2FofXeWB2BRWXvlPPXuPchCY3';
	$BM_ACCESS_KEY = 'AAAAeScdf-s:APA91bHq3mQ8rJaQXYaA8JVSGMVqSkrZ2SbqSwZkBDr87ULUIs607yoWubF0OnhQk99Uxf9Ffzop5_UOyl8goJ-Rq2ZE3EYX97iS9G-dKnmexsDbK1ZKjxshIdDENDerjNLImXP8isav';

	if( $role == 'rm' ){
		$API_ACCESS_KEY = $RM_ACCESS_KEY;
	} else if( $role == 'bm' ){
		$API_ACCESS_KEY = $BM_ACCESS_KEY;
	} else {
		$API_ACCESS_KEY = $RET_ACCESS_KEY;
	}

	$msg = array(
		'message'   => $message,
		'title'     => $title
	);
	
	$msg1 = array(
		"body" => $message,
		"message" => $message,
		"title" => $title,
		"sound" => 1,
		"vibrate" => 1,
		"badge" => 1,
		"mutable-content" => 1,
	);
	
	$fields = array(
		'registration_ids'  => $registrationIds,
		// 'notification' => $msg1,
		'priority' => 'high',
		'data' => $msg
	);
		
	$headers = array(
		'Authorization: key=' . $API_ACCESS_KEY,
		'Content-Type: application/json'
	);
		
	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	$result = curl_exec($ch );
	curl_close( $ch );
}

//============= Upload file log ========
function upload_file_log($file_name, $page_name, $num_records) {

	$insert_array = array(
		'file_name' => $file_name,
		'page_name' => $page_name,
		'date' => today(),
		'date' => date('Y-m-d H:i:s'),
		'num_records' => $num_records,
		'uploader_name' => session('name'),
		'uploader_uniquecode' => session('uniquecode'),
		'uploader_role' => session('role'),
		'remarks' => '',
		'session' => request()->server('HTTP_USER_AGENT')
	);
	DB::table('files_uploaded')->insert($insert_array);
	return true;
}

//================ GET DATABASE TABLE SIZE =================
function table_size($database, $table_name) {

	$sql = 'SELECT TABLE_NAME AS `Table`,ROUND((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024) AS `Size (MB)` FROM information_schema.TABLES WHERE TABLE_SCHEMA = "'.$database.'" AND TABLE_NAME = "'.$table_name.'" ';
	$query = $CI->db->query($sql);
	if( $query->num_rows() > 0 ){
		$result = $query->row_array();
		return $result['Size (MB)'];
	}
	return false;
}