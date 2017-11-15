<?php
/*
	phpDMX API plugin
	Copyright Francois Dechery 2017
*/

//require_once(dirname(__FILE__).'/phpdmx.php');

// ################################################################################################################
class phpDmx_API{

	private $api_version='1.0';
	private $plugin;
	private $default_universe=0;
	private $in_names=array(
		'command'	=>	'com',
		'universe'	=>	'u',
		'scene'		=>	's',
		'raw'		=>	'r',
		'json'		=>	'j',
		'channel'	=>	'c',
		'value'		=>	'v',
	);
	private $inputs=array();
	private $scenes=array();


	// ------------------------------------------------------------------------
	function __construct($plugin_name,$arg1,$arg2=''){
		$class_name="phpDmxPlugin_$plugin_name";
		$class_file="phpdmx_plugin_$plugin_name.php";
		$dir=dirname(__FILE__);
		if(file_exists("$dir/$class_file")){
			require_once("$dir/$class_file");
			$this->plugin=new $class_name($arg1,$arg2);
		}
		else{
			die("Can't find a '$class_file' plugin !");
		}
	} 

	// ------------------------------------------------------------------------
	function Server($default_universe=0,$scenes=''){
		$this->SetScenes($scenes);
		$this->default_universe=$default_universe;
		$this->_GetInputs();

		// format datas -----------------
		if($scene=$this->scenes[$this->inputs['scene']]){
			$dmx_array=$scene;
		}
		else if($this->inputs['json']){
			$dmx_array=$this->_JsonToDmxArray($this->inputs['json']);
		}
		else  if(strlen($this->inputs['json']) > 0){
			$dmx_array=$this->_RawToDmxArray($this->inputs['raw']);
		}
		else  if($this->inputs['channel'] and strlen($this->inputs['value']) ){
			// set command
		}
		else  if(in_array($this->inputs['command'], array('black','get')) ){
			// no data needed for theses command
		}
		else{
			$this->_PrintOutputError("Data is Mandatory! Please provide Raw, Json, or Channel/value");
		}
		
		switch ($this->inputs['command']) {
			case 'get':
				if($dmx=$this->plugin->CommandGet($this->inputs['universe'])){
					$this->_PrintOutput($dmx);
				}
				break;
			case 'replace':
				$state=$this->plugin->CommandReplace($this->inputs['universe'], $dmx_array);
				break;
			case 'merge':
				$state=$this->plugin->CommandMerge($this->inputs['universe'], $dmx_array);
				break;
			case 'set':
				$state=$this->plugin->CommandChannelSet($this->inputs['universe'], $this->inputs['channel'], $this->inputs['value']);
				break;
			case 'black':
				$state=$this->plugin->CommandBlack($this->inputs['universe']);
				break;
			default:
				$this->_PrintOutputError("Invalid Command: '{$this->inputs['command']}'");
				break;
		}
		if(!$state){
			$this->plugin->error or $this->plugin->error="Can't confirm!";
		}
		$this->_PrintOutput();
	}

	// ------------------------------------------------------------------------
	function SetScenes($scenes){
		if(is_array($scenes)){
			$this->scenes=$scenes;
		}
	}

	// ------------------------------------------------------------------------
	function SetInputNaming($name,$value=''){
		if(is_array($name)){
			foreach($name as $k => $v){
				$this->_SetInputName($k,$v);
			}
		}
		else{
			$this->_SetInputName($name,$value);
		}
	}

	// PRIVATE #################################################################

	private function _PrintOutputError($err_msg="Unexpected Error"){
		$this->error=$err_msg;
		$this->_PrintOutput();
	}

	// ------------------------------------------------------------------------
	private function _PrintOutput($dmx=''){
		$out=array();
		$out['error']	=0;
		$out['err_msg']	="";
		if($this->plugin->error){
			$out['error']	=1;
			$out['err_msg']	=$this->plugin->error;
		}
		if(is_array($dmx)){
			$out['dmx']	= $dmx;
		}
		if(isset($_GET['debug'])){
			$out['info']	=$this->plugin->info;
			$out['info']['API_version']=$this->api_version;
			$out['inputs']	=$this->inputs;
			$out['dmx_sent']	=$this->plugin->dmx_array_sent;

			echo "<h4>Debug Output</h4><hr><pre>";
			print_r($out);
		}
		else{
			echo json_encode($out);
		}
		exit;
	}

	// ------------------------------------------------------------------------
	private function _SetInputName($name,$value){
		if(isset($this->in_names[$name]) and $value){
			$this->in_names[$name]=$value;
		}
	}

	// ------------------------------------------------------------------------
	private function _GetInputs(){
		foreach($this->in_names as $k=>$v){
			$this->inputs[$k]=$_GET[$v];
		}
		strlen($this->inputs['universe']) or $this->inputs['universe']=$this->default_universe;
	}

	// ----------------------------------
	function _JsonToDmxArray($json){
		$array=json_decode($json,true);
		//offset index to start at 1;
		$array[0]=0;
		ksort($array);
		return $array;
	}

	// ----------------------------------
	function _RawToDmxArray($raw){
		$array=explode(',',$raw);
		//offset index to start at 1;
		array_unshift($array, 0);
		//fill remaining with 0
		$start=count($array);
		for($i=$start; $i < 513; $i++){
			$array[$i]=0;
		}
		return $array;
	}

}

?>