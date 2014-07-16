<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Choga: Edit</title>
	<link rel='stylesheet' type='text/css' href='../choga.css'>
	<script src="../jquery.min.js"></script>
<style>
*
{
	font-size:13px;
	font-family:"malgun Gothic", 맑은 고딕, malgun,Dotum,tahoma,sans-serif;
	font-style:normal; font-weight:normal;
}

table.list
{ 
	border-collapse:collapse;
	width:100%;
	border:1px solid;
}

.list thead
{
	background-color: lightgray;
}

.list td
{
	border:1px solid;
	padding:3px;
}
</style>
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
var param = {};

function View(form) 
{
	var songID = form.elements["songID"].value;
	if(songID == "")
	{
		alert("Please input ID");
		return;
	}
	var win = window.open("show.php?id=" + songID , '_blank');
	win.focus();
}

function register(form)
{
	var requiredList = ["songID", "title", "musician"];
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
	{ alert(response); });
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
if($id != "")
{
	$handle = fopen("$id.choga", "r");
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
}
else
	$handle = fopen("../template.choga", "r");

$content = stream_get_contents($handle);
fclose($handle);

echo ("				ID <input type=text name=songID value='$id' size=5>\n");
echo ("				음악가 <input type=text name=musician value=\"$musician\"  size=15></td>\n");
echo ("			<td>제목 </td><td><input type=text name=title value='$title' size=30></td></tr>\n");
echo ("		</tr><tr>\n");
echo ("			<td>\n");
echo ("		   		Key  <select name=key id=key><option value=\"$key\">$key</option></select>\n");
echo ("				Capo  <select name=capo id=capo><option value=\"$capo\">$capo</option></select>\n");
echo ("			</td>\n");
echo ("			<td>부제 </td><td><input type=text name=subtitle value='$subtitle' size=30> </td>\n");
echo ("		</tr><tr>\n");
echo ("			<td colspan=3> <TEXTAREA name=content cols=100 rows=40>\n$content\n</TEXTAREA> </td>\n");
?>
		</tr> </table>
	<INPUT TYPE="button" value='Register' onclick='register(this.form);'>
	<INPUT TYPE="button" value='View' onclick='View(this.form);'>
</td></tr></table>
</form>
</body>
</html>

