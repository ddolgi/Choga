<html><head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
	<title>Choga</title>
	<link rel='stylesheet' type='text/css' href='../choga.css'>
	<script src="../jquery.min.js"></script>
	<script src="../choga.js"></script>
</head>
<body>
<button onclick='javascript:ToggleColumn("dadan");'>다단</button>
<button onclick="javascript:TransposeAll(1);" >#</button>
<button onclick="javascript:TransposeAll(-1);" >b</button>
<button onclick="javascript:ToggleLyric();" >Lyric</button>
<?php

$converter = "./choga.py";

$id= trim($_GET["id"]);

// echo "$nID|$musician|$title|$subtitle|$content";
//echo "$key|$capo\n";

// Convert TXT to HTML
$handle = popen("$converter < data/$id.choga", "r");
echo stream_get_contents($handle);
pclose($handle);

?>