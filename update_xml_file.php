<?php

/**
 * get xml file from URL
 * save file localy
 */
// save xml file into server 
$filename =MAP__PLUGIN_DIR. 'files/map.xml';

set_time_limit(0);
$fp = fopen ($filename, 'w+');
$ch = curl_init('http://appli.newtritioncoach.com/ExportCoachs.php?action=coachs&key=617428');// or any url you can pass which gives you the xml file
curl_setopt($ch, CURLOPT_TIMEOUT, 50);
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_exec($ch);
curl_close($ch);
fclose($fp);

echo 'done ';
die();

?>