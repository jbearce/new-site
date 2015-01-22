$(document).ready(function() {
	$("iframe").each(function() {
		var height = $(this).attr("height");
		var width = $(this).attr("width");
		var aspectRatio = (height / width) * 100 + "%";
		$(this).wrap("<span class=\"iframe\" style=\"padding-bottom:" + aspectRatio + "\">");
	});
});