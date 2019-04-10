<?php
/**
 * script to read data from DB
 * create js file with json content
 */

global $wpdb;
$table_name = $wpdb->prefix . "custom_map";
$sql = "SELECT * FROM $table_name  ";

$result = $wpdb->get_results($sql, OBJECT);

$filename =MAP__PLUGIN_DIR. 'js/location.js';

$jsStringColl='var allLocations = [';
$jsString='';
if(!empty($result)){
	//convert result to json 
	 $jsString='var allLocations = '.json_encode($result).'';

}
else {
	die('no result ');
}

// save file js format 
//open or create the file
$handle = fopen($filename,'w+');

//write the data into the file
fwrite($handle,$jsString);

//close the file
fclose($handle);

echo 'done';
die();