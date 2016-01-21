var journalMgr = {
	placeholder:null,
	entries:[],
	nEntries:0,
	entriesPerPage:5,
	currentPage:1,
	pagingService:null,
	query:{},
	
	display:function(entries,placeholder) {
		if (!entries) return;
		if (!entries.data || !entries.data.length || entries.data.length<=0) return;
		if (!this.placeholder && !placeholder) return;
		if (placeholder) this.placeholder = placeholder;
		
		var allHTML = '';
		var data = entries.data;
		for (var i = 0;i<data.length;i++) {
			var html = this.template;
			html = this.displayDate(html,data[i]);
			if (data[i].id) html = html.replace(/{ID}/,data[i].id);
			if (data[i].title) html = html.replace(/{TITLE}/,data[i].title);
			else html = html.replace(/{TITLE}/,'');
			if (data[i].content) html = html.replace(/{CONTENT}/,data[i].content);
			else html = html.replace(/{CONTENT}/,'');
			if (data[i].visibility) html = html.replace(/{VISIBILITY}/,data[i].visibility);
			else html = html.replace(/{VISIBILITY}/,'');
			if (data[i].id) html = html.replace(/{JOURNAL_ID}/,data[i].id);
			allHTML += html
		}
		$(this.placeholder).html();
		$(this.placeholder).html(allHTML);
		$('button.edit').on('click',function(e) {
			var id = $(this).attr('id');
			if (id) {
				id = id.replace(/Journal_/,'');
				if (id) {
					window.location.href='/journal?journal_id='+id;
				}
			}
		});
		for (var i = 0;i<data.length;i++) {
			if ($('#entry_'+data[i].id+' img') && $('#entry_'+data[i].id+' img').length>0) {
				this.createColorBox('entry_'+data[i].id);
			}
		}
	},
	
	createColorBox:function(id) {
		if (!id) return;
		//var html = "<div style='height:100%;cursor:pointer;width:100%;'>";
		$('#'+id+' img').each(function(i,item) {
			$(this).wrap("<a class='gallery_"+id+"' href='"+$(this).attr('src')+"'></a>");
			//html += "<a href='"+item.src+"' class='gallery_"+id+"'></a>";
		});
		//html += "</div>";
		//$('#'+id).append(html);
		$('a.gallery_'+id).colorbox();
	},
	
	displayDate:function(html,datum) {
		if (!html) return;
		if (!datum) return;
		if (!datum.publish_date) return;
		var myD,
			_date = '',
			_time = '',
			inputDate = datum.publish_date.replace(/\s/,'T'),
			tzOffset = new Date().getTimezoneOffset()*60*1000;
			
		if (inputDate) {
			myD = new Date(inputDate);
			myD.setTime(myD.getTime()+tzOffset);
			_date = myD.toLocaleDateString();
			//_time = myD.toLocaleTimeString('en-us',{minute:'2-digit',hour:'2-digit'});
		}
		if (datum.publish_time) {
			_time = datum.publish_time;
		}
		html = html.replace(/{DATE}/,_date);
		html = html.replace(/{TIME}/,_time);
		return html;	
	},
	
	clearData:function() {
		this.entries = [];
		this.nEntries = 0;
	},
	
	nextPage:function() {
		if (!this.currentPage) return;
		if (!this.entriesPerPage) return;
		if (!this.pagingService) return;
		
		var self = this;
		var entriesDisplayed = this.currentPage*this.entriesPerPage;
		if (entriesDisplayed>=this.nEntries) return;
		
		var data = {
			offset:entriesDisplayed,
			limit:this.entriesPerPage
		};
		for (var i in this.query) {
			data[i] = this.query[i];
		}
		this.getData({data:data},function(entries) {
			self.currentPage = self.currentPage+1;
			self.display(entries);
			self.entries = self.entries.concat(entries.data);
			$('.currentPage').html('Current Page: '+self.currentPage);
		});
	},
	
	previousPage:function() {
		if (!this.currentPage) return;
		if (!this.entriesPerPage) return;
		if (!this.entries) return;
		var self = this;
		var entriesDisplayed = this.currentPage*this.entriesPerPage;
		if (entriesDisplayed<=this.entriesPerPage) return;
		this.currentPage = this.currentPage - 1;
		var end = this.currentPage*this.entriesPerPage,
			start = end - this.entriesPerPage;
		console.log('start and end',start,end);
		this.display({data:this.entries.slice(start,end)});
		$('.currentPage').html('Current Page: '+this.currentPage);
		
	},
	
	changeEntriesPerPage:function(v) {
		if (!v) return;
		if (v == this.entriesPerPage) return;
	},
	
	/**
	 * {
	 * 		service,
	 * 		payload
	 * }
	 */
	getData:function(params,callback) {
		var self = this;
		if (!this.pagingService) return;
		if (!params.data) return;
		
		$.ajax({
			url:this.pagingService,
			type:'POST',
			dataType:'json',
			data:params.data,
			success:function(response) {
				console.log('response',response);
				if ('success' in response && response['success']==1) {
					if (response['entries']) {
						if (callback) {
							callback(response['entries']);
						} else
							self.display(response['entries']);
					}
				} else {
					var msg = 'Unable retrieve journal entries';
					if ('msg' in response) msg = response['msg'];
					updateMsg($('.myErrorMsg_msg'),msg);
					$('#myErrorMsg').dialog('open');
					setTimeout(function() {$('#myErrorMsg').dialog('close');},2000);
				}
			},
			error:function(err) {
				var msg = 'Unable retrieve journal entries';
				updateMsg($('.myErrorMsg_msg'),msg);
				$('#myErrorMsg').dialog('open');
				setTimeout(function() {$('#myErrorMsg').dialog('close');},2000);
			}
		});		
	},
	
	template:[
	          "<div class='journalEntryWrapper' id='entry_{ID}'>",
	          	"<div class='journalHeaderWrapper'>",
		          	"<h1 class='journalEntryTitle'>{TITLE}</h1>",
		          	"<div class='journalEntryDateTimeWrapper'>",
		          		"<span class='journalEntryDate'>{DATE}</span>",
		          		"<span class='journalEntryTime'>{TIME}</span>",
		          	"</div>",
		        "</div>",
	          	"<div class='journalEntryContent'>{CONTENT}</div>",
	          	"<div class='journalEntryButtons'>",
	          		"<button id='Journal_{JOURNAL_ID}' class='edit' type='button'>Edit</button>",
	          	"</div>",
	          "</div>"
	          ].join('')
}
