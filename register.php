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
$converter = "../choga_converter.py";

$nID		= trim($_POST["songID"]);
$key		= trim($_POST["key"]);
$capo		= trim($_POST["capo"]);
$title		= trim($_POST["title"]);
$subtitle	= trim($_POST["subtitle"]);
$musician	= trim($_POST["musician"]);
$content	= trim($_POST["content"]);
// echo "$nID|$musician|$title|$subtitle|$content";
//echo "$key|$capo\n";

// Field Format
if( $subtitle != "") $subtitle = "{subtitle:$subtitle}\n";
if( $key != "") $key = "{key:$key}\n";
if( $capo != "") $capo = "{capo:$capo}\n";

// Make TXT
if(!WriteList("$nID.txt", "{title:$title}\n$subtitle$key$capo{musician:$musician}\n\n\n$content\n"))
{
	echo "Failed to write a text file";
	exit;
}

// Delete Old Song with Same ID in List
exec("awk -vid=$nID '{if($1!=id)print;}' $listFile > .temp ; mv .temp $listFile");

// Append New Song in List
$cmd = "echo \"$nID\t$musician\t$title\" >> $listFile";
//echo "$cmd<br>";
exec($cmd);

echo "$musician - $title Saved.";

?>
