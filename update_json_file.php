<?php
/**
 * read xml file
 * convert to json
 * get latitude and lg from google gecode
 */
$url =MAP__PLUGIN_DIR. 'files/map.xml';
$fileContents = utf8_encode(file_get_contents($url));
$xml = new SimpleXMLElement($fileContents);
$xml = new JsonSimpleXMLElementDecorator($xml);
$xmlJson= json_encode($xml, JSON_PRETTY_PRINT);
$data = json_decode($xmlJson, TRUE);
$options = get_option('map_settings');
$mapkey= !empty($options['map_key']) ?  $options['map_key'] : false;

if(!$mapkey)
	die('add map key');

$allAdress=[];
// read json 
foreach ($data as $key => $value) {
	foreach ($value as $key => $cord) {
		// get lattitude lang
		$adress=getAdressFormat($cord);

		// get lat long from google api 
		$coordinates = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($adress) . '&sensor=true&key='.$mapkey);
	    $coordinates = json_decode($coordinates);

	    if(!empty($coordinates->results[0]->geometry->location->lat)){
  	    	$lat = $coordinates->results[0]->geometry->location->lat;
  	  		$lng = $coordinates->results[0]->geometry->location->lng;

  			  $cord['lat']=$lat;
  			  $cord['lng']=$lng;
	    }
				
		$allAdress[]=$cord;
	}
}

$filename =MAP__PLUGIN_DIR. 'files/map.json';

// save file json format 
//open or create the file
$handle = fopen($filename,'w+');

//write the data into the file
fwrite($handle,json_encode($allAdress));

//close the file
fclose($handle);

echo 'done';
die();
function getAdressFormat($cordonne)
{

  $adress='';
  if(!empty($cordonne['CodePostal']))
    $adress.=$cordonne['CodePostal'];

  if(!empty($cordonne['Pays']))
    $adress.=' '.$cordonne['Pays'];

	return $adress;
}

 ?>
 