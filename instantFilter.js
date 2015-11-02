$(document).ready(function()
{
	$('#searchID').keyup(function() { filter($(this).val(),"td.idCell"); });
	$('#searchMusician').keyup(function() { filter($(this).val(),"td.musicianCell"); });
	$('#searchTitle').keyup(function() { filter($(this).val(),"td.titleCell"); });
});

function filter(inputVal, dataID)
{
	var table = $('#tblData');
	table.find('tr').each(function(index, row)
	{
		var allCells = $(row).find(dataID);
		if(allCells.length > 0)
		{
			var found = false;
			allCells.each(function(index, td)
			{
				var regExp = new RegExp(inputVal, 'i');
				if(regExp.test($(td).text()))
				{
					found = true;
					return false;
				}
			});
			if(found == true)$(row).show();else $(row).hide();
		}
	});
}
