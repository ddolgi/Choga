<?php

//$now = date("Y-m-d H:i:s");
$listFile = "data/list.tsv";

$id			= trim($_POST["songID"]);
$original	= trim($_POST["original"]);
$key		= trim($_POST["key"]);
$title		= trim($_POST["title"]);
$subtitle	= trim($_POST["subtitle"]);
$musician	= trim($_POST["musician"]);
$content	= trim($_POST["content"]);
// echo "$id|$musician|$title|$subtitle|$content";
// echo "$key|$original\n";

function GetUserName() {
	$uri = $_SERVER["REQUEST_URI"];
	$tokens = explode('/', $uri);
	return $tokens[count($tokens) -2];
}

$userInList = trim(shell_exec("grep -w $id $listFile | cut -f2"));
$user = GetUserName();
if($userInList != "" && $userInList != $user)
{
	echo "Authorization Error";
	exit(1);
}

// Check ID
$line = exec("cat $listFile |grep -w '^$id'");
if( $line != "" )
{
	$tokens = split("\t", $line);
	if( $tokens[2] != $musician || $tokens[3] != $title )
	{
		echo "Failed: Another Song with same ID";
		exit(-1);
	}
}

function WriteList( $fileName, $doc)
{
	$handle = fopen($fileName, 'w');
	if ($handle === FALSE) return FALSE;

	$nWrite = fwrite($handle, $doc);
	fclose($handle);
	return $nWirte;
}

$doc  = "{ \"title\":\"$title\"";
$doc .= ", \"subtitle\":\"$subtitle\"";
$doc .= ", \"original\":\"$original\"";
$doc .= ", \"key\":\"$key\"";
$doc .= ", \"musician\":\"$musician\"";
$doc .= "}\n\n$content\n";

// Make TXT
if( WriteList("data/$id.choga", $doc) === FALSE)
{
	echo "Failed to write a text file.";
	exit(-1);
}

if( "" == trim(exec("grep -w '^$id' $listFile"))) 
{
	$cmd = "echo \"$id\t$user\t$musician\t$title\" >> $listFile";
	//echo "$cmd<br>";
	exec($cmd);
}

echo "$musician - $title Saved.";

?>
