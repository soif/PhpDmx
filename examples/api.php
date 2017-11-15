<?php
// get $cfg 
require_once(dirname(__FILE__).'/config.php');

// set your own setting :
$server_ip		=$cfg['server_ip'];		// IP address of the OLA Server
$universe		=$cfg['universe'];		// OLA Universe connected to DMX hardware
$dmx_address	=$cfg['dmx_address'];	// test fixture address

// set channesl according to your test fixture
$dim_ch		=1;
$red_ch		=2;
$green_ch	=3;
$blue_ch	=4;
$white_ch	=5;


// Some Scenes examples -------------------------------------------------
$offset=$dmx_address -1;
// the 'black' scene :
$scenes['black'][$red_ch	+$offset]=0;		// Red
$scenes['black'][$green_ch	+$offset]=0;		// Green
$scenes['black'][$blue_ch	+$offset]=0;		// Blue
$scenes['black'][$white_ch	+$offset]=0;		// White
$scenes['black'][$dim_ch	+$offset]=0;		// Master Dimmer

// the 'red' scene :
$scenes['red']						=$scenes['black'];	//all black
$scenes['red'][$red_ch	+$offset]	=255;				// Red On
$scenes['red'][$dim_ch	+$offset]	=255;				// Master Dimmer

// the 'green' scene :
$scenes['green']						=$scenes['black'];	//all black
$scenes['green'][$green_ch	+$offset]	=255;				// Green On
$scenes['green'][$dim_ch	+$offset]	=255;				// Master Dimmer

// the 'blue' scene :
$scenes['blue']							=$scenes['black'];	//all black
$scenes['blue'][$blue_ch	+$offset]	=255;				// Blue On
$scenes['blue'][$dim_ch	+$offset]	=255;				// Master Dimmer

// the 'yellow' scene :
$scenes['yellow']						=$scenes['black'];	//all black
$scenes['yellow'][$green_ch	+$offset]	=255;				// Green On
$scenes['yellow'][$blue_ch	+$offset]	=255;				// Blue On
$scenes['yellow'][$dim_ch	+$offset]	=255;				// Master Dimmer


/* 
---	Some Queries to test --------------------
* Replace some scenes (s)
?com=replace&s=yellow
?com=replace&s=blue

* Merge a channel(c) / value(v)
?com=merge&c=15&v=255

* Replace a raw (r) series of dmx value, starting at DMX address 1
?com=replace&c=15&r=255,0,0,255

* Merge a json (j) series of dmx value
?com=merge&c=15&j={'3':255,'4':100}

---> You might like to add '&debug' at the end of the query, ie ?com=replace&s=yellow&debug
*/



// Api Server ##############################################################

require_once($cfg['path_lib'].'phpdmx_api.php');
// with OLA (most powerfull)
$API=new phpDmx_API('ola',$server_ip);
$API->Server($universe,$scenes);

// with with Art-Net
//$API=new phpDmx_API('artnet',$server_ip);
//$API->Server($universe,$scenes);


?>