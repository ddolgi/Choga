<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width, height=device-height">
	<link rel='stylesheet' type='text/css' href='choga.css'>
	<script src="jquery.min.js"></script>
	<script src="choga.js"></script>
	<title>Choga</title>
</head>
<body>
<button onclick='javascript:ToggleColumn("dadan");'>다단</button>
<button onclick="javascript:TransposeAll(1);" >#</button>
<button onclick="javascript:TransposeAll(-1);" >b</button>
<button onclick="javascript:ToggleLyric();" >Lyric</button>
<?php

$converter = "./choga.py";

$id= trim($_GET["id"]);

// Convert TXT to HTML
$cmd= "$converter < data/$id.choga";
//echo "$cmd\n";
$handle = popen($cmd, "r");
echo stream_get_contents($handle);
pclose($handle);

?>
</body>
</html>
