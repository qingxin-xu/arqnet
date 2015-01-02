/*
 * To display how you answered a question, the results of other answers, and public comments
 */
var QuestionAnalysis = {
	deleteService:'/deleteQuestion',
	answerDeleteService:'/deleteAnswer',
	
	id:null,
	questions:null,
	placeHolder:null,
	mainDisplay:null,
	analysisPages:{},
	areAnswers:false,
	sortByDateParameter:'date_created',
	sortParameter:'date_created',
	//sortDirection:['DESC','DESC','DESC']
	sortable:['category','date_created','status'],
	sortable:{
		category:'ASC',
		date_created:'DESC',
		status:'ASC'
	},
	
	MCBarColors:['#2ca8fe','#ee65fd','#00bf4b','#fee8ec'],
	
	/*
	 * Use the create method to create a stand alone analysis display
	 * i.e., not corresponding table of answers exists
	 */
	create:function(mainDisplay,placeHolder) {
		if (!mainDisplay) return;
		if (!placeHolder) return;
		this.mainDisplay = mainDisplay;
		this.placeHolder = placeHolder;
		this.id = Math.floor((Math.random() * 10000000) + 1);
	},
	
	display:function(placeHolder,questions,areAnswers) {
		this.areAnswers = areAnswers;
		this.id = Math.floor((Math.random() * 10000000) + 1);
		if (!placeHolder) return;
		this.placeHolder = placeHolder;
		if (!questions || !questions.length || questions.length<=0) {
			 $(placeHolder).html('Thre are currently no questions to display');
			 return;
		}
		
		var html = this.template,
			rows = '',
			self = this;
		if (!this.questions) 
		{
			this.questions = questions;
			this.sort(this.sortParameter||'date_created',false);
		}
		
		
		for (var i = 0;i<questions.length;i++)
		{
			rows+=this.createRow(questions[i],i,areAnswers);
		}
		html = html.replace(/{ROWS}/,rows);
		
		$(this.placeHolder).html(html);
		
		//Click on question will bring up answer analysis
		$(this.placeHolder+' td.questionContent').each(function(index,row) {
			$(this).click({row:index},function(e) {
				self.displayAnswerAnalysis(self.placeHolder, self.questions[e.data.row], e.data.row);
			});
		});
		
		//Click the delete button
		$(this.placeHolder+' button.deleteButton').each(function(index,row) {
			$(this).on('click',{index:index,row:row},function(e) {		
				if (areAnswers) {
					self.deleteQuestion(e.data.index,true);
				} else {
					self.deleteQuestion(e.data.index);
				}
			});
			
		});
		
		//Click on date will navigate to the calendar
		$(placeHolder+' td.questionDate').each(function(index,row) {
			$(this).click({row:index},function(e) {
				
				if ($(this).html()) {
					var myD = new Date($(this).html()),
						strDate = myD.getFullYear()+'_'+myD.getMonth()+'_'+myD.getDate();
					
					window.open('/calendar?atDate='+strDate,'_blank');
				}
				
			});
		});
		
		this.hookupSorting(placeHolder);
		/*
		for (var i in this.sortable) {
			$(placeHolder+' th.sort_'+i).click({property:i},function(e) {
				if (self.sortable[e.data.property] == 'ASC') {
					self.sortable[e.data.property] = 'DESC';
				} else {
					self.sortable[e.data.property] = 'ASC';
				}
				self.sort(e.data.property,true);
				self._toggleSortClass(e.data.property);
			});
		}
		*/
		
		this.mainDisplay = $(placeHolder+' table');
	},

	hookupSorting:function(placeHolder) {
		var self = this;
		for (var i in this.sortable) {
			$(placeHolder+' th.sort_'+i).click({property:i},function(e) {
				if (self.sortable[e.data.property] == 'ASC') {
					self.sortable[e.data.property] = 'DESC';
				} else {
					self.sortable[e.data.property] = 'ASC';
				}
				self.sort(e.data.property,true);
				self._toggleSortClass(e.data.property);
			});
		}		
	},
	
	replace:function(question) {
		if (!question) return;
		if (!this.questions) return;
		for (var i = 0;i<this.questions.length;i++) {
			if (question.question.question_id == this.questions[i].question.question_id) {
				this.questions[i] = $.extend(this.questions[i],question);
				this.display(this.placeHolder,this.questions,true);
			}
		}
	},
	
	add:function(question) {
		if (!question) return;
		if (!this.questions) this.questions = [];
		this.questions.push(question);
		this.display(this.placeHolder,this.questions,true);
		this.sort(this.sortParameter||'date_created',true);
	},
	
	remove:function(placeHolder,question) {
		if (!placeHolder) return;
		if (!question) return;		
	},
	
	removeQuestion:function(index,id) {
		if (index == null) return;
		if (!this.placeHolder) return;
		$(this.placeHolder+' tr.rowNumber'+index).remove();
		if ( $('#Wrapper_'+this.id+'_'+id) ) {
			$('#Wrapper_'+this.id+'_'+id).remove();
		}
		this.questions.splice(index,1);
		this.display(this.placeHolder,this.questions,this.areAnswers);
	},
	
	setDateTime:function(dateString) {
		if (!dateString) return '';

		var myD,
			_date = '',
			_time = '',
			inputDate = dateString.replace(/\s/,'T'),
			tzOffset = new Date().getTimezoneOffset()*60*1000;
		
		if (inputDate) {
			myD = new Date(inputDate);
			myD.setTime(myD.getTime()+tzOffset);
			_date = myD.toLocaleDateString();
			_time = myD.toLocaleTimeString('en-us',{minute:'2-digit',hour:'2-digit'});
			return new Date(_date).toDateString();
		} else return '';
		
		
	},
	
	createRow:function(questionObj,index,areAnswers) {
		if (!questionObj) return '';
		var question = questionObj.question;
		if (!question) return '';
		if (index==null) index = 1;
		var rowClass = index%2==0?'even':'odd';
		var row = '<tr class="rowClass rowNumber'+index+'" >';
		var date_created = '';
		var buttonTitle = areAnswers?'Delete my answer':'Delete my question';
		if (question.date_created) date_created = this.setDateTime(question.date_created);
		row+='<td class="qa_'+rowClass+' questionContent" style="width:50%;">'+question.content+'</td>';
		row+='<td class="qa_'+rowClass+' centered categoryCol">'+question.category+'</td>';
		row+='<td class="qa_'+rowClass+' questionDate centered dateCol">'+date_created+'</td>';
		row+='<td class="qa_'+rowClass+' centered statusCol">'+question.status+'</td>';
		row+='<td class="qa_'+rowClass+' centered"><button title="'+buttonTitle+'" class="deleteButton" type="button">Delete</></td>';
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
		for (var i = 0;i<=4;i++) 
		{
			html = html.replace("Tooltip"+i+"_{ID}","Tooltip"+i+"_"+id);
		}
		//html = html.replace(/{ANSWER_COMMENTS}/,'Comments_'+id);
		
		
		if (myAnswer) {
			var user_answer = '';
			if (myAnswer.user_answer) user_answer = '"'+myAnswer.user_answer+'"';
			html = html.replace(/{YOUR_ANSWER_CONTENT}/,user_answer);
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

		buttons += this.setButtons(next,prev,id);
		html = html.replace(/{BUTTONS}/,buttons);
		
		// The .jspPane container is from the jScrollPane plugin for fancy scrollbars
		if ($(placeHolder+' .jspPane') && $(placeHolder+' .jspPane').length>0) {
			$(placeHolder+' .jspPane').append(html);
		} else {
			$(placeHolder).append(html);
		}
		
		$('#ViewComments_'+id).click(function() {
			self.showAnswerComments(id);
		});
		
		this.hookupButtons(next, prev, id,placeHolder);
		
		this.analysisPages[id] = {plot:1};
		
		$(placeHolder+' .displayed').fadeOut({
			done:function() {
				$('#Wrapper_'+id).fadeIn({
					done:function() {
						self.analysisPages[id]['plot'] = self.showAnalysisGraph(_question,id);
						if (type == 'Multiple Choice') self.showMCTooltips(id);
					}
				});
				$(placeHolder+' .displayed').removeClass('displayed');
				$('#Wrapper_'+id).addClass('displayed');
			}
		});
		
		
	},
	
	buttonReturn:'Back To Summary',
	buttonNext:'Next',
	buttonPrev:'Previous',
	setButtons:function(next,prev,id) {
		var buttons = '<input id="GOBACK_'+id+'" type="button" value="'+this.buttonReturn+'" class="qa_analysis" style="margin:0 8px 0 8px;float:right;" />';
		if (this.questions && this.questions[next]) {
			buttons += '<input id="NEXT_'+id+'" type="button" value="'+this.buttonNext+'" class="qa_analysis" style="margin:0 8px 0 8px;float:right;" />';			
		}
		
		if (this.questions && this.questions[prev]) {
			buttons += '<input id="PREV_'+id+'" type="button" value="'+this.buttonPrev+'" class="qa_analysis" style="margin:0 8px 0 8px;float:right;" />';

		}		
		return buttons;
	},
	
	hookupButtons:function(next,prev,id,placeHolder) {
		var self = this;
		$('#GOBACK_'+id).click(function() {
			self.goBack(id);
		});
		
		if (this.questions && this.questions[next]) {
			$('#NEXT_'+id).click(function() {
				self.displayAnswerAnalysis(placeHolder, self.questions[next], next);
			});
		}
		
		if (this.questions && this.questions[prev]) {
			$('#PREV_'+id).click(function() {
				self.displayAnswerAnalysis(placeHolder, self.questions[prev], prev);
			});
		}		
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
	
	deleteQuestion:function(index,isAnswered) {
		
		if (index==null) return;
		if (!this.questions[index]) return;
		var question = this.questions[index].question;
		if (isAnswered && !this.questions[index].myAnswer) return;
		
		var id = isAnswered?this.questions[index].myAnswer.answer_id:question.question_id,
			question_id = this.questions[index].question.question_id;
		var data = {};
		if (isAnswered) {
			data['answer_id'] = id;
		} else 
		{
			data['question_id'] = id;
		}

		if (!id) return;
		var self = this;
		updateMsg($('.validateTips'),"Removing Question");
		
		$('#myThinker').dialog('open');
		
		$.ajax({
			url:isAnswered?this.answerDeleteService:this.deleteService,
			type:'POST',
			dataType:'json',
			data:data,
			success:function(d) {
				$('#myThinker').dialog('close');
				if (d.success && d.success>0)
				{					
					self.removeQuestion(index,question_id);
				} else
				{
					//$("#createQuestion")[0].reset();
					if (d.msg) msg = d.msg;
					else msg = errorMsg;
					updateMsg($('.validateTips'),msg);
					setTimeout(function() {$('#myThinker').dialog('close');},2000);
				}
			},
			
			error:function(err)
			{
				console.log('error',err);
				//$("#createQuestion")[0].reset();
				updateMsg($('.validateTips'),"Unable to delete your question at this time");
				setTimeout(function() {$('#myThinker').dialog('close');},3000);
				//_handleError();
			}
		});			
	},
	
	goBack:function(id) {
		var self = this;
		$(this.placeHolder+' .displayed').fadeOut({
			done:function() {
				//$(self.placeHolder+' table').fadeIn();
				//$(self.placeHolder+' table').addClass('displayed');
				$(self.mainDisplay).fadeIn();
				$(self.mainDisplay).addClass('displayed');
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
					modulus = i%2==0?'even':'odd',
					flagID = 'FLAG_'+answers[i].answer_id+id;
				
				html+='<tr><td class="qa_'+modulus+'" style="width:75%;">'+answers[i].user_answer+'</td>';
				html+='<td class="qa_'+modulus+'" align="right"></td>';
				html+='<td class="qa_'+modulus+'" align="right"><input class="qa_analysis" style="margin:0 8px;" id="'+likeID+'" type="button" value="Like" /><input class="qa_analysis" style="margin:0 8px;"  id="'+flagID+'" type="button" value="Flag" /></td>';
			}
		}
		return html;
	},
	
	showAnalysisGraph:function(_question,id) {
		if (!_question) return null;
		if (!_question.answers || !_question.answers.length || _question.answers.length<=0) return null;
		if (!id) return null;
		
		var question = _question.question,
			answers = _question.answers,
			myAnswer = _question.myAnswer||[],
			type = question.type_name|| 'Open Answer',
			placeAt = '#Canvas_'+id;
		
		if (type == 'Multiple Choice') {
			return this.plotMCGraph(question,answers,myAnswer,placeAt);
		} else if (type == 'Quantitative') {
			return this.plotQtyGraph(question,answers,myAnswer,placeAt);
		} else return 1;
			
	},
	
	plotMCGraph:function(question,answers,myAnswer,placeAt) {
		//console.log('PLOT',question,answers,placeAt);
		if (!question) return null;
		if (!question.choices) return null;
		if (!answers) return null;
		var year = 2014,
			day  = 1,
			options = {
				xaxis:{
					font:{size:16,family:'sans-serif',color:'rgb(104,72,162)'},
					min:0,/*sorted_set[nWords-1].count-1*/
					//max:answers.length+2,
	                axisLabel: 'Count',
	                axisLabelUseCanvas: true,
	                axisLabelFontSizePixels: 18,
	                axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
	                axisLabelPadding: 5				
				},
				yaxis:{
					font:{size:16,family:'sans-serif',color:'#FFFFFF'},
	                min: (new Date(year, 0, 1)).getTime(),
	                //max: (new Date(year, keys.length+1, 1)).getTime(),
	                tickSize: [1, "month"],
	          		monthNames:[""],
	         	   	mode:'time',
	                axisLabel: 'Value',
	                axisLabelUseCanvas: true,
	                axisLabelFontSizePixels: 18,
	                axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
	                axisLabelPadding: 5				
				}
		},
		results = {},
		count = answers.length+1;;
		
		for (var i = 0;i<question.choices.length;i++) {
			results[question.choices[i].question_choice_id] = {
				count:0,
				choice:question.choices[i].content
			};
		}

		for (var i = 0;i<answers.length;i++) {
			var choice = answers[i].question_choice_id;
			results[choice].count = results[choice].count+1;

		}
		
		var keys = Object.keys(results);
		
		keys.sort(function(a,b) {
			if (results[a].count > results[b].count) return -1;
			else if (results[a].count == results[b].count) return 0;
			else return 1;
		});
		options['xaxis'].max = results[ keys[keys.length-1] ].count+2;
		options['yaxis'].max = new Date(year, keys.length+1, 1).getTime();
		j = keys.length;
		var data = [];
		
		for (var i = 0;i<keys.length;i++) {
			data.push({
				bars: {
					show:true,
					horizontal:true,
					barWidth:4*12*24*60*60*300,
					fill:true,
					align:'center',
					fillColor:  this.MCBarColors[i],
					shadow:{color:null},
					lineWidth:0,				
				},
				data:[ [ results[keys[i]].count,(new Date(year,j,day)).getTime() ] ]
			});

			options['yaxis']['monthNames'].push("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+results[keys[j-1]].choice);
			j--;
		}
		//console.log("DATA",placeAt);
		options['yaxis']['monthNames'].push("");
		options['yaxis']['tickLength']=0;
		options['xaxis']['show']=false;
		options['grid'] = {
			borderWidth:0,
			borderColor:null
		};
		 return $.plot($(placeAt),data,options);
	},
	
	plotQtyGraph:function(question,answers,myAnswer,placeAt) {
		if (!question) return null;
		if (!question.choices) return null;
		if (!answers) return null;
		var myBarColor='#f067fd',
			otherBarColor='#985ffc',
			options = {
				xaxis:{
					font:{size:16,family:'sans-serif',color:'#FFFFFF'},
					//min:0,/*sorted_set[nWords-1].count-1*/
					//max:answers.length+2,
	                axisLabel: 'Quantity',
	                axisLabelUseCanvas: true,
	                axisLabelFontSizePixels: 18,
	                axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
	                axisLabelPadding: 5	,
	                tickLength:0
				},
				yaxis:{
					font:{size:16,family:'sans-serif',color:'#FFFFFF'},
	                min: 0,
	       
	                axisLabel: 'Count',
	                axisLabelUseCanvas: true,
	                axisLabelFontSizePixels: 18,
	                axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
	                axisLabelPadding: 5,
	                tickLength:0
				}
		},
		results = {},
		count = answers.length+1;;
		
		for (var i = 0;i<answers.length;i++) {
			var answer_id = answers[i].answer_id;
			if (results[answer_id]) {
				results[answer_id].count = results[answer_id].count+1;
			} else {
				results[answer_id] = {
					count:1,
					qty:parseInt(answers[i].quantitative_value)
				};
			}
		}
		
		var keys = Object.keys(results);
		
		keys.sort(function(a,b) {
			if (results[a].qty > results[b].qty) return 1;
			else if (results[a].qty == results[b].qty) return 0;
			else return -1;
		});
		
		options['xaxis'].min = 0;
		options['xaxis'].max = results[ keys[keys.length-1] ].qty+2;
		options['yaxis'].max = results[keys[keys.length-1]].count+2;
		var data = [],
			ticks = [],
			yticks = [];
		
		for (var i = 0;i<keys.length;i++) {
			var barColor = otherBarColor;
			if (myAnswer && myAnswer.answer_id == keys[i]) barColor = myBarColor;
			data.push({
				bars: {
					show:true,
					horizontal:false,
					barWidth:1,
					align:'center',
					fill:true,
					fillColor:  barColor,
					lineWidth:0,				
				},
				data:[ [ results[keys[i]].qty,results[keys[i]].count ] ]
			});
			//ticks.push([results[keys[i]].qty,results[keys[i]].count]);
			ticks.push(results[keys[i]].qty);
			yticks.push(results[keys[i]].count);
		}
		options['xaxis'].ticks = ticks;
		//options['yaxis'].ticks = yticks;

		 return $.plot($(placeAt),data,options);		
	},
	
	showMCTooltips:function(id) {
		if (!id) return;
		if (!this.analysisPages[id] || !this.analysisPages[id].plot) return;
		var plot = this.analysisPages[id].plot
		var ctx = plot.getCanvas().getContext("2d"); // get the context
		var _data = plot.getData();//[0].data;  // get your series data
		var xaxis = plot.getXAxes()[0]; // xAxis
		var yaxis = plot.getYAxes()[0]; // yAxis
		var offset = plot.getPlotOffset(); // plots offset
		ctx.font = "18px Verdana"; // set a pretty label font
		ctx.fillStyle = "#FFFFFF";
		
		$.each(_data,function(index,item) {
			var data = item.data;
			for (var i = 0; i < data.length; i++){
			    var text = data[i][0]*100 + '%';
			    var metrics = ctx.measureText(text);
			    var xPos = (xaxis.p2c(data[i][0])+offset.left + 15); // place it in the middle of the bar
			    var yPos = yaxis.p2c(data[i][1]) + offset.top + 6; // place at top of bar, slightly up
			    ctx.fillText(text, xPos, yPos);
			}	
		});

	},
	
	showQtyTooltips:function(id) {
		if (!id) return;
		if (!this.analysisPages[id] || !this.analysisPages[id].plot) return;
		var plot = this.analysisPages[id].plot
		var ctx = plot.getCanvas().getContext("2d"); // get the context
		var _data = plot.getData();//[0].data;  // get your series data
		var xaxis = plot.getXAxes()[0]; // xAxis
		var yaxis = plot.getYAxes()[0]; // yAxis
		var offset = plot.getPlotOffset(); // plots offset
		ctx.font = "18px Verdana"; // set a pretty label font
		ctx.fillStyle = "black";
		
		$.each(_data,function(index,item) {
			var data = item.data;
			for (var i = 0; i < data.length; i++){
			    var text = data[i][0]*100 + '%';
			    var metrics = ctx.measureText(text);
			    var xPos = (xaxis.p2c(data[i][0])+offset.left + 15); // place it in the middle of the bar
			    var yPos = yaxis.p2c(data[i][1]) + offset.top + 6; // place at top of bar, slightly up
			    ctx.fillText(text, xPos, yPos);
			}	
		});		
	},
	
	_showTooltips:function(id) {
		if (!id) return;
		if (!this.analysisPages[id] || !this.analysisPages[id].plot) return;
		
		var plot = this.analysisPages[id].plot,
			offset = 0;
		
		plot.unhighlight();
		//this.trackerPlot.unhighlight();
		$.each(plot.getData(),function(index,item) {
			var point = {x:item.data[0],y:item.data[1]};
			var pointOffset = plot.pointOffset(point);
			if (!point || !('x' in point)) return;
			
			var html = '';
			/*
			if (item.capped) {
				if (item.originalData[point['x']]) {
					
					html = item.tipLabel;
				} else {
					$("#Tooltip"+index).hide();
					return;
				}
			} else {
				if (!item.originalData) return;
				html = item.tipLabel+' = '+item.originalData[point['x']];//point['y'].toFixed(2);
			}
			*/
			html = 'HI ';
			$("#Tooltip"+index+"_"+id).html(html)
			.css({top: pointOffset.top+20, left: pointOffset.left+40+offset})
			.fadeIn(200);
			//plot.highlight(index,sliderValue);
		});
	},
	
	getSortByDateValue:function(question) {
		if (!question) return null;
		var sortByDateParameter = this.sortByDateParameter||'date_created';
		if (sortByDateParameter.match(/\./)) {
			var tmp = sortByDateParameter.split(/\./),
			p1 = tmp[0],p2=tmp[1];
		
			if (!p1) return null;
			if (!p2) return null;
			return question[p1][p2];
		} else return question.question[sortByDateParameter];
	},
	
	/**
	 * Provided a date, in the form YYYY-MM-DD, display all questions either asked
	 * or answered on this date.  The determination of whether or not we sort by 
	 * answered or asked questions is determined the the sortByDateParameter class property
	 */
	sortByDate:function(sortDate) {
		if (!sortDate) return;
		if (!this.questions) return;
		var sortDateQuestions = [],
			otherQuestions = [];
		for (var i = 0;i<this.questions.length;i++) {
			var value = this.getSortByDateValue(this.questions[i]) || null;
			if (value) {
				var question_date = value.split(/\s+/)[0];
				if (question_date == sortDate) {
					sortDateQuestions.push(this.questions[i]);
				} else otherQuestions.push(this.questions[i]);
			} else otherQuestions.push(this.questions[i]);
		}
		
		this.questions = sortDateQuestions.concat(otherQuestions);
		this.display(this.placeHolder,this.questions,this.areAnswers);
	},
	
	sort:function(property,redraw) {		
		if (!this.questions) return;
		if (!property) return;
		if (property.match(/\./)) {
			return this.deepSort(property,redraw);
		}
		if (!this.sortable[property]) return;
		var self = this;
		if (this.sortable[property] == 'DESC') {
			this.questions.sort(function(a,b) {
				if (property.match(/\./)) {
					var tmp = property.split(/\./);
					var p1 = tmp[0],p2=tmp[1];
					if (!a.question.p1.p2) return 1;
					if (!b.question.p1.p2) return -1;
					if (a.question.p1.p2 > b.question.p1.p2) return 1;
					else if (a.question.p1.p2 == b.question.p1.p2) return 0;
					else return -1;
				} else {
					if (!a.question[property]) return 1;
					if (!b.question[property]) return -1;
					if (a.question[property] > b.question[property]) return 1;
					else if (a.question[property] == b.question[property]) return 0;
					else return -1;
				}
			});
		} else {
			this.questions.sort(function(a,b) {
				if (property.match(/\./)) {
					var tmp = property.split(/\./);
					var p1 = tmp[0],p2=tmp[1];
					if (!a.question.p1.p2) return -1;
					if (!b.question.p1.p2) return 1;
					if (a.question.p1.p2 > b.question.p1.p2) return -1;
					else if (a.question.p1.p2 == b.question.p1.p2) return 0;
					else return 1;
				} else {
					if (!a.question[property]) return -1;
					if (!b.question[property]) return 1;
					if (a.question[property] > b.question[property]) return -1;
					else if (a.question[property] == b.question[property]) return 0;
					else return 1;
				}
			});			
		}
		if (redraw) {
			this.display(this.placeHolder,this.questions,this.areAnswers);
		}
	},
	
	deepSort:function(property,redraw) {
		
		var tmp = property.split(/\./),
			p1 = tmp[0],p2=tmp[1];
		
		if (!p1) return;
		if (!p2) return;

		var self = this;
		if (this.sortable[property] == 'DESC') {
			this.questions.sort(function(a,b) {
				if (!a[p1][p2]) return 1;
				if (!b[p1][p2]) return -1;
				if (a[p1][p2] > b[p1][p2]) return 1;
				else if (a[p1][p2] == b[p1][p2]) return 0;
				else return -1;
			});
		} else {
			console.log("ASC");
			this.questions.sort(function(a,b) {
				if (!a[p1][p2]) return -1;
				if (!b[p1][p2]) return 1;
				if (a[p1][p2] > b[p1][p2]) return -1;
				else if (a[p1][p2] == b[p1][p2]) return 0;
				else return 1;
			});			
		}
		if (redraw) {
			this.display(this.placeHolder,this.questions,this.areAnswers);
		}		
	},
	
	_toggleSortClass:function(property) {
		if (!property) return;
		var index = property;
		if (index.match(/DOT/) )  index = index.replace(/DOT/,'.');
	
		if (!this.sortable[index]) return;
		
		if (this.sortable[index] == 'ASC') {
			$(this.placeHolder+' th.sort_'+property).removeClass('headerSortDESC');
			$(this.placeHolder+' th.sort_'+property).addClass('headerSortASC');
		} else {
			$(this.placeHolder+' th.sort_'+property).removeClass('headerSortASC');
			$(this.placeHolder+' th.sort_'+property).addClass('headerSortDESC');
		}
		
		for (var i in this.sortable) {
			if (i != index) {
				$(this.placeHolder+' th.sort_'+i).removeClass('headerSortDESC');
				$(this.placeHolder+' th.sort_'+i).removeClass('headerSortASC');
				this.sortable[i] = 'ASC';
			}
		}

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
			'<div style="height:400px;width:100%;">',
			'<div id="{ANALYSIS_PLOT_ID}"  style="width:65%;height:300px;float:left;">',
				'<h2 class="qa_question">{QUESTION_CONTENT}</h2>',
				'<div id="Tooltip0_{ID}" class="plotTooltip"></div>',
				'<div id="Tooltip1_{ID}" class="plotTooltip"></div>',
				'<div id="Tooltip2_{ID}" class="plotTooltip"></div>',
				'<div id="Tooltip3_{ID}" class="plotTooltip"></div>',
				'<div id="Tooltip4_{ID}" class="plotTooltip"></div>',
				'<div id="{CANVAS_ID}" style="height:100%;width:100%;"></div>',
			'</div>',
			'<div id="{YOUR_ANSWER_ID}"  style="width:30%;height:300px;float:right;">',
				'<h4>Your Answer:<span class="qa_answer_type">{YOUR_ANSWER_TYPE}</span></h4>',
				//'<h4>Comment:</h4>',
				'<h4 class="qa_comment">{YOUR_ANSWER_CONTENT}</h4>',
			'</div>',
			'</div>',
			'<div style="width:85%;height:50px;margin:15px 0 15px 15px;">',
				'<div style="width:50%;float:left;">',
					'<a id="{VIEW_COMMENTS_ID}" class="qa_view_comments" style="{DISPLAY_COMMENTS};cursor:pointer;">View Comments</a>',
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
					'<th class="qa_header" >Question</th>',
					'<th class="qa_header centered sort_category headerSortable">Category</th>',
					'<th class="qa_header centered sort_date_created headerSortable headerSortASC">Created On</th>',
					'<th class="qa_header centered sort_status headerSortable">Status</th>',
					'<th class="qa_header centered">Actions</th>',
				'</thead>',
				'<tbody>',
				'{ROWS}',
				'</tbody>',
	          '</table>'].join("")
	          
	     
};