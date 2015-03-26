<link rel="stylesheet" href="assets/js/jquery-ui/css/vader/jquery-ui.min.css">
<link rel="stylesheet" href="assets/css/arq.css">
<script type='text/javascript' src='assets/js/arq/AnswerQuestion.js'></script>
<script type='text/javascript' src='assets/js/arq/OtherQuestions.js'></script>
<script type='text/javascript' src='assets/js/arq/CreateQuestion.js'></script>
<script type='text/javascript' src='assets/js/arq/QuestionAnalysis.js'></script>
<script type='text/javascript' src='assets/js/arq/AnsweredQuestions.js'></script>

<script type="text/javascript">

var categories = <?php echo json_encode($categories); ?>,
	question_types = <?php echo json_encode($question_types); ?>,
	question_statuses = <?php echo json_encode($question_statuses); ?>,
	question_flags = <?php echo json_encode($question_flags); ?>,
	//The initial question
	initial_question = <?php echo json_encode($randomQuestion); ?>,
	question_categories = <?php echo json_encode($categories);?>,
	answered_questions  = <?php echo json_encode($answeredQuestions); ?>,
	questions_asked = <?php echo json_encode($questionsAsked); ?>,
	askedQuestions,answeredQuestions,
	initialTab = <?php if ($goto) echo '"'.$goto.'"';else echo 'null';?>,
	sortByDate = <?php if ($sortByDate) echo '"'.$sortByDate.'"';else echo 'null';?>,
	//This will populate other questions
	randomQuestionsByCategory = <?php echo json_encode($randomQuestionsByCategory);?>;
function updateMsg( description,t ) {
	description
      .text( t );
 }
jQuery(document).ready(function($){
	$('#myThinker').dialog({
		autoOpen:false,
		closeOnEscape: false,
		modal:true,
		draggable:true,
		height:150,
		width:350,
	});

	$('#createQuestionSuccess').dialog({
		autoOpen:false,
		closeOnEscape: false,
		modal:true,
		draggable:true,
		height:320,
		width:450,
      buttons: {
          Yes: function() {
        	  $( this ).dialog( "close" );
        	  $('div.tab-pane').removeClass('active');
        	  $('ul.right-aligned li').removeClass('active');
        	  $('#AnswerQuestionTab').addClass('active');
        	  $('#AnswerQuestionPane').addClass('active');
        	  if (CreateQuestion.newQuestion) {
            	  AnswerQuestion.createForm(CreateQuestion.newQuestion.question);
        	  }
          },
          No:function() {$( this ).dialog( "close" );}

        },
        close: function() {
        	$( this ).dialog( "close" );
        }		
	});
	
	if (AnswerQuestion && initial_question && AnswerQuestion.createForm) {
		AnswerQuestion.createForm(initial_question);
		$('#NoQuestionsToAnswer').hide();
	}

	if (OtherQuestions && OtherQuestions.create && randomQuestionsByCategory) {
		OtherQuestions.create(randomQuestionsByCategory);
	}

	if (CreateQuestion && CreateQuestion.createQuestionTypesSelect) {
		CreateQuestion.createQuestionTypesSelect(question_types);
	}

	if (QuestionAnalysis && QuestionAnalysis.display) {
		askedQuestions = $.extend({},QuestionAnalysis);
		askedQuestions.display('#ViewQuestionsAskedPane',questions_asked);

		answeredQuestions = $.extend({},QuestionAnalysis);
		answeredQuestions = $.extend(answeredQuestions,AnsweredQuestions);
		answeredQuestions.display('#ViewQuestionsAnsweredPane',answered_questions,true);
	}

	$('a[href=#ViewQuestionsAskedPane]').on('shown.bs.tab',function() {
		if ($(askedQuestions.placeHolder).jScrollPane) {
			var el = $(askedQuestions.placeHolder).jScrollPane({
				verticalDragMinHeight: 50
			});
			askedQuestions.jspAPI  = el.data('jsp');
			askedQuestions.placeHolder = askedQuestions.placeHolder+' .jspPane';
		}
	});

	$('a[href=#ViewQuestionsAnsweredPane]').on('shown.bs.tab',function() {
		if ($(answeredQuestions.placeHolder).jScrollPane) {
			var el = $(answeredQuestions.placeHolder).jScrollPane({
				verticalDragMinHeight: 50
			});
			answeredQuestions.jspAPI = el.data('jsp');
			answeredQuestions.placeHolder = answeredQuestions.placeHolder+' .jspPane';
		}
	});
	
	if (initialTab) {
		var availableTabs = {
			myQuestions:'#ViewQuestionsAskedPane',
			myAnswers:'#ViewQuestionsAnsweredPane'
		};
		
		if (initialTab in availableTabs) {
			$('a[href='+availableTabs[initialTab]+']').tab('show');
			/*
				Scroll to the first occurrence of date in the specified tab
			*/
			if (sortByDate) {
				var firstQuestion,				
					offset = 0,
					tabObj;
				if (initialTab == 'myAnswers') {
					tabObj = answeredQuestions;
					firstQuestion = answeredQuestions.sortByDate(sortByDate);
				} else if (initialTab == 'myQuestions') {
					tabObj = askedQuestions;
					firstQuestion =askedQuestions.sortByDate(sortByDate);
					
				}
				question_id = firstQuestion.question_id;								
				offset = tabObj.calculateOffsetToQuestion(question_id)+$(tabObj.placeHolder+' thead').height();				
				tabObj.jspAPI.scrollBy(0,offset);
			}
		}
	}
});
</script>
	<!--  Hide the close button on dialog -->
	<style type='text/css'>
		.ui-dialog-titlebar-close {
	  		visibility: hidden;
		}	
		/*button {color:rgb(51,51,51);}*/
	</style>
<br />
<!-- 
<div class="row">
	<div class="addG-title2"><h1 style="padding-left: 20px;">We've got some Questions</h1></div>
	
</div>
 -->
<div class="row col-sm-8">
	<div class="boxHeader"><span class="word1">We've Got Some </span><span class="word2">Questions</span></div>
	<div class="row">
		<div class="panel panel-primary" data-collapsed="0">
			<div class='panel-heading'>
			<div class='panel-options'>
			<ul class="nav nav-tabs left-aligned"><!-- available classes "bordered", "right-aligned" -->
				<li id='AnswerQuestionTab' class="active">
					<a href="#AnswerQuestionPane" data-toggle="tab">
						<img src="/assets/images/Arq/answer_question.png" /><span class="allcaps tabTitle">Answer A Question</span>
				</a>
			</li>
			<li id='CreateQuestionTab'>
				<a href="#CreateQuestionPane" data-toggle="tab">
					<img src="/assets/images/Arq/create_question.png" /><span class="allcaps tabTitle">Create A Question</span>
				</a>
			</li>
			<li id='ViewQuestionsAskedTab'>
				<a href="#ViewQuestionsAskedPane" data-toggle="tab">
					<img src="/assets/images/Arq/your_questions.png" /><span class="allcaps tabTitle">Your Questions</span>
				</a>
			</li>
			<li id='ViewQuestionsAnsweredTab'>
				<a href="#ViewQuestionsAnsweredPane" data-toggle="tab">
					<img src="/assets/images/Arq/your_answers.png" /><span class="allcaps tabTitle">Your Answers</span>
				</a>
			</li>
		</ul>
		</div>
		</div>
		<div class="tab-content">
			<div class="tab-pane active" id="AnswerQuestionPane">
				<div class='FormPlaceHolder displayed' ></div>
				<div class='AnswerQuestionAnalysisPH'></div>
<?php 
		echo '<div id="NoQuestionsToAnswer">There are currently no questions to answer</div>';
?>

				<!--  </div>-->
				
			</div>
			<div class="tab-pane" id="CreateQuestionPane">
			<div id="questionTypeSelectPlaceHolder"></div>
			<div id='createQuestionFormPH'></div>

			</div>
			<div class="tab-pane" id="ViewQuestionsAskedPane" style="max-height: 433px;overflow:scroll;">
				<?php /*$this->widget('QuestionsAsked');*/ ?>
			</div>
			<div class="tab-pane" id="ViewQuestionsAnsweredPane" style="max-height: 433px;overflow:scroll;">
				<?php /*$this->widget('QuestionsAnswered')*/?>
			</div>
		</div>
		<br />
	</div>

<!-- -->
		</div>
	
	</div>	
	<div class="col-sm-4">
		<div class="boxHeader" style='margin-left:20px;'><span class="word1">Other </span><span class="word2">Questions</span></div>
		<div class="panel panel-primary arq-left-margin" data-collapsed="0">

			<div id='OtherQuestions' class="panel-body" style="height: 433px;overflow-x:hidden;overflow-y:scroll;">

			</div>
		
		</div>
	
	</div>
</div>


<br />
<br />




<!-- Footer -->
<footer class="main">
	
		
	&copy; 2014 <strong>ARQNET</strong> Prototype Version 1.0
	
</footer>	

</div>

<div id="myThinker" title="...">
  <p class="validateTips"></p> 
</div>

<div id='createQuestionSuccess' title='Thank you.  Your question has been submitted.'>
	<p class='successMsg'>Would you like to answer this question?</p>
</div>

<script src="/assets/js/jquery.validate.min.js"></script>
<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.custom.min.js"></script>
<script type='text/javascript' src='/assets/js/arq.js'></script>