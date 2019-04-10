<?php
/**
 * ajax script
 * return result recherche input //typehead.js
 */
include_once('../../../../wp-config.php');
include_once('../../../../wp-load.php');
include_once('../../../../wp-includes/wp-db.php');

$table_name = $wpdb->prefix . "custom_map";
$where = $_GET["query"];
$sqlquery = 'SELECT *  from '.$table_name.' where UCASE(Nom) like "%'.strtoupper($where).'%" OR CodePostal="'.$where.'" OR UCASE(Pays)like"%'.strtoupper($where).'%" OR UCASE(Ville)like"%'.strtoupper($where).'%" ';
$data = array();
$getData = $wpdb->get_results( $sqlquery );
foreach ($getData as $key => $value) {
	# code...
	$data[]=$value->id.' - '.$value->Nom.' - '.$value->CodePostal.' - '.$value->Pays;
}
echo json_encode($data);
die();