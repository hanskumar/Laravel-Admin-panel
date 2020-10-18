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

	$USER_ACCESS_KEY = 'AAAASSB688c:APA91bH4MLOgGWXiSfWT2ItnLCBFLwEvZ7xhHWtz7wTY0ibN8wpYvsWekM3l_mcWwlLJfgLfCseWrFVXlA-dvl4EiKqPvgCIOWaK9MFEcrQM5PJDm8kK1iISjhZ3Q57MGLmq9QuaWrPE';
	
	if( $role == 'user' ){
		$API_ACCESS_KEY = $USER_ACCESS_KEY;
	} else if( $role == 'brand' ){
		$API_ACCESS_KEY = $BRAND_ACCESS_KEY;
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


function generate_UserId(){

	$query = DB::table('users')
			->select('user_id')
			->orderBy('id','desc')
            ->limit(1)
			->get();
    if($query ){
		$user_id = date("dmy",time()).(substr($query[0]->user_id, -5)+1);
		return $user_id;
	} else {
		$user_id = date("dmy",time()).'1001';
		return $user_id;
	}

}