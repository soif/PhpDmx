<?php
/*

*/
// Information ---------------------------------------------------------
$o['manufacturer']	="Generic";
$o['model']			="";
$o['description']	="4 Channels Relay";
$o['url']			="https://www.aliexpress.com/item/x/32793354973.html";
$o['type']			="relay";

// Channels -------------------------------------------------------------
$o['channels'][1]['type']="relay";
$o['channels'][1]['name']="relay1";

$o['channels'][2]['type']="relay";
$o['channels'][2]['name']="relay2";

$o['channels'][3]['type']="relay";
$o['channels'][3]['name']="relay3";

$o['channels'][4]['type']="relay";
$o['channels'][4]['name']="relay4";


// Presets -------------------------------------------------------------
$o['presets']['p_all_on']	=array(1=>255,2=>255,3=>255,4=>255);
$o['presets']['p_all_off']	=array(1=>0,2=>0,3=>0,4=>0);

?>