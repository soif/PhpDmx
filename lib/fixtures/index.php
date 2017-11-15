<?php
$fixture_path=dirname(__FILE__).'/';


// ------------------------------------------------------------------------------------------------------------------------
function MyReadDir($path,$show_file=true,$show_dir=true){
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
// ------------------------------------------------------------------------------------------------------------------------
function FixtureToHtml($fixture){
	global $fixture_path;
	include("$fixture_path$fixture/info.php");

	$imgs=MyReadDir("$fixture_path$fixture/img/",true,false);
	$img_dir="$fixture/img";
	$first_img="$img_dir/{$imgs[0]}";
	$img_count=count($imgs);
	if($img_count > 1){
		unset($imgs[0]);
		$img_count--;
		foreach($imgs as $k =>$v){
			$img_name=ucfirst(preg_replace('#^\d+_([^\.]+)\.jpg#',"$1",$v));
			$icon="picture";
			if(preg_match('#^Doc#',$img_name)){
				$icon="book";
			}
			$img_url="$img_dir/$v";
			$html_img .="<a href='$img_url' target='_blank'><span class='glyphicon glyphicon-$icon'></span> $img_name</a>";
			$img_count--;
			$img_count and $html_img .="&nbsp;&nbsp; <br>";
		}
	}
	$ch_count=count($o['channels']);
	if(is_array($o['channels'])){
		$html_channels.="<table id='ch_table' cellspacing=0 cellpadding=0>\n";	
		foreach($o['channels'] as $k => $ch){
			$name =$ch['name'] or $name =$ch['type'];
			$name=ucfirst($name);
			$html_channels .="<tr><td class='ch'>$k</td><td class='fn'>$name</td></tr>\n";
		}
		$html_channels.="</table>";	
	}
	$html .=<<<FIX
<div class="row">
  <div class="col-xs-2 col-sm-2">
      <a href="$first_img" target="_blank"><img class="media-object" src="$first_img" alt="" width=100% style="max-width:70px"></a>
  </div>
  <div class="col-xs-6 col-sm-6">

    <h4 class="media-heading">$fixture <small>( {$o['manufacturer']} / {$o['model']} )</small></h4>
	{$o['description']}<br>
	<small>
		<a href="{$o['url']}" target="_blank"><span class="glyphicon glyphicon-globe" aria-hidden="true"></span> {$o['type']}</a> - <b>$ch_count DMX channels</b>. 
	</small><br>
  </div>
  <div class="col-xs-2 col-sm-1">
	<div>$html_channels</div>
  </div>
  <div class="col-xs-2 col-sm-2">
	<small>$html_img</small>
  </div>
</div>
<hr>
FIX;
	return $html;
}

// ############################################################################################################################################
$list=MyReadDir($fixture_path, false);
foreach($list as $fixture){
	$html .=FixtureToHtml($fixture);
}

echo <<<EOF
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- BootStrap -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<title>phpDMX Fixtures</title>
<style>
#ch_table{
	border: 1px solid #ccc;
}
#ch_table TD{
	font-size:9px;
	padding: 1px 3px;
	line-height:100%;
}
#ch_table .ch{
	font-weight:bold;
}
HR{
	margin: 5px;
}
</style>
</head>
<body>
	<div class="container">
    	<h2>Fixtures</h2>
<hr>
$html
	</div>
</body>
</html>
EOF;
?>