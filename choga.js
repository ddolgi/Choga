var ToggleChord= function() {
	$('.chord').toggle();
}

var ToggleLyric = function() {
	$('.lyric').toggle();
}

var ToggleColumn = function(id) {
	oldTR = $('#'+id).children().children();
	var rows = oldTR.length;
	var cols = oldTR.children('td').length;
	var newTBODY = $('<tbody></tbody>');

	for(var i=0;i<cols;++i)
	{
		var newTR = $('<tr></tr>');
		for(var j=0;j<rows;++j)
		{
			oldTD = oldTR.eq(j).children('td').eq(0)
				//if( j==0)
					//newTD = oldTD.attr("class", "choga");
				//else
					//newTD = oldTD.attr("class", "choga2");
			//newTR.append(newTD);
			newTR.append(oldTD);
		}
		newTBODY.append(newTR);
	}

	$('#'+id).children("tbody").remove();
	$('#'+id).append(newTBODY);
};

var keyList = ["C", "C#", "D", "Eb", "E", "F", "F#", "G", "G#", "A", "Bb", "B"];

var TransposeChord = function(delta, key)
{
	var idx = delta + keyList.indexOf(key);
	if( idx <0) idx += 12;
	if( idx >=12 ) idx -= 12;
	return keyList[idx];
};

var TransposeAll = function(delta)
{
	$("td.chord").each(function()
	{
		$(this).text(function(idx, chord)
		{
			chord = chord.trim();
			if(chord == "") return " ";

			var n;
			if( chord[1]=="#" || chord[1]=="b" )
		   		n = 2;
			else
		   		n = 1;

			chord = TransposeChord(delta, chord.substr(0, n)) + chord.substr(n);

			n = chord.indexOf("/") + 1;
			if( n == 0 ) return chord;
				
			return chord.substr(0, n) + TransposeChord(delta, chord.substr(n));
		});
	});
	return true;
};

//$(document).ready(function(){
      //$("#width").keyup(function(event){
		//if(event.keyCode == 13)
			   //$(".phrase td").css('width', $("#width").val());
	//});
//});

//var ModifyWidth = function(delta){
	//var width = parseInt($(".phrase td").css('width'));
	//$(".phrase td").css('width', width + delta);
//};

//function toggle(id) {
	//var el = document.getElementById(id);
	//var img = document.getElementById("arrow");
	//var box = el.getAttribute("class");
	//if(box == "hide"){
		//el.setAttribute("class", "show");
		//delay(img, "../images/arrow_left.png", 400);
	//}
	//else{
		//el.setAttribute("class", "hide");
		//delay(img, "../images/arrow_right.png", 400);
	//}
//}

//function delay(elem, src, delayTime){
	//window.setTimeout(function() {elem.setAttribute("src", src);}, delayTime);
//}
