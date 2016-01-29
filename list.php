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
<table id=tblData class=list>
<thead>
	<td> ID </td>
	<td><input type="text" id="searchMusician" placeholder="음악가" size=10></td>
	<td><input type="text" id="searchTitle" placeholder="제목" size=10></td>
	<td align=right><a href="edit.php" target=_blank> <INPUT TYPE='button' value='New'></a> </td>
</thead>

<?php

function GetUserName() {
	$uri = trim($_SERVER["REQUEST_URI"], '/');
	return substr($uri, strrpos($uri, "/")+1);
}

function PrintItem($line, $userName)
{
	if ( $line == "") return;
	list($nID, $user, $musician, $title) = split('	', $line);

	echo "<tr>
	<td class='idCell'>$nID</td>
	<td class='musicianCell'>$musician</td>
	<td class='titleCell'>$title</td>
	<td align=right>\n";
	if(strpos($_SERVER['HTTP_USER_AGENT'], "iPhone") === false && $user == $userName ) 
		echo "\t\t<a href='edit.php?id=$nID' target=_blank><INPUT TYPE='button' value='Edit'></a>\n";
	echo "\t\t<a href='view.php?id=$nID' target=$nID ><INPUT TYPE='button' value='View'></a>\n";
   	echo "	</td>\n";
	echo "</tr>\n";
}


$listFile	= "data/list.tsv";
$user = GetUserName();

$handle = fopen("$listFile", "r");
while($line = fgets($handle))
	PrintItem(rtrim($line), $user);
pclose($handle);
#echo $_SERVER['HTTP_USER_AGENT'];
?>
</table>
</body>
</html>

