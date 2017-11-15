<?php
/*
	phpDMX OLA plugin
	Copyright Francois Dechery 2017
*/

require_once(dirname(__FILE__).'/phpdmx_plugin.php');
// #############################################################################
class phpDmxPlugin_ola extends phpDmxPlugin{

	public	$plugin_name	='Ola';
	public	$plugin_version	='1.0';

	private $server_ip;			// server ip or Dns Name
	private $server_port=9090;	// server port
	private $server_url;		// server URL
	
	// ------------------------------------------------------------------------
	function __construct($server_ip, $server_port=''){
		//parent::__construct();
		$this->server_ip 	= $server_ip;
		$server_port and $this->server_port 	= $server_port;
		$this->_SetServerUrl();
		$this->_SetInfo();
	} 

	// ------------------------------------------------------------------------
	function CommandGet($universe){
		$url="{$this->server_url}/get_dmx?u=$universe";
		$json=@json_decode(file_get_contents($url),true);
		if(!$json['error'] and is_array($json['dmx'])){
			$dmx_array=$json['dmx'];
			//offset index to start at 1;
			array_unshift($dmx_array, 0);
			return $dmx_array;
		}
		$this->error="No Answer";
	}

	// ------------------------------------------------------------------------
	function CommandChannelSet($universe,$channel,$value){
		$dmx_array[0]=0;
		$dmx_array[$channel]=$value;
		return $this->CommandMerge($universe,$dmx_array);
	}

	// ------------------------------------------------------------------------
	function CommandMerge($universe,$dmx_array){
		if($dmx_current=$this->CommandGet($universe)){
			$this->dmx_array_sent=$dmx_array;
			$dmx_mixed=array_replace($dmx_current,$dmx_array);
			return $this->CommandReplace($universe,$dmx_mixed);
		}
		$this->error="Can't Get current DMX state";
	}

	// ------------------------------------------------------------------------
	function CommandReplace($universe,$dmx_array){
		$this->dmx_array_sent=$dmx_array;
		$dmx_emply=array(512=>0);
		$dmx_array=array_replace($dmx_emply,$dmx_array);
		$raw=$this->_DmxArrayToRaw($dmx_array);

		$url="{$this->server_url}/set_dmx";
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_POST, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS, array("u" => $universe, "d" => $raw));
		$result = curl_exec($ch);
		curl_close($ch);
		return true;
	}


	// ### PRIVATE #############################################################

	// ----------------------------------
	function _SetInfo(){
		parent::_SetInfo();
		$this->info['server_ip']	=$this->server_ip;
		$this->info['server_port']	=$this->server_port;
	}

	// ------------------------------------------------------------------------
	private function _SetServerUrl(){
		$this->server_url 	= "http://{$this->server_ip}:{$this->server_port}";
	} 

	// ----------------------------------
	function _DmxArrayToRaw($array){
		//remove 0
		unset($array[0]);
		ksort($array);
		// fill empty with 0
		$last=end(array_keys($array));
		for($i=1;$i <= $last ; $i++){
			if(!isset($array[$i])){
				$array[$i]=0;
			}
		}
		ksort($array);
		$raw=implode(',',$array);
		return $raw;
	}

}

?>