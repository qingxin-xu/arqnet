var calendarMgr = {
	journalDates:null,
	calendarDates:null,
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
	
	setDates:function(dates) {
		if (!dates) return;
		for (var year in dates) {
			for (var month in dates[year]) {
				//for (var day = 0;day< journalDates[year][month].length;day++) {
				for (var day in dates[year][month]) {
					var str = year+'-'+month+'-'+dates[year][month][day];
					if (!this.calendarDates) this.calendarDates = [];
					this.calendarDates.push(new Date(year,month-1,day).valueOf());
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
	},
	
	isAllowedCalendarDate:function(date) {
		if (!this.calendarDates) return '';
		if (!date) return '';
		var dateVal = date.valueOf();
		for (var i = 0;i<this.calendarDates.length;i++) {
			if (dateVal == this.calendarDates[i]) return '';
		}
		return 'disabled';
	},
	
	createDatesFromDashboardEvents:function(dashboardActivity,trackerActivity) {
		var dates = {},
			tmp,
			year,
			month,
			day,
			date,
			activities = ['top_people','top_categories','top_words'];
		for (var i = 0;i<dashboardActivity.length;i++) {
			for (var a = 0;a<activities.length;a++) {
				var toParse = false;
				if (!arqIsArray(dashboardActivity[i][activities[a]])) {
					toParse = true;
					break;
				}
			}
			if (toParse) {
				date = dashboardActivity[i]['date'];
				this.parseDateStr(dates, date);
			}
		}
		for (var event in trackerActivity) {
			for (var d in trackerActivity[event]) {
				for (var date in trackerActivity[event][d]) {
					this.parseDateStr(dates, date);
				}
			}
		}
		return dates;
	},
	
	parseDateStr:function(dates,date) {
		var tmp,
			year,
			month,
			day;
		tmp = date.split(/-/);
		year = tmp[0];
		month = tmp[1];
		day = tmp[2];
		if (!dates[year]) {
			dates[year] = {};
			dates[year][month] = {};
			dates[year][month][day]=1;
		} else  {
			if (!dates[year][month]) {
				dates[year][month] = {};
				dates[year][month][day]=1;
			} else {
				if (!dates[year][month][day]) {
					//dates[year][month][day] = {}
					dates[year][month][day] = 1;
				} else {
					dates[year][month][day] = dates[year][month][day]+1;
				}
			}
		}		
	}
};