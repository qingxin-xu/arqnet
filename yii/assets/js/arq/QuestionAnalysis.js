/*
 * To display how you answered a question, the results of other answers, and public comments
 */
var QuestionAnalysis = {
	deleteService:'/deleteQuestion',
	
	id:null,
	questions:null,
	placeHolder:null,
	analysisPages:{},
	
	display:function(placeHolder,questions) {
		
		this.id = Math.floor((Math.random() * 10000000) + 1);
		
		if (!placeHolder) return;
		if (!questions || !questions.length || !questions.length<=0) {
			 $(placeHolder).html('Thre are currently no questions to display');
		}
		
		this.placeHolder = placeHolder;
		
		var html = this.template,
			rows = '',
			self = this;
		
		for (var i = 0;i<questions.length;i++)
		{
			rows+=this.createRow(questions[i].question,i);
		}
		html = html.replace(/{ROWS}/,rows);
		this.questions = questions;
		$(placeHolder).html(html);
		$(placeHolder+' tr').each(function(index,row) {
			
			$(this).click({row:index-1},function(e) {
				self.displayAnswerAnalysis(placeHolder, self.questions[e.data.row], e.data.row);
			});
		});
	},

	add:function(placeHolder,question) {
		if (!placeHolder) return;
		if (!question) return;
	},
	
	remove:function(placeHolder,question) {
		if (!placeHolder) return;
		if (!question) return;		
	},
	
	createRow:function(question,index) {
		if (!question) return '';
		if (!index) index = 1;
		var rowClass = index%2==0?'even':'odd';
		var row = '<tr class="rowClass" style="cursor:pointer;">';
		row+='<td>'+question.content+'</td>';
		row+='<td>'+question.category+'</td>';
		row+='<td>'+question.date_created+'</td>';
		row+='<td>'+question.status+'</td>';
		row+='<td>delete</td>';
		row+='</tr>';
		return row;
	},
	
	displayAnswerAnalysis:function(placeHolder,_question,index) {
		if (!_question) return;
		if (!placeHolder) return;
		
		var answers = _question.answers,
			question = _question.question,
			type = question.type_name|| 'Open Answer',
			myAnswer = _question.myAnswer||null,
			id = this.id+'_'+ question.question_id,
			self = this,
			html = this.answerAnalysisTemplate;
		
		if (this.analysisPages[id]) {
			this._displayAnalysis(id);
			return;
		}
		
		html = html.replace(/{QUESTION_CONTENT}/,question.content);
		html = html.replace(/{ANSWER_ANALYSIS_WRAPPER_ID}/,'Wrapper_'+id);
		html = html.replace(/{ANALYSIS_PLOT_ID}/,'Plot_'+id);
		html = html.replace(/{CANVAS_ID}/,'Canvas_'+id);
		html = html.replace(/{YOUR_ANSWER_ID}/,'Answer_'+id);
		html = html.replace(/{COMMENT_CONTAINER_ID}/,'CommentContainer_'+id);
		html = html.replace(/{VIEW_COMMENTS_ID}/,'ViewComments_'+id);
		//html = html.replace(/{ANSWER_COMMENTS}/,'Comments_'+id);
		
		
		if (myAnswer) {
			html = html.replace(/{YOUR_ANSWER_CONTENT}/,myAnswer.user_answer);
			if (type == 'Open Answer') {
				html = html.replace(/{YOUR_ANSWER_TYPE}/,'');
			} else if (type == 'Multiple Choice') {
				var choices = question.choices,
					choice_id = myAnswer.question_choice_id,
					str = this.MCAnalysisResultsTemplate;
				
				for (var i = 0;i<choices.length;i++) {
					if (choices[i].question_choice_id == choice_id) {
						str = str.replace(/{MY_CHOICE}/,choices[i].content);
						break;
					}
				}
				html = html.replace(/{YOUR_ANSWER_TYPE}/,str);
			} else if (type == 'Quantitative') {
				str = this.QtyAnalysisResultsTemplate;
				str = str.replace(/{MY_CHOICE}/,myAnswer.quantitative_value);
				html = html.replace(/{YOUR_ANSWER_TYPE}/,str);
			}
		} else {
			html = html.replace(/{YOUR_ANSWER_CONTENT}/,'');
			html = html.replace(/{YOUR_ANSWER_TYPE}/,'');
		}
		
		if (answers && answers.length && answers.length>0) {
			var comments = this.createAnswerComments(answers,id);
			html = html.replace(/{ANSWER_COMMENTS}/,comments);
			html = html.replace(/{DISPLAY_COMMENTS}/,'');
		} else {
			html = html.replace(/{DISPLAY_COMMENTS}/,'display:none;');
		}
		
		//Buttons
		var buttons = '',
			next = index+1,
			prev = index-1;

		buttons += '<input id="GOBACK_'+id+'" type="button" value="Back" style="margin:0 8px 0 8px;float:right;" />';
		if (this.questions[next]) {
			buttons += '<input id="NEXT_'+id+'" type="button" value="Next" style="margin:0 8px 0 8px;float:right;" />';			
		}
		
		if (this.questions[prev]) {
			buttons += '<input id="PREV_'+id+'" type="button" value="Previous" style="margin:0 8px 0 8px;float:right;" />';

		}
		html = html.replace(/{BUTTONS}/,buttons);
		
		$(placeHolder).append(html);
		
		$('#ViewComments_'+id).click(function() {
			console.log('show comment container');
			self.showAnswerComments(id);
		});
		
		$('#GOBACK_'+id).click(function() {
			self.goBack(id);
		});
		
		if (this.questions[next]) {
			$('#NEXT_'+id).click(function() {
				self.displayAnswerAnalysis(placeHolder, self.questions[next], next);
			});
		}
		
		if (this.questions[prev]) {
			$('#PREV_'+id).click(function() {
				self.displayAnswerAnalysis(placeHolder, self.questions[prev], prev);
			});
		}
		
		$(placeHolder+' .displayed').fadeOut({
			done:function() {
				$('#Wrapper_'+id).fadeIn();
				$(placeHolder+' .displayed').removeClass('displayed');
				$('#Wrapper_'+id).addClass('displayed');
			}
		});
		this.analysisPages[id] = 1;
	},
	
	_displayAnalysis:function(id) {
		if (!id) return;
		if (!this.placeHolder) return;
		var self = this;
		$(this.placeHolder+' .displayed').fadeOut({
			done:function() {
				$('#Wrapper_'+id).fadeIn();
				$(self.placeHolder+' .displayed').removeClass('displayed');
				$('#Wrapper_'+id).addClass('displayed');
			}
		});		
	},
	
	goBack:function(id) {
		var self = this;
		$(this.placeHolder+' .displayed').fadeOut({
			done:function() {
				$(self.placeHolder+' table').fadeIn();
				$(self.placeHolder+' table').addClass('displayed');
				$('#Wrapper_'+id).removeClass('displayed');
			}
		});
	},
	
	showAnswerComments:function(id) {
		if (!id) return;
		var self = this;
		
		$('#CommentContainer_'+id).fadeIn({done:function() {
			$('#ViewComments_'+id).html('Hide Comments');
			$('#ViewComments_'+id).unbind('click');
			$('#ViewComments_'+id).click(function() {
				self.hideAnswerComments(id);
			});
		}});
	},
	
	hideAnswerComments:function(id) {
		if (!id) return;
		var self = this;
		$('#CommentContainer_'+id).fadeOut({done:function() {
			$('#ViewComments_'+id).html('View Comments');
			$('#ViewComments_'+id).unbind('click');
			$('#ViewComments_'+id).click(function() {
				self.showAnswerComments(id);
			});
		}});		
	},
	
	createAnswerComments:function(answers,id) {
		if (!answers || !answers.length || answers.length<=0) return;
		if (!id) return;
		var html = '';
		for (var i = 0;i<answers.length;i++) {
			if (answers[i].user_answer) {
				var likeID = 'LIKE_'+answers[i].answer_id+id,
					flagID = 'FLAG_'+answers[i].answer_id+id;
				
				html+='<tr><td style="width:75%;">'+answers[i].user_answer+'</td>';
				html+='<td align="right"></td>';
				html+='<td align="right"><input style="margin:0 8px;" id="'+likeID+'" type="button" value="Like" /><input style="margin:0 8px;"  id="'+flagID+'" type="button" value="Flag" /></td>';
			}
		}
		return html;
	},
	
	hideComments:function(id) {
		
	},
	
	viewQuestion:function(question) {
		if (!question) return;
	},
	
	deleteAskedQuestion:function(question) {
		if (!question) return;
	},
	
	deleteAnsweredQuestion:function(question) {
		if (!question) return;
	},
	
	likeAnswerComment:function(answer) {
		
	},
	
	flagAnswerComment:function(answer) {
		
	},
	
	answerAnalysisTemplate:[
		'<div class="answerAnalysisWrapper" style="display:none;" id="{ANSWER_ANALYSIS_WRAPPER_ID}" >',
			//'<div style="width:100%;height:50px;"></div>',
			'<div style="height:300px;width:100%;">',
			'<div id="{ANALYSIS_PLOT_ID}"  style="width:65%;height:300px;float:left;">',
				'<h2>{QUESTION_CONTENT}</h2>',
				'<canvas id="{CANVAS_ID}"></canvas>',
			'</div>',
			'<div id="{YOUR_ANSWER_ID}"  style="width:30%;height:300px;float:right;">',
				'<h4>Your Answer:{YOUR_ANSWER_TYPE}</h4>',
				'<h4>Comment:</h4>',
				'<h4>{YOUR_ANSWER_CONTENT}</h4>',
			'</div>',
			'</div>',
			'<div style="width:85%;height:50px;margin:15px 0 15px 15px;">',
				'<div style="width:50%;float:left;">',
					'<a id="{VIEW_COMMENTS_ID}" style="{DISPLAY_COMMENTS};cursor:pointer;">View Comments</a>',
				'</div>',
				'<div style="width:50%:float:right;">',
					'{BUTTONS}',
				'</div>',
			'</div>',
			'<div id="{COMMENT_CONTAINER_ID}" style="display:none;overflow:scroll;height:225px;">',
				'<table class="table table-striped" style="width:100%;">{ANSWER_COMMENTS}</table>',
			'</div>',
		'</div>'].join(""),
	
	MCAnalysisResultsTemplate:[
	                           '<span style="margin:0 0 0 8px;">{MY_CHOICE}</span>'	                          
	                           ].join(""),
	                           
	QtyAnalysisResultsTemplate:[
	                           '<span style="margin:0 0 0 8px;">{MY_CHOICE}</span>'
	                           ].join(""),
	
	answerCommentTemplate:[
	                       
	                       ].join(""),
	
	template:[
	          '<table class="table table-striped displayed">',
				'<thead>',
				'<tr>',
					'<th>Question</th>',
					'<th>Category</th>',
					'<th>Date</th>',
					'<th>Status</th>',
					'<th>Actions</th>',
				'</thead>',
				'<tbody>',
				'{ROWS}',
				'</tbody>',
	          '</table>'].join("")
	          
	     
};