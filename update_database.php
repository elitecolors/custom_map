<?php
/**
 * script to read json file with map information
 * update database with new data
 */

// Read JSON file
$filename =MAP__PLUGIN_DIR. 'files/map.json';
$json = file_get_contents($filename);

//Decode JSON
$json_data = json_decode($json,true);

//update database
if(!empty($json_data)){
	global $wpdb;

	$table_name = $wpdb->prefix . "custom_map";

	// clear table 
	$wpdb->query("TRUNCATE TABLE $table_name");

	foreach ($json_data as $key => $value) {

		$wpdb->insert( 
		    $table_name, 
		    array( 
		        'No'     => $value['No'],
		        'Nom'    => $value['Nom'],
		        'email' => $value['email'],
		        'Tel1'   => $value['Tel1'],
		        'Tel2'      => $value['Tel2'],
		        'Rue'      => $value['Rue'],
		        'Num'      => $value['Num'],
		        'CodePostal' => $value['CodePostal'],
		        'Ville'      => $value['Ville'],
		        'Pays'      => $value['Pays'],
		        'Demo'      => $value['Demo'],
		        'Minimal'      => $value['Minimal'],
		        'lat'      => !empty($value['lat']) ?  $value['lat'] : '',
		        'lng'      => !empty($value['lng']) ?  $value['lng'] : '',
		        'URLSite'      => !empty($value['URLSite']) ?  $value['URLSite'] : '',
		        'URLLogo'      => !empty($value['URLLogo']) ?  $value['URLLogo'] : '',
		        'keyword'      => !empty($value['keyword']) ?  $value['keyword'] : '',
		    )
		);
	}

	die('done');
}