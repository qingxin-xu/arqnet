<link rel="stylesheet" href="assets/js/jquery-ui/css/vader/jquery-ui.min.css">
<script type="text/javascript">
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
<div class="row">
	<div class="addG-title2"><h1 style="padding-left: 20px;">We've got some Questions</h1></div>
	
</div>
<div class="row col-sm-8">
	<div class="row">
		<div class="panel panel-primary" data-collapsed="0">
			<ul class="nav nav-tabs right-aligned"><!-- available classes "bordered", "right-aligned" -->
				<li class="active">
						<a href="#home-2" data-toggle="tab">
					<span class="visible-xs"><i class="entypo-home"></i></span>
					<span class="hidden-xs">Answer A Question</span>
				</a>
			</li>
			<li>
				<a href="#profile-2" data-toggle="tab">
					<span class="visible-xs"><i class="entypo-user"></i></span>
					<span class="hidden-xs">Create A Question</span>
				</a>
			</li>
			<li>
				<a href="#messages-2" data-toggle="tab">
					<span class="visible-xs"><i class="entypo-mail"></i></span>
					<span class="hidden-xs">Questions Asked</span>
				</a>
			</li>
			<li>
				<a href="#settings-2" data-toggle="tab">
					<span class="visible-xs"><i class="entypo-cog"></i></span>
					<span class="hidden-xs">Questions Answered</span>
				</a>
			</li>
		</ul>
		
		<div class="tab-content">
			<div class="tab-pane active" id="home-2">
				<div>
<?php 

	if ($question)
	{
		echo '<form role="form" id="answerQuestion" class="arq-form">'.
			'<input type="hidden" name="question_id" value="'.$question->question_id.'">'.
			'<div class="btn-group" style="float:right;">'.
				'<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" >'.
					'<i class="entypo-flag"></i>'.
				'</button>'.
		
				'<ul class="dropdown-menu dropdown-danger" role="menu">'.
					'<li><a href="#"><i class="entypo-right"></i>Inappropriate</a>'.
					'</li>'.
					'<li><a href="#"><i class="entypo-right"></i>Unclear</a>'.
					'</li>'.
					'<li class="divider"></li>'.
				'</ul>'.
			'</div>'.
			'<div class="btn-group" style="float:right;">'.
				'<button type="button" class="btn btn-gold dropdown-toggle" data-toggle="dropdown" >'.
					'<i class="entypo-forward"></i>'.
				'</button>'.
			'</div>'.
		
			'<div class="form-group">'.
				'<h3>'.$question->content.'</h3>';
					if (count($question->questionChoices)>0/*$question->quantitative=='N'*/) { 
						echo '<div class="input-group">'.
						'<ul class="icheck-list">';
							foreach ($question->questionChoices as $choice) { 
				    			echo '<li>'.
				        				'<input class="icheck-10" type="radio" id="minimal-radio-1-10" name="question_choice_id" value="'.$choice->question_choice_id.'" required >'.
				        				'<label for="minimal-radio-1-10">'.$choice->content.'</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;'.
				        			'</li>';
				   			}
				  		echo '</ul>';
					echo '</div>';
					} else {
					echo '<div class="form-group">'.
							'<label for="field-ta" class="col-sm-3 control-label">Written Answer</label>'.
							'<textarea class="form-control autogrow" id="field-ta" name="user_answer" ></textarea>'.
						'</div>';
					}
						echo '<button type="submit" class="btn btn-green btn-icon">'.
								'Submit Answer'.
								'<i class="entypo-check"></i>'.
							'</button>'.
							//'<input type="submit" name="submit" value="submit">'.
						'</form></div>';		
	} else
	{
		echo '<div>There are currently no questions to answer</div>';
	}
?>

				<!--  </div>-->
				</div>
			</div>
			<div class="tab-pane" id="profile-2">
<form id='createQuestion'>			
<div class="arq-form">
						<h3>What is your question?</h3>
							<textarea name="content" class="form-control autogrow" id="field-ta" placeholder="Type here..."></textarea>
							<br>
							<div class="form-group">
								<label class="control-label">Quantitative (<a href="#">?</a>)</label>
								    <input type="checkbox" name="quantitative">
							</div>		
							<br>
							<div>
								<label>Multiple Choice (optional)</label>
								<br>
							<input type="text" class="form-control" id="field-1" placeholder="Choice 1" name="choice_1">
							<br>
							<input type="text" class="form-control" id="field-2" placeholder="Choice 2" name="choice_2">
							<br>
							<input type="text" class="form-control" id="field-3" placeholder="Choice 3" name="choice_3">
							<br>
							<input type="text" class="form-control" id="field-4" placeholder="Choice 4" name="choice_4">
							</div>
							<?php 
								if ($categories)
								{
									echo '<div style="margin:15px 0 15px 0;">'.
											'<label >Optionally Select One or More Categoriess for this Question</label>'.
										'</div><div>'.
										'<select style="width:200px;" multiple name=\'categories[]\'>';
									foreach ($categories as $category)
									{
										echo '<option value="'.$category['category_id'].'" >'.$category['display_name'].'</option>';
									}
									echo	'</select>'.
									'</div>';									
								}
							?>

						<br>
						<button type="submit" class="btn btn-green btn-icon">
						Submit Question
						<i class="entypo-check"></i>
						</button>
					</div>
</form>

			</div>
			<div class="tab-pane" id="messages-2">
				<?php $this->widget('QuestionsAsked'); ?>
			</div>
			<div class="tab-pane" id="settings-2">
				<?php $this->widget('QuestionsAnswered')?>
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
			<div class="panel-body" style="height: 433px;">
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
<script src="/assets/js/jquery.validate.min.js"></script>
<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.custom.min.js"></script>
<script type='text/javascript' src='/assets/js/arq.js'></script>