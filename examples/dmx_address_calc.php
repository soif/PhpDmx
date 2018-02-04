<?php
// get $cfg 
require_once(dirname(__FILE__).'/config.php');

echo HtmlHead('DMX Address Converter');
echo <<<EOF

<script language="javascript">

$(document).ready(function(){
	var bin_len=9;

	function pad (str, max) {
	  str = str.toString();
	  return str.length < max ? pad("0" + str, max) : str;
	}

	function updateInputBinary(){
		var r='';
		$('.digit').each(function( index, element ){
			var val=$(element).val();
			r=val + r;
		});
		$('#in_bin').val(r).trigger('change');
	}

	function updateDigits(){
		var in_bin=$('#in_bin').val();
		var n=bin_len -1;
		$('.digit').each(function( index, element ){
			var val=$(element).val(in_bin[n]);
			n--;
		});
	}

	$('#in_bin').on('change',function(){
		var bin=$(this).val();
		var dec=parseInt(bin, 2);
		console.log('convert '+bin+' to '+dec);		
		$('#in_dec').val(dec);
	});
	$('#in_dec').on('keyup',function(){
		var dec=$(this).val();
		var bin=(dec >>> 0).toString(2);
		bin=pad(bin, bin_len)
		console.log('convert '+dec+' to '+bin);		
		$('#in_bin').val(bin);
		updateDigits();
	});

	$('.digit').on('focus',function(){
		$(this).select();
	});

	$('.digit').on('keyup',function(){
		var val=$(this).val();
		if(val.length > 0){
			if( (val != '0' && val !='1') || val.length > 1 ){
				$(this).val('');
				$(this).select();
			}
			else{
				var next_index=	$('.digit').index(this) + 1;
				if(next_index>8){next_index=0}
				$('.digit:eq(' + (next_index) + ')').trigger('focus');
				updateInputBinary();
			}
		}
	});
});

</script>
<style>
#bin_table{
	margin:auto;
}
#bin_table TD,
#bin_table TD INPUT,
#in_dec {
	text-align:center;
}
#bin_table TD{
	padding : 3px 2px;
}
#bin_table TD INPUT{
	padding : 5px 3px;
}
#bin_table .tr_1 TD{
	font-size:12px;
}
#bin_table .tr_2 TD{
	font-size:10px;
	color: grey;
}
</style>
<H1 class='text-center'>DMX Address Converter</h1>
<div class='jumbotron'>

<div class='row'>
	<div class='col-xs-7 col-sm-8 text-center'>
		<b>DIP Switches</b>
	</div>
	<div class='col-xs-2'>
	</div>

	<div class='col-xs-3 col-sm-2 text-center'>
		<b>Address</b>
	</div>
</div>
<div class='row'>
	<div class='col-xs-7 col-sm-8 text-center'>
		<table id='bin_table'>
			<tr class='tr_1'>
				<td>1</td>
				<td>2</td>
				<td>3</td>
				<td>4</td>
				<td>5</td>
				<td>6</td>
				<td>7</td>
				<td>8</td>
				<td>9</td>
			</tr>
			<tr class=''>
				<td><input type="text" class="form-control input-sm digit" size=1 name="digit[8]" value=0 maxlength="1"></td>
				<td><input type="text" class="form-control input-sm digit" size=1 name="digit[7]" value=0 maxlength="1"></td>
				<td><input type="text" class="form-control input-sm digit" size=1 name="digit[6]" value=0 maxlength="1"></td>
				<td><input type="text" class="form-control input-sm digit" size=1 name="digit[5]" value=0 maxlength="1"></td>
				<td><input type="text" class="form-control input-sm digit" size=1 name="digit[4]" value=0 maxlength="1"></td>
				<td><input type="text" class="form-control input-sm digit" size=1 name="digit[3]" value=0 maxlength="1"></td>
				<td><input type="text" class="form-control input-sm digit" size=1 name="digit[2]" value=0 maxlength="1"></td>
				<td><input type="text" class="form-control input-sm digit" size=1 name="digit[1]" value=0 maxlength="1"></td>
				<td><input type="text" class="form-control input-sm digit" size=1 name="digit[0]" value=0 maxlength="1"></td>
			</tr>
			<tr class='tr_2'>
				<td>1</td>
				<td>2</td>
				<td>4</td>
				<td>8</td>
				<td>16</td>
				<td>32</td>
				<td>64</td>
				<td>128</td>
				<td>256</td>
			</tr>
		</table>
		<input type="hidden" class="" placeholder="" size=10 id="in_bin" readonly>
	</div>

	<div class='col-xs-2 text-center'>
		<br>
		<big><span class="glyphicon glyphicon-arrow-left"></span><span class="glyphicon glyphicon-arrow-right"></span></big>
	</div>

	<div class='col-xs-3 col-sm-2'>
		<br>
		<input type="text" class="form-control input-sm" placeholder="" id="in_dec" size=3>
	</div>
</div>
</div>

{$cfg['html_foot']}
EOF;

?>