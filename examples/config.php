<?php
$cfg['universe']	=1;
$cfg['server_ip']	="ola.lo.lo";
$cfg['dmx_address']	=1;
$cfg['path_lib']	=dirname(dirname(__FILE__)).'/lib/';

$cfg['html_head']=<<<EOF
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> 
	<!-- BootStrap -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<!--HEAD_CONTENT-->

<style>
.att_table{
	border: 1px solid #ccc;
}
.att_table TD{
	font-size: 12px;
	padding: 3px 6px;
	line-height: 100%;
	border-bottom: 1px solid #ccc;
}
.att_table .c1{
	text-align: right;
	font-weight:bold;
}
.array{
	font-size:11px;
	line-height: 110%;
}
HR{
	margin: 5px;
}
</style>
</head>
<body>
	<div class="container">
<!-- END Header -->
EOF;

$cfg['html_foot']=<<<EOF
<!-- START Footer -->
	</div>
</body>
</html>
EOF;


// UTILS #############################################################################################################################################################
function HtmlHead($title='',$extras=''){
	global $cfg;
	$title and $head="<title>$title</title>\n";
	$head .=$extras;
	return str_replace('<!--HEAD_CONTENT-->',$head, $cfg['html_head']);	
}

// ------------------------------------------------------------------------
function AttributeToHtml($obj, $att, $name=''){
	$value=$obj->$att;
	$name or $name=$att;
	$name=ucwords(str_replace('_',' ',$name));
	return "<tr><td class='c1'>$name :</td><td>$value</td></tr>\n";
}

?>