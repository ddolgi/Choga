<html>
<head>
	<title>Choga</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name=”apple-mobile-web-app-capable” content=”yes” />
	<!--<link rel='stylesheet' type='text/css' href='../list.css'>-->
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width, height=device-height">
	<link rel="apple-touch-icon" href="choga.ico">
	<link rel="shortcut icon" href="choga.ico">
	<script src="jquery.min.js"></script>
	<script src="instantFilter.js"></script>
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
</head>
<body>
<form target='_self'>
<table id=tblData class=list>
<thead>
	<td> ID </td>
	<td><input type="text" id="searchMusician" placeholder="음악가" size=15></td>
	<td><input type="text" id="searchTitle" placeholder="제목" size=15></td>
	<td align=left><?php

//function get($var, $key) { return array_key_exists($key, $var) ? $var[$key] : ""; }
function get(&$var, $default) { return isset($var) ? $var : $default; }

$listFile = "data/list.tsv";
$user =  trim(get($_GET["user"], ""));

if($user != "")
	echo "<a href=\"edit.php?user=$user\" target=_blank> <INPUT TYPE='button' value='New'></a> ";
else
	echo "<INPUT TYPE='text' name='user' id='user' placeholder='사용자' size=8><input type='submit' value='Login'>";
echo "</td>
</thead>\n";

function PrintItem($line, $user)
{
	if ( $line == "" || $line[0]=='#') return;
	list($nID, $editor, $musician, $title) = split('	', $line);

	echo "<tr>
	<td class='idCell'>$nID</td>
	<td class='musicianCell'>$musician</td>
	<td class='titleCell'>$title</td>
	<td align=left>\n";
	echo "\t\t<a href='view.php?id=$nID' target=$nID ><INPUT TYPE='button' value='View'></a>\n";
	if(strpos($_SERVER['HTTP_USER_AGENT'], "iPhone") === false && $editor == $user ) 
		echo "\t\t<a href='edit.php?id=$nID&user=$user' target=_blank><INPUT TYPE='button' value='Edit'></a>\n";
   	echo "	</td>\n";
	echo "</tr>\n";
}

$handle = fopen($listFile, "r");
$songs = array();
while($line = fgets($handle))
	array_push($songs, $line);
foreach(array_reverse($songs) as $line)
	PrintItem(rtrim($line), $user);
fclose($handle);
#echo $_SERVER['HTTP_USER_AGENT'];
?>
</table>
</form>
</body>
</html>

