<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width, height=device-height">
	<link rel='stylesheet' type='text/css' href='choga.css'>
	<link rel="apple-touch-icon" href="choga.ico">
	<link rel="shortcut icon" href="choga.ico">
	<script src="jquery.min.js"></script>
	<script src="choga.js"></script>
	<title>Choga</title>
</head>
<body>
<button onclick='javascript:ToggleColumn("dadan");'>다단</button>
<button onclick="javascript:TransposeAll(1);" >#</button>
<button onclick="javascript:TransposeAll(-1);" >b</button>
<button onclick="javascript:ToggleChord();" >Chord</button>
<button onclick="javascript:ToggleLyric();" >Lyric</button>
<?php

$id= trim($_GET["id"]);

if(strpos($_SERVER['HTTP_USER_AGENT'], "iPhone") === false ) 
	$bColumn = TRUE;

function ParsePhrase($line)
{
	$phrase = array();
	$madis = explode("|", $line);
	foreach($madis as $madiTxt)
	{
		$madiTxt = trim($madiTxt);
		if($madiTxt == "") continue;

		$madi = array();
		$pieces = explode("[", $madiTxt);
		foreach($pieces as $piece)
		{
			$piece = trim($piece);
			if( $piece == "") continue;
			$idx = strpos($piece,"]");
			if($idx === FALSE)
				array_push($madi, array("chord"=>"", "lyric"=>$piece));
//				array_push($madi, array("chord"=>"", "lyric"=>substr($piece,1)));
			else
				array_push($madi, array("chord"=>substr($piece,0,$idx), "lyric"=>substr($piece,$idx+1)));
		}
		array_push($phrase, $madi);
	}
	return $phrase;
}

function array_key_valid($key, $a)
{
	return array_key_exists($key, $a) && trim($a->$key) !== "";
}

$cmt_header = "{c";

$handle = fopen("data/$id.choga", "r");
$metaInfo = json_decode(fgets($handle));

$maxMadi = 0;
$lines = array();
while($line = fgets($handle))
{
	$line = trim($line);
	if($line == "" or $line[0]=="#") continue;

	if($line[0] == "{")
	{
		if($line == "{column}")
			array_push($lines,array("type"=>"column"));
		else if(substr($line,0,2) === "{c")
		{
			$idx = strpos($line,":");
			$text = trim(substr($line,$idx+1,strlen($line)-2-$idx));
			array_push($lines,array("type"=>"comment","text"=>$text));
		}
	}
	else
	{
		$phrase = ParsePhrase($line);
		$maxMadi = max( $maxMadi, sizeof($phrase) );
		array_push($lines, $phrase);
	}
}
fclose($handle);

//var_dump($lines);

///// PRINT
echo "<table id=dadan class=dadan><tr><td class=choga>\n";
echo "<h1>$metaInfo->title</h1>\n";
if(array_key_valid( "subtitle", $metaInfo))
	echo "<h2>- $metaInfo->subtitle -</h2>\n";
echo "<table border=0 width=100%><tr><td class=key>\n";
echo "<table><tr>\n";
if(array_key_valid("original", $metaInfo))
	echo "<td> Origianl: </td><td>$metaInfo->original</td>\n";
if(array_key_valid("key", $metaInfo))
	echo "<td>Key: </td><td class=chord>$metaInfo->key</td>\n" ;
echo "</tr></table>\n";
echo "</td><td class=musician> $metaInfo->musician</td>";
echo "</tr></table>\n<hr>\n";

$width = 100 / $maxMadi;
foreach($lines as $line)
{
	if(array_key_exists("type", $line))
	{
		$line_type = $line["type"];
//		echo "<br>$phrase_type<br>\n";
		if($line_type == "comment")
		{
			echo $line["text"]."<br>";
		}
		else if($line_type == "column")
		{
			if($bColumn)
				echo "</td><td class=choga>";	// multi-column
			else
				echo "</td></tr><tr><td class=choga>";	// 1-column
		}
		continue;
	}
	elseif(sizeof($line) == 1 && $line[0][0]["chord"] === "")
		echo $line[0][0]["lyric"]."<br>";
	else
	{
		$nMadi = sizeof($line);
		echo "<table class=phrase width=".($width * $nMadi)."%><tr>\n";
		foreach($line as $madi)
		{
			echo "<td width=".(100/$nMadi)."%><table class=madi><tr>\n";
			foreach($madi as $piece)
			{
				echo "	<td class=chord>".$piece["chord"]."</td>\n";
			}
			echo "</tr><tr>\n";
			foreach($madi as $piece)
			{
			//	#print("	<td> %s</td>"% piece["lyric"],end="")
				if($piece["chord"]!="")
					echo "	<td class=lyric>".$piece["lyric"]."</td>\n";
				else
					echo "	<td align=right class=lyric>".$piece["lyric"]."</td>\n";
			}
			echo "</tr></table></td>\n"; //madi
		}
		echo "</tr></table>\n"; //phrase
	}
}
echo "</td></tr></table>\n"; //dadan

?>

</body>
</html>
