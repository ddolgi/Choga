<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Choga: Edit</title>
	<link rel='stylesheet' type='text/css' href='../choga.css'>
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width, height=device-height">
	<link rel="apple-touch-icon" href="choga.ico">
	<link rel="shortcut icon" href="choga.ico">
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
		body,form,table,td{
			margin:0px;
			padding:3px;
			border-collapse:collapse;
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
	<script src="jquery.min.js"></script>
	<script>
		function Merge(form)
		{
			output = "";

			chord_madis = form["chord"+form.chord.value].value.split('\n');
			lyric_madis = form["lyric"+form.lyric.value].value.split('\n');
			max_madis = Math.max(chord_madis.length, lyric_madis.length); 
			for(i=0;i<max_madis;++i)
			{
				chord_pieces = chord_madis[i]?chord_madis[i].split('|'):[];
				lyric_pieces = lyric_madis[i]?lyric_madis[i].split('|'):[];
				max_pieces = Math.max(chord_pieces.length, lyric_pieces.length); 
				for(j=0;j<max_pieces;++j)
				{
					output += "[";
					output += chord_pieces[j]?chord_pieces[j].trim():"";
					output += "]";
					output += lyric_pieces[j]?lyric_pieces[j].trim():"";
				}
				output += "|";
			}
			form.merged.value = output;
			//form.content.focus();
			//alert(output);
		}

		function Save(form, bAlert)
		{
			var param = {};
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

			$.post('save.php', param, function(response)
			{ if(bAlert) alert(response); });
		}

		function View(form) 
		{
			var songID = form.elements["songID"].value;
			if(songID == "")
			{
				alert("Please input ID");
				return;
			}
			var win = window.open("view.php?id=" + songID , songID);
			win.focus();
		}

		function changeChord(inField) {
			var name = inField.name.substring(5);
			var radioButton = document.getElementById("chord" + name+"btn");
			radioButton.checked = true;
		}

		function changeLyric(inField) {
			var name = inField.name.substring(5);
			var radioButton = document.getElementById("lyric" + name+"btn");
			radioButton.checked = true;
		}

		$(document).ready(function(){
			var keyList = ["C", "C#", "D", "Eb", "E", "F", "F#", "G", "G#", "A", "Bb", "B"];
			for(var i in keyList ) {
				$("#key").append("<option value=\""+ keyList[i] +"\">"+ keyList[i] +"</option>\n");
				$("#original").append("<option value=\""+ keyList[i] +"\">"+ keyList[i] +"</option>\n");
			}
		});
	</script>
</head>
<body>
<form>
<?php

//function get($var, $key) { return array_key_exists($key, $var) ? $var[$key] : ""; }
function get(&$var, $default) { return isset($var) ? $var : $default; }

function lookup_list($id, $filename)
{
	$handle = fopen($filename, "r");
	$ret = "";
	while($line = fgets($handle))
	{
//		PrintItem(rtrim($line), $user);
		if ( $line == "" || $line[0]=='#') continue;
		list($nID, $editor, $musician, $title) = split('	', $line);
		if ( $nID == $id )
		{
			$ret = $editor;
			break;
		}
	}
	fclose($handle);
	return $ret;
}

$id = trim(get($_GET["id"], ""));
$user =  trim(get($_GET["user"], ""));
if($id != "" && $user != lookup_list($id, "data/list.tsv"))
{
	echo "<font color=red>User '$user' cannot save this song.</font><br>\n";
}


$musician = "";
$title = "";
$subtitle = "";
$original = "";
$key = "";
if($id != "")
{
	$handle = fopen("data/$id.choga", "r");
	$header = json_decode(fgets($handle));

	$musician = $header->{'musician'};
	$title = $header->{'title'};
	$subtitle = $header->{'subtitle'};
	$original = $header->{'original'};
	$key = $header->{'key'};
}
else
{
	$handle = fopen("data/template.choga", "r");
	$id = rand(1000,9999);
	echo "<a href='http://ddolgi.pe.kr/choga/edit.php?id=1' target='_blank'>작성 예</a><br>\n";
}

$content = stream_get_contents($handle);
fclose($handle);

echo ("<table border=0 class=input> <tr>\n");
echo ("				<td>ID <input type=text name=songID value='$id' size=5></td>\n");
echo ("				<td>음악가 <input type=text name=musician value=\"$musician\"  size=15></td>\n");
echo ("			<td>제목 </td><td><input type=text name=title value='$title' size=30></td></tr>\n");
echo ("		</tr><tr>\n");
echo ("				<td>Original <select name=original id=original><option value=\"$original\">$original</option></select></td>\n");
echo ("		   		<td>Key  <select name=key id=key><option value=\"$key\">$key</option></select></td>\n");
echo ("			<td>부제 </td><td><input type=text name=subtitle value='$subtitle' size=30> </td>\n");
echo("</tr></table>\n");
echo("<input type=hidden id='editor' name='editor' value='$user'>\n");
?>

<table><tr>
	<td><input type="radio" name="chord" id="chord1btn" value="1" checked>Chord 1<br><TEXTAREA name=chord1 cols=8 rows=8 placeholder='c1 | c2
c3
chord4' onclick="changeChord(this);"></TEXTAREA></td>
	<td><input type="radio" name="chord" id="chord2btn" value="2">Chord 2<br><TEXTAREA name=chord2 cols=8 rows=8 onclick="changeChord(this);"></TEXTAREA></td>
	<td><input type="radio" name="chord" id="chord3btn" value="3">Chord 3<br><TEXTAREA name=chord3 cols=8 rows=8 onclick="changeChord(this);"></TEXTAREA></td>
	<td><input type="radio" name="chord" id="chord4btn" value="4">Chord 4<br><TEXTAREA name=chord4 cols=8 rows=8 onclick="changeChord(this);"></TEXTAREA></td>
	<td> + </td>
	<td><input type="hidden" name="lyric" id="lyric1btn" value="1" checked>Lyric<br><TEXTAREA name=lyric1 cols=52 rows=8 placeholder='lyric1 | lyric2
lyric3
lyric4' onclick="changeLyric(this);"></TEXTAREA></td>
</td></tr></table>

<p><INPUT TYPE=button value='Merge' onclick='Merge(this.form);'> <input type=text name=merged id=merged size=95 disabled>
<p>
<?
echo ("			<TEXTAREA id=content name=content cols=106 rows=25>\n$content\n</TEXTAREA>\n");
?>
<p>
<INPUT TYPE="button" value='Save' onclick='Save(this.form, true);'>
<INPUT TYPE="button" value='View' onclick='View(this.form);'>
<INPUT TYPE="button" value='Save & View' onclick='Save(this.form, false);View(this.form);'>
</form>
※ 오른쪽 정렬법: 코드를 비운다. '[]'
</body>
</html>

