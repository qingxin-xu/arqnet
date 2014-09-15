<link rel="stylesheet" href="assets/js/jquery-ui/css/vader/jquery-ui.min.css">
<link rel="stylesheet" href="assets/css/arq.css">
<script type='text/javascript' src='assets/js/arq/AnswerQuestion.js'></script>
<script type='text/javascript' src='assets/js/arq/OtherQuestions.js'></script>
<script type='text/javascript' src='assets/js/arq/CreateQuestion.js'></script>
<script type='text/javascript' src='assets/js/arq/QuestionAnalysis.js'></script>
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
            	  AnswerQuestion.createForm(CreateQuestion.newQuestion);
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
		answeredQuestions.display('#ViewQuestionsAnsweredPane',answered_questions);
	}
});
</script>
	<!--  Hide the close button on dialog -->
	<style type='text/css'>
		.ui-dialog-titlebar-close {
	  		visibility: hidden;
		}	
		button {color:rgb(51,51,51);}
	</style>
<br />
<!-- 
<div class="row">
	<div class="addG-title2"><h1 style="padding-left: 20px;">We've got some Questions</h1></div>
	
</div>
 -->
<div class="row col-sm-8">
	<div class="row">
		<div class="panel panel-primary" data-collapsed="0">
			<ul class="nav nav-tabs right-aligned"><!-- available classes "bordered", "right-aligned" -->
				<li id='AnswerQuestionTab' class="active">
					<a href="#AnswerQuestionPane" data-toggle="tab">
					<span class="visible-xs"><i class="entypo-home"></i></span>
					<span class="hidden-xs">Answer A Question</span>
				</a>
			</li>
			<li id='CreateQuestionTab'>
				<a href="#CreateQuestionPane" data-toggle="tab">
					<span class="visible-xs"><i class="entypo-user"></i></span>
					<span class="hidden-xs">Create A Question</span>
				</a>
			</li>
			<li id='ViewQuestionsAskedTab'>
				<a href="#ViewQuestionsAskedPane" data-toggle="tab">
					<span class="visible-xs"><i class="entypo-mail"></i></span>
					<span class="hidden-xs">Questions Asked</span>
				</a>
			</li>
			<li id='ViewQuestionsAnsweredTab'>
				<a href="#ViewQuestionsAnsweredPane" data-toggle="tab">
					<span class="visible-xs"><i class="entypo-cog"></i></span>
					<span class="hidden-xs">Questions Answered</span>
				</a>
			</li>
		</ul>
		
		<div class="tab-content">
			<div class="tab-pane active" id="AnswerQuestionPane">
				<div class='FormPlaceHolder displayed' ></div>
				<div class='AnswerQuestionAnalysisPH'></div>
<?php 
		echo '<div>There are currently no questions to answer</div>';
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
			<div class="tab-pane" id="ViewQuestionsAnsweredPane">
				<?php /*$this->widget('QuestionsAnswered')*/?>
			</div>
		</div>
		<br />
	</div>

<!-- -->
		</div>
	
	</div>	
	<div class="col-sm-4">
		<div class="panel panel-primary arq-left-margin" data-collapsed="0">
			<!-- panel head -->
			<div class="panel-heading">
				<div class="panel-title addG-panel-title2" id="question_title" style="padding-top: 20px;">Other Questions</div>
			</div>
			<!-- panel body -->
			<div id='OtherQuestions' class="panel-body" style="height: 433px;overflow:scroll;">
				<!--  
				<div class="addG-innerdiv">
					<span class="label label-success">Work</span><br><br>
					<p>How do you feel about your boss at work?</p>		
				</div>
				<div class="addG-innerdiv">
					<span class="label label-primary">Personal</span><br><br>
					<p>How do you feel when you see the color blue?</p>		
				</div>
				<div class="addG-innerdiv">
					<span class="label label-secondary">Emotions</span><br><br>
					<p>How do you feel when someone embarrasses you?</p>		
				</div>
				-->
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