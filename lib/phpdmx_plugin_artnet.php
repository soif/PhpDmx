<?php
/*
	phpDMX ArtNet plugin
	Copyright Francois Dechery 2017
*/

require_once(dirname(__FILE__).'/phpdmx_plugin.php');
// #############################################################################
class phpDmxPlugin_artnet extends phpDmxPlugin{

	public	$plugin_name	='Art-Net';
	public	$plugin_version	='1.0';

	private $server_ip;			// server ip or Dns Name

	
	// ------------------------------------------------------------------------
	function __construct($server_ip){
		$this->server_ip 	= $server_ip;
		$this->_SetInfo();
	} 

	// ------------------------------------------------------------------------
	function CommandReplace($universe,$dmx_array){
		$this->dmx_array_sent=$dmx_array;
		$raw=$this->_DmxArrayToRaw($dmx_array);
        $sum = (count($dmx_array) < 512) ? "\x00".chr(count($dmx_array)) : "\x02\x00";
		$packet = "Art-Net\x00\x00\x50\000\016\x00\x01" . chr($universe) . "\x00". $sum .$raw;

		$sock = fsockopen('udp://' . $this->server_ip, 0x1936);		
		fwrite($sock, $packet);
		fclose($sock);
		return true;		
	}


	// ### PRIVATE #############################################################

	// ----------------------------------
	function _SetInfo(){
		parent::_SetInfo();
		$this->info['server_ip']=$this->server_ip;
	}
	
	// ----------------------------------
	function _DmxArrayToRaw($dmx_array){
		//fill all array
		$dmx_emply=array(512=>0);
		$dmx_array=array_replace($dmx_emply,$dmx_array);

		//remove 0
		unset($dmx_array[0]);
		ksort($dmx_array);
		// fill empty with 0
		//$last=end(array_keys($dmx_array));
		$last=512;
		for($i=1; $i <= $last ; $i++){
			if(!isset($dmx_array[$i])){
				$dmx_array[$i]=0;
			}
			$out[$i]=chr($dmx_array[$i]);
		}
		ksort($out);
		$raw=implode('',$out);		
		return $raw;
	}

}

?>