<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Choga: List</title>
	<link rel='stylesheet' type='text/css' href='../list.css'>
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width, height=device-height">
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
</head>
<body>
<table> <tr> <td valign=top>
	<table class=list>
	<thead>
		<td> ID </td>
		<td> 음악가 </td>
		<td> 제목 </td>
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
		<td>$nID</td>
		<td>$musician</td>
		<td>$title</td>
		<td align=right>";
	if(strpos($_SERVER['HTTP_USER_AGENT'], "iPhone") === false && $user == $userName ) 
		echo "<a href='edit.php?id=$nID' target=_blank><INPUT TYPE='button' value='Edit'></a>";
	echo " <a href='view.php?id=$nID' target=$nID ><INPUT TYPE='button' value='View'></a> </td>
	</tr>\n";
}


$listFile	= "list.tsv";
$user = GetUserName();

$handle = fopen("$listFile", "r");
while($line = fgets($handle))
	PrintItem($line, $user);
pclose($handle);
#echo $_SERVER['HTTP_USER_AGENT'];
?>
	</table>
</body>
</html>

