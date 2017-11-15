<?php
/*

*/
// Information ---------------------------------------------------------
$o['manufacturer']	="Generic";
$o['model']			="";
$o['description']	="Par RGBW 12 Leds";
$o['url']			="https://www.aliexpress.com/item/x/32698020245.html";
$o['type']			="projector";

// Channels -------------------------------------------------------------
$o['channels'][1]['type']="dim";
$o['channels'][2]['type']="red";
$o['channels'][3]['type']="green";
$o['channels'][4]['type']="blue";
$o['channels'][5]['type']="white";
$o['channels'][6]['type']="mode";
$o['channels'][6]['name']="anim";
$o['channels'][6]['modes']['dim-strobe']=1;
$o['channels'][6]['modes']['flash']		=51;
$o['channels'][6]['modes']['gradient']	=101;
$o['channels'][6]['modes']['pulse']		=151;
$o['channels'][6]['modes']['sound']		=201;
$o['channels'][7]['type']="speed";

// Presets -------------------------------------------------------------
$o['presets']['p_strobe']	=array(1=>255,2=>255,3=>255,4=>255,5=>255);
$o['presets']['p_strobe'][6]=1;
$o['presets']['p_strobe'][7]=250;

$o['presets']['p_color']	=array(1=>255,2=>255,3=>255,4=>255,5=>255);
$o['presets']['p_color'][6]	=51;
$o['presets']['p_color'][7]	=220;

$o['presets']['p_sound']	=array(1=>255,2=>255,3=>255,4=>255,5=>255);
$o['presets']['p_sound'][6]	=201;
$o['presets']['p_sound'][7]	=0;

?>