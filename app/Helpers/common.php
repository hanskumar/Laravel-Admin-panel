<?php
if (!function_exists('set_flashdata')) {
	function set_flashdata($name, $message, $class='') {
		//$data = 'toastr.'.$class.'("'.$message.' !'.'", "'.ucfirst($class).'", {timeOut: 6000,showMethod:"slideDown",hideMethod:"slideUp"});';
		$data ='toastr["'.$class.'"]("'.$message.'", { positionClass: "top-right"});';
		session()->flash($name, $data);
	}
}

function get_flashdata($name) {
	return session($name);
}

//================ Session related functions =======================
function user_logged_in( $redirect = '' ) {
	if ( get_session('logged_in') == 1 ) {
		if( !empty($redirect) ) redirect($redirect);
		return true;
	} else {
		return false;
	}
}


function set_sessions($values) {
	session($values);
}

function get_session($name='') {
	if( !empty($name) ) {
		return session($name);
	}
	return session()->all();
}

function unset_session($name) {
	session()->forget($name);
}

function set_login_sessions($user) {

	$token = bin2hex(openssl_random_pseudo_bytes(18));
	//set_value('token', 'users', $token, $user['mobile'], 'mobile');

	$data = array(
		'logged_in' => 1,
		'role' => $user['role'],
		'name' => $user['name'],
		'email' => $user['email'],
		//'uniquecode' => $user['uniquecode'],
		'mobile' => $user['phone'],
		'image' => $user['image'],
		'agent' => $_SERVER["REMOTE_ADDR"].'-'.$_SERVER["HTTP_USER_AGENT"],
		'token' => $token,
	);
	set_sessions($data);

	//Session::push('user_session', $data);
}

function unset_login_sessions() {
	$data = array(
		'logged_in',
		'role',
		'role_type',
		'name',
		'email',
		'uniquecode',
		'mobile',
		'image',
		'zone',
		'state',
		'city',
		'user_data',
		'access',
		'mobile_verified',
		'email_verified',
		'ip',
		'token',
	);
	foreach( $data as $value ) {
		unset_session($value);
	}
}

//======================= Common database functions ========================
function get_value($field, $table, $value, $where='id') {
	
	$op = DB::table($table)
		->where($where, $value)
		->value($field);
	if( !is_null($op) ){
		return $op;
	}
	return false;
}

function get_value_column_sum($field, $table, $value, $where='id') {
	
	$op = DB::table($table)
		->where($where, $value)
		->sum($field);
	if( !is_null($op) ){
		return $op;
	}
	return '0';
}

function set_value($field, $table, $value, $where_value, $where_cond = 'id') {
	
	$op = DB::table($table)
		->where($where_cond, $where_value)
		->update([$field => $value]);
	return $op;
}

function get_row($table, $value, $where='id', $value1='', $where1='') {

	if( empty($where1) ){
		$op = DB::table($table)->where($where, $value)->first();
	} else {
		$op = DB::table($table)->where($where, $value)->where($where1, $value1)->first();
	}
	if( !is_null($op) ){
		return $op;
	}
	return false;
}

function get_table($table, $value ='', $where ='', $value1='', $where1='') {
	
	if( empty($where1) ){
		$op = DB::table($table)->where($where, $value)->get();
	} else {
		$op = DB::table($table)->where($where, $value)->where($where1, $value1)->get();
	}
	if( !is_null($op) ){
		return $op;
	}
	return false;
}

function get_count($table, $value='', $where='', $value1='', $where1='') {
	
	if( empty($where1) ){
		$op = DB::table($table)->where($where, $value)->count();
	} else {
		$op = DB::table($table)->where($where, $value)->where($where1, $value1)->count();
	}
	if( !is_null($op) ){
		return $op;
	}
	return '0';
}

//============== Other common functions ============
function compare_datetime($a, $b) {
	$ad = strtotime($a['exact_date']);
	$bd = strtotime($b['exact_date']);

	if ($ad == $bd) {
		return 0;
	}

	return $ad > $bd ? 1 : -1;
}

function get_extention($file) {
	return pathinfo($file['name'], PATHINFO_EXTENSION);
}

function i_encode($url) {
	$uri = $CI->encryption->encrypt($url);
	$pattern = '"/"';
	$new_uri = preg_replace($pattern, '_', $uri);
	return $new_uri;
}

function i_decode($url) {
	$pattern = '"_"';
	$uri = preg_replace($pattern, '/', $url);
	$new_uri = $CI->encryption->decrypt($uri);
	return $new_uri;
}

function custom_encode($string) {
	$key = "rEdI";
	$string = base64_encode($string);
	$string = str_replace('=', '', $string);
	$main_arr = str_split($string);
	$output = array();
	$count = 0;
	for( $i=0; $i<strlen($string); $i++) {
		$output[] = $main_arr[$i];
		if($i%2==1) {
			$output[] = substr($key, $count, 1);
			$count++;
		}
	}
	$string = implode('', $output);
	return $string;
}

function custom_decode($string) {
	$key = "rEdI";
	$arr = str_split($string);
	$count = 0;
	for( $i=0; $i<strlen($string); $i++) {
		if( $count < strlen($key) ) {
			if($i%3==2) {
				unset($arr[$i]);
				$count++;
			}
		}
	}
	$string = implode('', $arr);
	$string = base64_decode($string);
	return $string;
}

function get_array_key($value, $array) {
	while ($single = current($array)) {
		if ($single == $value) {
			return key($array);
		}
		next($array);
	}
}

//================ include scripts and css ================
function inclusions( $values = array() ) {
	$options = array(
		'datatable' => array(
							array(
								'type' => 'js',
								'value' => 'assets/js/pages/datatables.init.js'
							), 
							array(
								'type' => 'js',
								'value' => 'assets/libs/datatables.net/js/jquery.dataTables.min.js'
							),
							array(
								'type' => 'js',
								'value' => 'assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js'
							),  
							array(
								'type' => 'css',
								'value' => 'assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css'
							),

							array(
								'type' => 'css',
								'value' => 'assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css'
							),
							
						),  
		'datepicker' => array(
							array(
								'type' => 'css',
								'value' => 'assets/libs/datepicker/datetimepicker.min.css'
							),
							array(
								'type' => 'js',
								'value' => 'assets/libs/datepicker/moment.min.js'
							),
							array(
								'type' => 'js',
								'value' => 'assets/libs/datepicker/datetimepicker.min.js'
							)
						),
		'validate' => array(
							array(
								'type' => 'js',
								'value' => 'assets/libs/validation/validates.js'
							),
							array(
								'type' => 'css',
								'value' => 'assets/libs/validation/validates.css'
							),


						),

		'dashboard' => array(
							array(
								'type' => 'js',
								'value' => 'assets/libs/jquery-sparkline/jquery-sparkline.min.js'
							),
							array(
								'type' => 'js',
								'value' => 'assets/libs/jquery-vectormap/jquery-vectormap.min.js'
							),
							array(
								'type' => 'js',
								'value' => 'assets/js/pages/dashboard-2.init.js'
							),
						),


		'animation' => array(
							array(
								'type' => 'css',
								'value' => 'css/animate.min.css'
							),
						),
		'fancybox' => array(
						array(
							'type' => 'js',
							'value' => 'assets/libs/fancybox/jquery.fancybox.js'
						),
						array(
							'type' => 'js',
							'value' => 'assets/libs/fancybox/jquery-browser.js'
						),
						array(
							'type' => 'css',
							'value' => 'assets/libs/fancybox/jquery.fancybox.css'
						),
					),
		'dropzone' => array(
						array(
							'type' => 'header_js',
							'value' => 'plugins/dropzone/dropzone.min.js'
						),
						array(
							'type' => 'header_js',
							'value' => 'js/jquery.redirect.js'
						),
						array(
							'type' => 'css',
							'value' => 'plugins/dropzone/dropzone.min.css'
						),
						array(
							'type' => 'css',
							'value' => 'plugins/dropzone/dropzone1.min.css'
						),
					),
		'multiselect' => array(
						array(
							'type' => 'js',
							'value' => 'plugins/multiselect/dist/js/bootstrap-multiselect.js'
						),
						array(
							'type' => 'css',
							'value' => 'plugins/multiselect/docs/css/multiselect.css'
						),  
					),
		'toggle' => array(
						array(
							'type' => 'js',
							'value' => 'plugins/bootstrap-toggle/bootstrap-toggle.min.js'
						),
						array(
							'type' => 'css',
							'value' => 'plugins/bootstrap-toggle/bootstrap-toggle.min.css'
						),
					),
		'select2' => array(
						array(
							'type' => 'js',
							'value' => 'assets/libs/select2/js/select2.min.js'
						),
						array(
							'type' => 'css',
							'value' => 'assets/libs/select2/css/select2.min.css'
						),
					),			
	);
	
	$output['header_js'] = array(
		//'js/bootstrap.min.js',
		//'js/custom_js.js'
	);

	foreach( $values as $value ) {
		$inputs = $options[$value];
		foreach( $inputs as $input ) {
			$output[$input['type']][] = $input['value'];
		}
	}

	return $output;
}

function delete_file($file_path) {
	if( is_file($file_path) ) {
		unlink($file_path);
	}
}

function format_datetime($datetime) {
	return date('j M, Y - h:ia', strtotime($datetime));
}

function format_date($date) {
	return date('j M, Y', strtotime($date));
}

function format_time($time) {
	return date('h:i A', strtotime($time));
}

function timezone_datetime($datetime = '') {
	$timezone_datetime = new DateTime($datetime, new DateTimeZone('Asia/Kolkata'));
	return $timezone_datetime;
}

function posted_ago($datetime, $full = false) {
	$now = timezone_datetime();
	$ago = timezone_datetime($datetime);

	$diff = $now->diff($ago);

	$diff->w = floor($diff->d / 7);
	$diff->d -= $diff->w * 7;

	$string = array(
		'y' => 'year',
		'm' => 'month',
		'w' => 'week',
		'd' => 'day',
		'h' => 'hour',
		'i' => 'minute',
		's' => 'second',
	);
	foreach ($string as $k => &$v) {
		if ($diff->$k) {
			$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
		} else {
			unset($string[$k]);
		}
	}

	if (!$full) $string = array_slice($string, 0, 1);
	return $string ? implode(', ', $string) . ' ago' : 'just now';
}


function debug($item = array(), $die = true, $display = true) {
	if( is_array($item) || is_object($item) ) {
		echo "<pre ".($display?'':'style="display:none"').">"; print_r($item); echo "</pre>";
	} else {
		echo $item;
	}
	
	if( $die ) {
		die();
	}
}

function random_code($length = 16) {
	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	$code = substr( str_shuffle( $chars ), 0, $length );
	return $code;
}

function generate_otp($length = 5) {
	$chars = "0123456789";
	$otp = substr( str_shuffle( $chars ), 0, $length );
	return $otp;
}

/*function get_flashdata($name) {
	return session($name);
}*/

function set_notification($message, $class) {
	//set_flashdata('notification', $message, $class);
}

function get_notification() {
	return get_flashdata('notification');
}

function truncate($string, $word_count = 10) {
	$string = htmlspecialchars_decode(strip_tags($string));
	$words = explode(' ', $string);

	$output = '';
	foreach( $words as $key=>$word ) {
		if( $key < $word_count ) {
			$output .= $word.' ';
		}
	}

	if( sizeof( $words ) > $word_count ) {
		return $output.'...';
	}
	return $output;
}

function month_diff($month1, $month2){
	//$m1 = date_create($month1);
	//$m2 = date_create($month2);
	//$diff = date_diff($m1, $m2);
	//return $diff->m;
	
	//$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
	
	//$diff = abs(strtotime($month1.'-01') - strtotime($month2.'-01'));    
	//$years = floor($diff / (365*60*60*24));
	//return $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
	$m = round(abs(strtotime($month1) - strtotime($month2))/86400);
	if( $m >= 28 ){
		return '1';
	} else {
		return '0';
	}
}

function month_array($month1, $month2){
	$start = new DateTime($month1);
	$end = new DateTime($month2);
	$end->modify('first day of next month');
	$interval = DateInterval::createFromDateString('1 month');
	$period = new DatePeriod($start, $interval, $end);
	
	foreach ($period as $dt) {
		$arr[] = $dt->format("Y-m");
	}
	return $arr;
}

function create_date_range_array($strDateFrom, $strDateTo) {
	$aryRange = array();
	$iDateFrom = mktime(1,0,0,substr($strDateFrom,5,2), substr($strDateFrom,8,2), substr($strDateFrom,0,4));
	$iDateTo = mktime(1,0,0,substr($strDateTo,5,2), substr($strDateTo,8,2), substr($strDateTo,0,4));

	if ($iDateTo>=$iDateFrom) {
		array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
		while ($iDateFrom<$iDateTo)
		{
			$iDateFrom+=86400; // add 24 hours
			array_push($aryRange,date('Y-m-d',$iDateFrom));
		}
	}
	return $aryRange;
}

function create_date_range_array_one($strDateTo) {
	$strDateFrom = date('Y-m-01', strtotime($strDateTo));

	$aryRange = array();
	$iDateFrom = mktime(1,0,0,substr($strDateFrom,5,2), substr($strDateFrom,8,2), substr($strDateFrom,0,4));
	$iDateTo = mktime(1,0,0,substr($strDateTo,5,2), substr($strDateTo,8,2), substr($strDateTo,0,4));

	if ($iDateTo>=$iDateFrom) {
		array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
		while ($iDateFrom<$iDateTo)
		{
			$iDateFrom+=86400; // add 24 hours
			array_push($aryRange,date('Y-m-d',$iDateFrom));
		}
	}
	return $aryRange;
}