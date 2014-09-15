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
		if (!questions || !questions.length || questions.length<=0) {
			 $(placeHolder).html('Thre are currently no questions to display');
			 return;
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
		for (var i = 0;i<=4;i++) 
		{
			html = html.replace("Tooltip"+i+"_{ID}","Tooltip"+i+"_"+id);
		}
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
	
	showAnalysisGraph:function(_question,id) {
		if (!_question) return 1;
		if (!_question.answers || !_question.answers.length || _question.answers.length<=0) return 1;
		if (!id) return 1;
		
		var question = _question.question,
			answers = _question.answers,
			myAnswer = question.myAnswer||[],
			type = question.type_name|| 'Open Answer',
			placeAt = '#Canvas_'+id;
		
		if (type == 'Multiple Choice') {
			return this.plotMCGraph(question,answers,myAnswer,placeAt);
		} else if (type == 'Quantitative') {
			return this.plotQtyGraph(question,answers,myAnswer,placeAt);
		} else return 1;
			
	},
	
	plotMCGraph:function(question,answers,myAnswer,placeAt) {
		console.log('PLOT',question,answers,placeAt);
		if (!question) return 1;
		if (!question.choices) return 1;
		if (!answers) return 1;
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
					font:{size:16,family:'sans-serif',color:'rgb(104,72,162)'},
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
		console.log('RESULTS',keys,results);
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
					fillColor:  "#80699B",
					lineWidth:1,				
				},
				data:[ [ results[keys[i]].count,(new Date(year,j,day)).getTime() ] ]
			});

			options['yaxis']['monthNames'].push("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+results[keys[j-1]].choice);
			j--;
		}
		console.log("DATA",placeAt);
		options['yaxis']['monthNames'].push("");
		options['yaxis']['tickLength']=0;
		options['xaxis']['show']=false;
		options['grid'] = {
			borderWidth:0
		};
		 return $.plot($(placeAt),data,options);
	},
	
	plotQtyGraph:function(question,answers,myAnswer,placeAt) {
		if (!question) return 1;
		if (!question.choices) return 1;
		if (!answers) return 1;
		var myBarColor='blue',
			otherBarColor='red',
			options = {
				xaxis:{
					font:{size:16,family:'sans-serif',color:'rgb(104,72,162)'},
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
					font:{size:16,family:'sans-serif',color:'rgb(104,72,162)'},
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
		console.log('RESULTS',keys,results);
		keys.sort(function(a,b) {
			if (results[a].qty > results[b].qty) return 1;
			else if (results[a].qty == results[b].qty) return 0;
			else return -1;
		});
		
		options['xaxis'].min = 0;
		options['xaxis'].max = results[ keys[keys.length-1] ].qty+2;
		options['yaxis'].max = results[keys[keys.length-1]].count+2;
		var data = [],
			ticks = [];
		
		for (var i = 0;i<keys.length;i++) {
			var barColor = otherBarColor;
			if (myAnswer && myAnswer.answer_id == keys[i]) barColor = myBarColor;
			data.push({
				bars: {
					show:true,
					horizontal:false,
					barWidth:0.25,
					align:'center',
					fill:true,
					fillColor:  barColor,
					lineWidth:1,				
				},
				data:[ [ results[keys[i]].qty,results[keys[i]].count ] ]
			});
			ticks.push([results[keys[i]].qty,results[keys[i]].count]);
		}
		options['xaxis'].ticks = ticks;

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
				'<h2>{QUESTION_CONTENT}</h2>',
				'<div id="Tooltip0_{ID}" class="plotTooltip"></div>',
				'<div id="Tooltip1_{ID}" class="plotTooltip"></div>',
				'<div id="Tooltip2_{ID}" class="plotTooltip"></div>',
				'<div id="Tooltip3_{ID}" class="plotTooltip"></div>',
				'<div id="Tooltip4_{ID}" class="plotTooltip"></div>',
				'<div id="{CANVAS_ID}" style="height:100%;width:100%;"></div>',
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