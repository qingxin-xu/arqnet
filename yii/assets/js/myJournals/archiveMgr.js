var archiveMgr = {
	months:['January','February','March','April','May','June','July','August','September','October','November','December'],
	archive:null,
	createArchvie:function() {
		if (!journalDates) return;
		for (var year in journalDates) {
			if (!this.archive) this.archive = [];
			var entry = {};
			entry= {label:year};
			for (var month in journalDates[year]) {
				if (!entry['children']) entry['children'] = [];
				var nJournals = 0;
				for (var day in journalDates[year][month]) {
					nJournals = nJournals + journalDates[year][month][day];
				}
				entry['children'].push({
					label:this.months[parseInt(month)-1]+' ('+nJournals+')',
					year:year,
					month:month
				});
			}
			this.archive.push(entry);
		}
	},
	
	renderArchive:function(placeHolder) {
		if (!this.archive) {return;}
		
		var self = this;
		
	    $(placeHolder).tree({
	        data: this.archive
	    });
	    
	    $(placeHolder).tree().bind('tree.click',function(e) {
	    	console.log('select',e.node.year,e.node.month);
	    	self.query = {
	    		year:e.node.year||'',
	    		month:e.node.month||''
	    	};
	    	self.getData({data:self.query},function(entries) {
	    		self.currentPage = 1;
	    		self.display(entries);
	    		self.entries = entries.data;
	    		self.nEntries = entries.count||0;
	    		
	    		$('.currentPage').html('Current Page: '+1);
				$('button.next').unbind('click');

				$('button.previous').unbind('click');
				$('button.next').on('click',function(e) {
					console.log('click',self);
					self.nextPage();
				});

				$('button.previous').on('click',function(e) {
					self.previousPage();
				});
	    	});
	    });
	}
}
