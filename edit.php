<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel='stylesheet' type='text/css' href='../list.css'>
	<script src="../jquery.min.js"></script>
	<style>
	body,form,table,td{
		margin:0px;
		padding:0px;
		border-collapse:collapse;
	}
	table.window{
		width:100%
	}
	td.left{
		padding:5px;
		width:500;
	}
	iframe{
		border-top:1px;
	}
	.mainContent {
		float:left;
		width:100%;
		height:100%;
	}
	</style>
</head>
<script>
function register(form)
{
	var requiredList = ["songID", "title", "musician"];
	var param = {};
	for (field in form.elements)
	{
		var element = form.elements[field];
		//alert("["+element.name+"]");
		if( element.value == "" && requiredList.indexOf(element.name) >= 0 )
		{
			alert("Please input "+ element.name);
			form[element.name].focus();
			return;
		}
		param[element.name] = element.value;
	}

	$.post('register.php', param, function(response)
	{ 
		alert(response);
		$('#outputHTML').attr('src' , "choga.php?id=" + param.songID );
	});
}
$(document).ready(function(){
	var keyList = ["C", "C#", "D", "Eb", "E", "F", "F#", "G", "G#", "A", "Bb", "B"];
	for(var i in keyList )
		$("#key").append("<option value=\""+ keyList[i] +"\">"+ keyList[i] +"</option>\n");
	for(var i = 0 ; i < 12 ; ++i )
		$("#capo").append("<option value=\""+ i +"\">"+ i +"</option>\n");
});

</script>
<body>
<form>
<table class=window><tr><td class=left>
		<table class=input> <tr>
			<td>
<?php
$id = trim($_GET["id"]);
//$id="50";
$handle = fopen("$id.txt", "r");

while($line = fgets($handle))
{
	if( $line[0]!= '{' )	break;
	$field = strtok(trim($line, " \t\n{}"), ":");
	$value = strtok(":");

	if( $field == "title" ) 		$title = $value;
	else if( $field == "subtitle" ) $subtitle = $value;
	else if( $field == "musician" ) $musician = $value;
	else if( $field == "key" ) 		$key = $value;
	else if( $field == "capo" ) 	$capo = $value;
}
$content = stream_get_contents($handle);
fclose($handle);

if ($content == "")
{
	$handle = fopen("../example.txt", "r");
	$content = stream_get_contents($handle);
	fclose($handle);
}

echo ("				ID <input type=text name=songID value='$id' size=5>\n");
echo ("				음악가 <input type=text name=musician value='$musician'  size=15></td>\n");
echo ("			<td>제목 </td><td><input type=text name=title value='$title' size=30></td></tr>\n");
echo ("		</tr><tr>\n");
echo ("			<td>\n");
echo ("		   		Key  <select name=key id=key><option value=\"$key\">$key</option></select>\n");
echo ("				Capo  <select name=capo id=capo><option value=\"$capo\">$capo</option></select>\n");
echo ("			</td>\n");
echo ("			<td>부제 </td><td><input type=text name=subtitle value='$subtitle' size=30> </td>\n");
echo ("		</tr><tr>\n");
echo ("			<td colspan=3> <TEXTAREA name=content cols=80 rows=50>\n$content\n</TEXTAREA> </td>\n");
?>
		</tr> </table>
</td><td width=50>
	<INPUT TYPE="button" value='Register' onclick='register(this.form);'>
</td><td>
		<iframe class=mainContent id='outputHTML'></iframe>
</td></tr></table>
</form>
</body>
</html>
