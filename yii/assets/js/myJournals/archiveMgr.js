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
		
	    $(placeHolder).tree({
	        data: this.archive
	    });
	    
	    $(placeHolder).tree().bind('tree.click',function(e) {
	    	console.log('select',e.node.year,e.node.month);
	    });
	}
}
