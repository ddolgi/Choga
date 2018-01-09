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
$editor	= trim($_POST["editor"]);
// echo "$id|$musician|$title|$subtitle|$content";
// echo "$key|$original\n";

function LookupList($id, $filename)
{
	$handle = fopen($filename, "r");
	$ret = "";
	while($line = fgets($handle))
	{
//		PrintItem(rtrim($line), $user);
		if ( $line == "" || $line[0]=='#') continue;
		list($nID, $editor, $musician, $title) = explode('\t', $line);
		if ( $nID == $id )
		{
			$ret = $line;
			break;
		}
	}
	fclose($handle);
	return $ret;
}

$tsv = LookupList($id, $listFile);
list($prevID, $prevEditor, $prevMusician, $prevTitle) = explode('\t', $tsv);
if($prevEditor != "")
{
	if( $prevEditor != $editor )
	{
		echo "Authorization Error: user $editor cannot edit";
		exit(1);
	}
	else if( trim($prevMusician) != $musician || trim($prevTitle) != $title )
	{
		echo "Failed: Another Song with same ID";
		exit(-1);
	}
}

function WriteSong($fileName, $doc)
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
$doc .= ", \"editor\":\"$editor\"";
$doc .= "}\n\n$content\n";

// Make TXT
if( WriteSong("data/$id.choga", $doc) === FALSE)
{
	echo "Failed to write a text file.";
	exit(-1);
}

if( $tsv == "" ) 
{
	$handle = fopen($listFile, 'a');
	if ($handle === FALSE) return FALSE;

	$nWrite = fwrite($handle, "$id\t$editor\t$musician\t$title\n");
	fclose($handle);
}

echo "$musician - $title Saved.";

?>
