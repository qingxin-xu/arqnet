var calendarMgr = {
	journalDates:null,
	setJournalDates:function() {
		if (!journalDates) return;
		for (var year in journalDates) {
			for (var month in journalDates[year]) {
				//for (var day = 0;day< journalDates[year][month].length;day++) {
				for (var day in journalDates[year][month]) {
					var str = year+'-'+month+'-'+journalDates[year][month][day];
					if (!this.journalDates) this.journalDates = [];
					this.journalDates.push(new Date(year,month-1,day).valueOf());
				}
			}
		}
	},
	
	isAllowedDate:function(date) {
		if (!this.journalDates) return '';
		if (!date) return '';
		var dateVal = date.valueOf();
		for (var i = 0;i<this.journalDates.length;i++) {
			if (dateVal == this.journalDates[i]) return '';
		}
		return 'disabled';
	}
};