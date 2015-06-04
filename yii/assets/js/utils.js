var arqUtils = {
	
	/**
	 * Remove HTML tags and return just the text
	 * Thanks to: http://stackoverflow.com/questions/822452/strip-html-from-text-javascript
	 */
	strip:function(html)
	{
	   var tmp = document.createElement("DIV");
	   tmp.innerHTML = html;
	   return tmp.textContent || tmp.innerText || "";
	}
}

$(document).ready(function() {
	var powerbarWidth = 293,
		offset = -10;
	
	if (powerbar) {
		powerbar = Math.round(powerbar)/100;
		powerbar = powerbar*powerbarWidth;
		if (powerbar>(powerbarWidth-2)) {
			$('.progress-bar-success').css('border-radius','12px')
		}
		setTimeout(function() {
		$('.progress-bar').animate({
			width:powerbar+'px'
		},1000);
		},1000);
	}
});