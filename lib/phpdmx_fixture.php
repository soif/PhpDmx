<?php
/*
	phpDMX Main Class
	Copyright Francois Dechery 2017
*/

// #############################################################################
class phpDmxFixture{

	public $id			='';	// unique ID to define per fixture
	public $address		=1;		// DMX Address
	public $code		='';	// normalized name code = fixtures template name
	public $hw_manufacturer='';	// Hardware Manufacturer
	public $hw_model	='';	// Hardware Model
	public $description	='';	// Hardware Description
	public $url			='';	// Hardware Example URL
	public $type		='';	// Type
	public $channels	=array();
	public $ch_names	=array();
	public $presets		=array();
	public $template_presets=array();

	private $fixtures_path		='';
	private $channels_inited	=false;

	// ----------------------------------
	function __construct($id='',$fixture_code='custom',$address=''){
		$this->fixtures_path=dirname(__FILE__).'/fixtures/';
		
		if(!$id){
			$id=$fixture_code.'_'.substr(md5(microtime().rand(1,100000)),0,8);
		}
		
		$this->SetId($id);

		if($fixture_code=='custom'){
			$this->_InitCustom();
		}
		elseif($fixture_code){
			$this->_LoadFixture($fixture_code);
		}

		$this->SetAdress($address);
	} 

	// ------------------------------------------------------------------------
	function ListDmxPresets(){
		foreach($this->presets as $k => $prese){
			$out[$k]= $this->GetDmxPreset($k);
		}
		return $out;
	}

	// ------------------------------------------------------------------------
	function GetImages($code=''){
		$code or $code=$this->code;
		$out['dir']	="{$code}/img/";
		$out['path']="{$this->fixtures_path}{$out['dir']}";

		$imgs=$this->_myReadDir($out['path'],true,false);
		$img_count=count($imgs);
		if($img_count){
			$out['image']=$imgs[0];
			unset($imgs[0]);
			$img_count--;			
		}
		else{
			return false;
		}
		if($img_count){
			foreach($imgs as $k =>$v){
				$img_name=ucfirst(preg_replace('#^\d+_([^\.]+)\.jpg#',"$1",$v));
				
				$icon="imgs";
				if(preg_match('#^Doc#',$img_name)){
					$icon="docs";
				}
				$out[$icon][]=$v;
			}
		}
		return $out;
	}


	// ------------------------------------------------------------------------
	function GetDmx($preset_key_or_params,$fill_all=false){
		if(is_array($preset_key_or_params)){
			return $this->GetDmxCustom($preset_key_or_params,$fill_all);
		}
		else{
			return $this->GetDmxPreset($preset_key_or_params);
		}
	}

	// ------------------------------------------------------------------------
	function GetDmxPreset($preset_key){
		if($preset=$this->presets[$preset_key]){
			return $this->_convertPresetToDmxArray($preset,$this->address);
		}
	}

	// ------------------------------------------------------------------------
	function GetDmxCustom($channels, $fill_all=false){
		$this->CreatePreset('tmp_preset_created_from_scratch', $channels, $fill_all);
		if($preset=$this->presets['tmp_preset_created_from_scratch']){
			return $this->_convertPresetToDmxArray($preset,$this->address);
		}
	}


	// ------------------------------------------------------------------------
	function SetAdress($address){
		if($address and $address <= 512){
			$this->address=$address;
		}
	}
	// ------------------------------------------------------------------------
	function SetId($id){
		if($id){
			$this->id=$id;
		}
	}

	// ------------------------------------------------------------------------
	function Define($params){
		$params['code']			and	$this->code				=$params['code'];
		$params['manufacturer']	and	$this->hw_manufacturer	=$params['manufacturer'];
		$params['model']		and	$this->hw_model			=$params['model'];
		$params['description']	and	$this->description		=$params['description'];
		$params['url']			and	$this->url				=$params['url'];
		$params['type']			and	$this->type				=$params['type'];
		$this->_initChannels($params['channels']);
		$params['presets']		and	$this->template_presets	=$params['presets'];
		
		$this->_CreatePresets();
	}

	// ------------------------------------------------------------------------
	function CreatePreset($name, $channels, $fill_all){
		unset($this->presets[$name]);
		$this->_initChannels($channels);

		if($fill_all){
			$count=count($this->channels);
			for($i=1;$i<=$count;$i++){
				$this->presets[$name][$i]=0;
			}
		}

		foreach($channels as $k => $v){
			$i=$this->_getChannelIndex($k);
			$this->presets[$name][$i]=$v;
		}
		ksort($this->presets[$name]);
	}



	// ### PRIVATE #############################################################


	// ------------------------------------------------------------------------
	function _CreatePresets(){
		$colors_ch=array();
		foreach($this->channels as $k => $chan){
			$ch_arr=array();
			if($chan['type']=='dim'){
				// PRESET : dim +++++++
				$master_dim_ch= $k;
				$this->CreatePreset('dim_on',	array($k=>255)	, false);
				$this->CreatePreset('dim_half',array($k=>123)	, false);
				$this->CreatePreset('dim_off',	array($k=>0)	, false);
			}
			elseif($chan['type']=='relay'){
				// PRESET : dim +++++++
				$this->CreatePreset('relay_on',		array($k=>255)	, false);
				$this->CreatePreset('relay_off',	array($k=>0)	, false);
			}
			elseif( in_array($chan['type'],array('red','green','blue','white'))){
				// PRESET : color +++++++
				$master_dim_ch and $ch_arr[$master_dim_ch]=255;
				$ch_arr[$k]=255;
				$this->CreatePreset($chan['type'],			$ch_arr			, true);
				$this->CreatePreset($chan['type'].'_on',	array($k=>255)	, false);
				$this->CreatePreset($chan['type'].'_half',	array($k=>123)	, false);
				$this->CreatePreset($chan['type'].'_off',	array($k=>0)	, false);
				$colors_ch[$k]=$k;
			}
			elseif( $chan['type']=='mode'){
				// PRESET : mode +++++++
				foreach($chan['modes'] as $mode => $n){
					$this->CreatePreset($chan['name'].'-'.$mode,	array($k=>$n)	, false);
				}
			}
			elseif( $chan['type']=='speed'){
				// PRESET : speed +++++++
				$this->CreatePreset('speed_slow',	array($k=>0)	, false);
				$this->CreatePreset('speed_mid',	array($k=>150)	, false);
				$this->CreatePreset('speed_fast',	array($k=>255)	, false);
			}
			//assign names
			$name=$chan['name'] or $name=$chan['type'] ;
			$this->channels[$k]['name']=$name;
			$this->ch_names[$name]=$k;
		}

		if(count($colors_ch)){

		// PRESET : all colors +++++++
			foreach($colors_ch as $ch){
				$all_on[$ch]	=255;
				$all_half[$ch]	=123;
				$all_off[$ch]	=0;
			}

			$this->CreatePreset('all',		$this->_makeColor($all_on,		$master_dim_ch, 255),	true);

			$this->CreatePreset('all_on',	$this->_makeColor($all_on,		$master_dim_ch, 255),	false);
			$this->CreatePreset('all_half',	$this->_makeColor($all_half,	$master_dim_ch, 255),	false);
			$this->CreatePreset('all_off',	$this->_makeColor($all_off,		$master_dim_ch, 255), 	false);

			// PRESET : yellow, purple, cyan  colors +++++++
			if(count($colors_ch)>=3){
				$colors['yellow']	=array('red'=>255,	'green'=>255,	'blue'=>0);
				$colors['purple']	=array('red'=>255,	'green'=>0,		'blue'=>255);
				$colors['cyan']		=array('red'=>0,	'green'=>255,	'blue'=>255);
				foreach($colors as $col_name => $color){
					$this->CreatePreset($col_name,	$this->_makeColor($color,	$master_dim_ch, 255), true);
					$this->CreatePreset($col_name."_on",	$this->_dimColor(255,	$this->_makeColor($color,	$master_dim_ch, 255), $master_dim_ch), false);
					$this->CreatePreset($col_name."_half",	$this->_dimColor(123,	$this->_makeColor($color,	$master_dim_ch, 255), $master_dim_ch), false);
					$this->CreatePreset($col_name."_off",	$this->_dimColor(0, 	$this->_makeColor($color,	$master_dim_ch, 255), $master_dim_ch), false);
				}
				
			}
		}
		
		// template presets
		if(count($this->template_presets)){
			foreach($this->template_presets as $name => $channels){
				$this->presets[$name]=$channels;
			}
			//unset($this->template_presets);
		}
	}

	// ------------------------------------------------------------------------
	private function _getChannelIndex($index_or_name){
		$n=$index_or_name;
		if(!is_numeric($n)){
			$n=$this->ch_names[$n];
		}
		return $n;
	}

	// ------------------------------------------------------------------------
/*
	private function _getChannel($index_or_name){
		return $this->presets[$this->_getChannelIndex($index_or_name)];
	}
*/
	// ------------------------------------------------------------------------
	private function _convertPresetToDmxArray($preset,$address=0){
		$address or $address=$this->address;
		$offset= $address - 1;
		foreach($preset as $k => $v){
			$out[$k+$offset]=$v;
		}
		return $out;		
	}

	
	// ------------------------------------------------------------------------
	private function _LoadFixture($fixture_code){
		$file=$this->fixtures_path.$fixture_code.'/info.php';
		if(file_exists($file)){
			include($file);
			$o['code']=$fixture_code;
			$this->Define($o);
		}
	}

	// ------------------------------------------------------------------------
	private function _InitCustom(){
		$this->code				='custom';
		$this->hw_manufacturer	='Unknown';
		$this->hw_model			='Unknown';
		$this->description		='';
		$this->url				='';
		$this->type				='custom';
		
	}

	// ------------------------------------------------------------------------
	private function _initChannels($channels){
		if($channels and !$this->channels_inited){
			$this->channels=$channels;
			foreach($channels as $n => $ch){
				$name=$this->channels[$n]['name'] or $name=$this->channels[$n]['type'];
				$this->channels[$n]['name']	=$name;
				$this->channels[$n]['num']	=$n;
				$this->ch_names[$name]		=$n;
			}
			$this->channels_inited=true;
		}
	}

	// ------------------------------------------------------------------------
	private function _makeColor($array_ch_val,$dim_ch='',$dim_val=255){
		$out=array();
		$dim_ch and $out[$dim_ch]=$dim_val;
		foreach($array_ch_val as $c => $v){
			$i=$this->_getChannelIndex($c);
			$out[$i]=$v;
		}
		return $out;
	}

	// ------------------------------------------------------------------------
	private function _dimColor($dim,$array_ch_val,$dim_ch=''){
		foreach($array_ch_val as $c => $v){
			$i=$this->_getChannelIndex($c);
			if($dim_ch){
				$out[$dim_ch]	=$dim;
				$out[$i]		=$v;
			}
			else{
				$out[$i]		=$dim;				
			}
		}
		return $out;
	}

	// ------------------------------------------------------------------------------------------------------------------------
	private function _myReadDir($path,$show_file=true,$show_dir=true){
		$arr=array();
		if(file_exists($path)){
			$arr=scandir($path);
			foreach($arr as $k => $v){
				if( 	($v=='.' or $v=='..') 			or
						(!$show_dir and is_dir($path.$v))	or
						(!$show_file and is_file($path.$v)) ){
					unset($arr[$k]);
				}
			}
			sort($arr);
		}
		return $arr;
	}



}
?>