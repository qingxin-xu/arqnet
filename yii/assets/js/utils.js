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