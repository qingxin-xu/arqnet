var arqUtils = {
	/**
	 * Date formats for birthday drop downs
	 */
	ArqDateFormats:{
		js:'mm/dd/yyyy',
		php:'m/d/Y'
	},
	/**
	 * Remove HTML tags and return just the text
	 * Thanks to: http://stackoverflow.com/questions/822452/strip-html-from-text-javascript
	 */
	strip:function(html)
	{
	   var tmp = document.createElement("DIV");
	   tmp.innerHTML = html;
	   return tmp.textContent || tmp.innerText || "";
	},

	createTooltip:function(element,content,target) {
		if (!element) return;
		if (!element.qtip) return;
		var myTip = element.qtip({
			   content:{text:content},
			   hide:{
				   fixed:true,
				   delay:100
			   },
			   style: { 
			      //width: 200,			
			      padding: 5,
			      background: '#181818',
			      color: '#FFFFFF',
			      textAlign: 'center',
			      border: {
			         width: 17,
			         radius: 5,
				 
			         color: '#181818'
			      },
			      tip: 'topLeft',
			      //name: 'dark' // Inherit the rest of the attributes from the preset dark style
			   },
			   show:'mouseover',
			   position:{
			   	target:element,
			   	corner: {
			   		target: target||'topCenter'
			   	}
			}
		});		
	},
	
	setAutocomplete:function(selector,service) {
		if (!selector || !service) return;
	    $( selector ).autocomplete({
	        source: function( request, response ) {
	          $.ajax({
	            url:service,
	            dataType: "json",
	            data: {
	              term: request.term
	            },
	            success: function( data ) {
	              response( data );
	            }
	          });
	        },
	        minLength: 3
	    });
	}
}

$(document).ready(function() {
	var powerbarWidth = 293,
		offset = -10;
	
	if (powerbar) {
		
		if (powerbar<61) {
			$('.progressBar').addClass('progress-bar-nosuccess');
		} else {
			$('.progressBar').addClass('progress-bar-success');
		}
		powerbar = Math.round(powerbar)/100;
		powerbar = powerbar*powerbarWidth;
		if (powerbar>(powerbarWidth-2)) {
			$('.progressBar').css('border-radius','10px')
		}
		setTimeout(function() {
		$('.progress-bar').animate({
			width:powerbar+'px'
		},1000);
		},1);
	}
});

function arqIsArray(data) {
	if (data) {
		if (Object.prototype.toString.call( data ) === '[object Array]' ) return true;
		else return false;
	}
	return false
}