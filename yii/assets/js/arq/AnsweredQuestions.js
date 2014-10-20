var AnsweredQuestions = {

		sortParameter:'myAnswer.date_created',
		//sortDirection:['DESC','DESC','DESC']
		sortable:['category','myAnswer.date_created','status'],
		sortable:{
			category:'ASC',
			'myAnswer.date_created':'DESC',
			status:'ASC'
		},
		
		createRow:function(questionObj,index,areAnswers) {
			if (!questionObj) return '';
			var question = questionObj.question;
			if (!question) return '';
			var myAnswer = questionObj.myAnswer;
			if (index==null) index = 1;
			var rowClass = index%2==0?'even':'odd';
			var row = '<tr class="rowClass rowNumber'+index+'" >';
			var date_created = '';
			var buttonTitle = areAnswers?'Delete my answer':'Delete my question';
			if (myAnswer.date_created) date_created = new Date(myAnswer.date_created).toDateString();
			row+='<td class="qa_'+rowClass+' questionContent" style="width:50%;">'+question.content+'</td>';
			row+='<td class="qa_'+rowClass+' centered categoryCol">'+question.category+'</td>';
			row+='<td class="qa_'+rowClass+' questionDate centered dateCol">'+date_created+'</td>';
			row+='<td class="qa_'+rowClass+' centered statusCol">'+question.status+'</td>';
			row+='<td class="qa_'+rowClass+' centered"><button title="'+buttonTitle+'" class="deleteButton" type="button">Delete</></td>';
			row+='</tr>';
			return row;
		},
		
		hookupSorting:function(placeHolder) {
			var self = this;
			for (var i in this.sortable) {
				var index = i;
				if (index.match(/\./)) index = index.replace(/\./,'DOT');
				$(placeHolder+' th.sort_'+index).click({property:index},function(e) {
					if (e.data.property.match(/DOT/)) {
						var tmp = e.data.property.replace(/DOT/,'.');//e.data.property.split(/DOT/),
							//p1 = tmp[0],p2=tmp[1];
						/*
						if (self.sortable[p1][p2] == 'ASC') {
							self.sortable[p1][p2] = 'DESC';
						} else {
							self.sortable[p1][p2] = 'ASC';
						}
						*/
						if (self.sortable[tmp] == 'ASC') {
							self.sortable[tmp] = 'DESC';
						} else {
							self.sortable[tmp] = 'ASC';
						}						
						self.sort(tmp,true);
						self._toggleSortClass(e.data.property);
												
					} else {
						if (self.sortable[e.data.property] == 'ASC') {
							self.sortable[e.data.property] = 'DESC';
						} else {
							self.sortable[e.data.property] = 'ASC';
						}
						self.sort(e.data.property,true);
						self._toggleSortClass(e.data.property);
					}
				});
			}		
		},
		
		template:[
		          '<table class="table table-striped displayed">',
					'<thead>',
					'<tr>',
						'<th class="qa_header" >Question</th>',
						'<th class="qa_header centered sort_category headerSortable">Category</th>',
						'<th class="qa_header centered sort_myAnswerDOTdate_created headerSortable headerSortDESC">Answered On</th>',
						'<th class="qa_header centered sort_status headerSortable">Status</th>',
						'<th class="qa_header centered">Actions</th>',
					'</thead>',
					'<tbody>',
					'{ROWS}',
					'</tbody>',
		          '</table>'].join("")
};