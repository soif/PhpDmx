<?php
/*
	phpDMX Main Class
	Copyright Francois Dechery 2017
*/

// #############################################################################
class phpDmxPlugin{

	public $last_error='';
	public $info=array();
	public	$dmx_array_sent	=array();

	// ----------------------------------
	function __construct(){
		
	} 

	// ------------------------------------------------------------------------
	function CommandBlack($universe){
		$dmx_array=array(512=>0);
		return $this->CommandReplace($universe,$dmx_array);
	}

	// ------------------------------------------------------------------------
	function CommandGet($universe){
		$this->error="Method '".__FUNCTION__ ."' is not Implemented in this Plugin";
	}

	// ------------------------------------------------------------------------
	function CommandChannelSet($universe,$channel,$value){
		$this->error="Method '".__FUNCTION__ ."' is not Implemented in this Plugin";
	}

	// ------------------------------------------------------------------------
	function CommandMerge($universe,$dmx_array){
		$this->error="Method '".__FUNCTION__ ."' is not Implemented in this Plugin";
	}

	// ------------------------------------------------------------------------
	function CommandReplace($universe,$dmx_array){
		$this->error="Method '".__FUNCTION__ ."' is not Implemented in this Plugin";
	}

	// ------------------------------------------------------------------------
	function _SetInfo(){
		$this->info['plugin_name']		=$this->plugin_name;
		$this->info['plugin_version']	=$this->plugin_version;
	}

}
?>