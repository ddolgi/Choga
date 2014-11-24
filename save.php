<?php

function WriteList( $fileName, $content)
{
	if($content== "") 
	{
		echo "no content";
		return FALSE;
	}

	$handle = fopen($fileName, 'w');
	if ($handle === FALSE)
	{
		echo "open error";
		return FALSE;
	}

	$nWrite = fwrite($handle, $content);
	fclose($handle);
	if ($nWrite	=== FALSE)
	{
		echo "open error";
 		return FALSE;
	}
	return TRUE;
}

//$now = date("Y-m-d H:i:s");
$listFile = "list.tsv";

$nID		= trim($_POST["songID"]);
$key		= trim($_POST["key"]);
$capo		= trim($_POST["capo"]);
$title		= trim($_POST["title"]);
$subtitle	= trim($_POST["subtitle"]);
$musician	= trim($_POST["musician"]);
$content	= trim($_POST["content"]);
// echo "$nID|$musician|$title|$subtitle|$content";
//echo "$key|$capo\n";

// Check ID
$line = exec("cat $listFile |grep -w '^$nID'");
if( $line != "" )
{
	$tokens = split("\t", $line);
	if( $tokens[2] != $musician || $tokens[3] != $title )
	{
		echo "Failed: Another Song with same ID";
		exit(-1);
	}
}

//function GetUserName() {
	//$uri = trim($_SERVER["REQUEST_URI"], '/');
	//return substr($uri, strrpos($uri, "/")+1);
//}
function GetUserName() {
	$uri = $_SERVER["REQUEST_URI"];
	$tokens = explode('/', $uri);
	return $tokens[count($tokens) -2];
}

$user = GetUserName();
// Field Meta-Information Format
if( $subtitle != "") $subtitle = "{subtitle:$subtitle}\n";
if( $key != "") $key = "{key:$key}\n";
if( $capo != "") $capo = "{capo:$capo}\n";

// Make TXT
if(!WriteList("data/$nID.choga", "{title:$title}\n$subtitle$key$capo{musician:$musician}\n\n\n$content\n"))
{
	echo "Failed to write a text file";
	exit(-1);
}

if( "" == trim(exec("grep -w '^$nID' $listFile"))) 
{
	$cmd = "echo \"$nID\t$user\t$musician\t$title\" >> $listFile";
	//echo "$cmd<br>";
	exec($cmd);
}

echo "$musician - $title Saved.";

?>
