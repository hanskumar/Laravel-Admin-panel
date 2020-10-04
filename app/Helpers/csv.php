<?php  //if ( ! defined('BASEPATH')) exit('No direct script access allowed');
ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');
// ------------------------------------------------------------------------

/**
 * CSV Helpers
 * Inspiration from PHP Cookbook by David Sklar and Adam Trachtenberg
 * 
 * @author		J��r�0�0me Jaglale
 * @link		http://maestric.com/en/doc/php/codeigniter_csv
 */

// ------------------------------------------------------------------------

/**
 * Array to CSV
 *
 * download == "" -> return CSV string
 * download == "toto.csv" -> download file toto.csv
 */
if ( ! function_exists('array_to_csv'))
{
	function array_to_csv($array, $download = "")
	{
		if ($download != "")
		{
			@header('Content-Type: application/csv');
			@header('Content-Disposition: attachement; filename="' . $download . '"');
		}	

		ob_start();
		$f = fopen('php://output', 'w') or show_error("Can't open php://output");
		$n = 0;
		foreach ($array as $line)
		{
			$n++;
			if ( ! fputcsv($f, $line))
			{
				show_error("Can't write line $n: $line");
			}
		}
		fclose($f) or show_error("Can't close php://output");
		$str = ob_get_contents();
		ob_end_clean();

		if ($download == "")
		{
			return $str;	
		}
		else
		{
			echo $str;
		}
	}
}

// ------------------------------------------------------------------------

/**
 * Query to CSV
 *
 * download == "" -> return CSV string
 * download == "toto.csv" -> download file toto.csv
 */
if ( ! function_exists('query_to_csv'))
{
	function query_to_csv($query, $headers = TRUE, $download = "")
	{
		if ( ! is_object($query) OR ! method_exists($query, 'list_fields'))
		{
			show_error('invalid query');
		}
		
		$array = array();
		
		if ($headers)
		{
			$line = array();
			foreach ($query->list_fields() as $name)
			{
				$line[] = $name;
			}
			$array[] = $line;
		}
		
		foreach ($query->result_array() as $row)
		{
			$line = array();
			foreach ($row as $item)
			{
				$line[] = $item;
			}
			$array[] = $line;
		}

		echo array_to_csv($array, $download);
	}
}

/* End of file csv_helper.php */
/* Location: ./system/helpers/csv_helper.php */


function parse_csv_file($p_Filepath) {

	$fields = '';            /** columns names retrieved after parsing */ 
	$separator = ';';    /** separator used to explode each line */
	$enclosure = '"';    /** enclosure used to decorate each field */

	$max_row_size = 10000000;

	$file = fopen($p_Filepath, 'r');
	$fields = fgetcsv($file, $max_row_size, $separator, $enclosure);
	$keys_values = explode(',',$fields[0]);

	$content    =   array();
	$keys   =   escape_string($keys_values);

	$i  =   1;
	while( ($row = fgetcsv($file, $max_row_size, $separator, $enclosure)) != false ) {            
		if( $row != null ) { // skip empty lines
			$values =   explode(',',$row[0]);
			if(count($keys) == count($values)){
				$arr    =   array();
				$new_values =   array();
				$new_values =   escape_string($values);
				for($j=0;$j<count($keys);$j++){
					if($keys[$j] != ""){
						$arr[$keys[$j]] = $new_values[$j];
					}
				}

				$content[$i]=   $arr;
				$i++;
			}
		}
	}
	fclose($file);
	return $content;
}

function escape_string($data){
	$result =   array();
	foreach($data as $row){
		$result[]   =   preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", str_replace('"', '',$row)); //str_replace('"', '',$row);
	}
	return $result;
}


/*
//============== Csv ====================
function csv_to_array($Filepath) {
	
	//Get csv file content
	$csvData = file_get_contents($Filepath);

	//Remove empty lines
	$csvData = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\r\n", $csvData);

	//String convert in array formate and remove double quote(")
	$array = array();
	$array = $this->escape_string(preg_split('/\r\n|\r|\n/', $csvData));
	$new_content_in_array = array();
	if($array)
	{
		//Get array key
		$array_keys = array();
		$array_keys = array_filter(array_map('trim', explode(';',$array[0])));

		//Get array value
		$array_values = array();
		for ($i=1;$i<count($array);$i++)
		{
			if($array[$i])
			{
				$array_values[] = array_filter(array_map('trim', explode(';',$array[$i])));
			}
		}

		//Convert in associative array
		if($array_keys && $array_values)
		{
			$assoc_array = array();
			foreach ($array_values as $ky => $val)
			{           
				for($j=0;$j<count($array_keys);$j++){
					if($array_keys[$j] != "" && $val[$j] != "" && (count($array_keys) == count($val)))
					{
						$assoc_array[$array_keys[$j]] = $val[$j];
					}
				}
				$new_content_in_array[] = $assoc_array;
			}
		}
	}
	return $new_content_in_array;
}

function escape_string($data){
	$result =   array();
	foreach($data as $row){
		$result[]   = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", str_replace('"', '',$row));
	}
	return $result;
}
*/