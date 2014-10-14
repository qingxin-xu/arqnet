var tagMgr = {
	
	renderTags:function(placeHolder) {
		if (!taggedNotes) {return;}
		
		var self = this;
		var html = '';
		for (var i in taggedNotes) {
			console.log(i);
			var tagHTML = this.tagTemplate;
			tagHTML = tagHTML.replace(/{TAG_NAME}/,i);
			tagHTML = tagHTML.replace(/{CONTENT}/,i+'('+taggedNotes[i]+')');
			html += tagHTML;
		}
		if (html) {
			$(placeHolder).html(html);
			$('.noteTag').click(function(e) {
				var tag = $(this).attr('id').replace(/^tag_/,'');
				self.query={tag:tag};
		    	self.getData({data:self.query},function(entries) {
		    		
		    		self.currentPage = 1;
		    		self.display(entries);
		    		self.entries = entries.data;
		    		self.nEntries = entries.count||0;
		    		
		    		$('.currentPage').html('Current Page: '+1);
					$('button.next').unbind('click');

					$('button.previous').unbind('click');
					$('button.next').on('click',function(e) {
						self.nextPage();
					});

					$('button.previous').on('click',function(e) {
						self.previousPage();
					});
					
		    	});
			});
			
		}
	},
	
	tagTemplate:[
	          "<h3 title='click to view these tagged entries' id='tag_{TAG_NAME}' class='word1 noteTag'>{CONTENT}</h3>"
	         ].join('')
	
		
};