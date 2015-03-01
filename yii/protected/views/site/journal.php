<link rel="stylesheet" href="assets/js/jquery-ui/css/vader/jquery-ui.min.css">
<style type='text/css'>
input[type=file] {
    display:none
}
button {
	border:none;
	background:transparent;
}
/*
select {
	border:none;
	background:transparent;
	border:1px solid rgb(224,224,224);
	border-radius:3px;
}
*/
label[for='status'] {
	margin:0 33px 0 0;
}

label[for='visibility'] {
	margin:0 23px 0 0;
}

</style>
<script type="text/javascript" src="assets/js/tinymce/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">

function updateMsg( description,t ) {
	description
      .text( t );
 }
jQuery(document).ready(function($){

	tinymce.init({
	    selector: "textarea",
	    content_css:'assets/css/custom.css',
	    plugins:['jbimages'],
	    relative_urls:false
	 });
	$('input[name=publish_time]').timepicker();
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

			<ol class="breadcrumb bc-3">
						<li>
				<a href="dashboard"><i class="entypo-home"></i>Home</a>
			</li>
					<li>
			
							<a href="myJournals">My Journal</a>
					</li>
				<li >
			
							<a href="recentJournals">Journal Dashboard</a>
					</li>
					</ol>
<div class='boxHeader'><span class='word2'>Add New Journal</span></div>

			
<br />

<style>
.ms-container .ms-list {
	width: 135px;
	height: 205px;
}

.post-save-changes {
	float: right;
}

@media screen and (max-width: 789px)
{
	.post-save-changes {
		float: none;
		margin-bottom: 20px;
	}
}
</style>


<form  method="post" role="form" id="journalForm" enctype="multipart/form-data">
	<input type='hidden' name='journal_id' value='<?php if ($edit_journal) echo $edit_journal->note_id; ?>' />
	<!-- Title and Publish Buttons -->	<div class="row">
		<div class="col-sm-2 post-save-changes">
			<button type="submit" class="">
				<?php if ($edit_journal) echo 'Save';else echo 'Create';?>
				
			</button>
		</div>
		
		<div class="col-sm-10">
			<input type="text" class="form-control input-lg" name="post_title" placeholder="Journal title" value="<?php if ($edit_journal) echo $edit_journal->title; ?>"/>
		</div>
	</div>
	
	<br />
	
	<!-- WYSIWYG - Content Editor -->	<div class="row">
		<div class="col-sm-12">
			<textarea placeholder="Type your entry here" class="form-control wysihtml5" rows="18" data-stylesheet-url="assets/css/wysihtml5-color.css" name="post_content" id="post_content">
<?php 
				if ($edit_journal) {echo $edit_journal->content;}
				
?></textarea>
		</div>
	</div>
	<textarea style='display:none;' type='hidden' name='stripped_content'></textarea>
	<br />
	
	<!-- Metaboxes -->	
	<div class="row">
		
		<!-- Metabox :: Publish Settings -->		<div class="col-sm-4">
			
			<div class="panel panel-primary" data-collapsed="0">
		
				<div class="panel-heading">
					<div class="panel-title">
						Publish Settings
					</div>
					
					<div class="panel-options">
						<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
					</div>
				</div>
				
				<div class="panel-body">
<!--  					
					<div class="checkbox checkbox-replace">
						<input type="checkbox" id="chk-1" checked name="show_on_frontpage">
						<label>Show in frontpage</label>
					</div>
					
					<br />
					
					<div class="checkbox checkbox-replace">
						<input type="checkbox" id="chk-2" checked name="stick_post">
						<label>Stick this post</label>
					</div>
					
					<br />
-->			
					<p>Publish Date</p>
					<div class="input-group">
						<input name='publish_date' type="text" class="form-control datepicker" value="<?php if ($edit_journal) {echo date('D, d M Y',strtotime($edit_journal->publish_date) );} else {echo date('D, d M Y');}?>" >
						
						<div class="input-group-addon">
							<a href="#"><i class="entypo-calendar"></i></a>
						</div>
					</div>
					<p> Publish Time (click to change)</p>
					<div class='input-group'>
						<input name='publish_time' type="text" class="form-control bootstrap-timepicker input-small" value="<?php if ($edit_journal) {echo date('h:i a',strtotime($edit_journal->publish_time) ); } else {echo date('h:i a');} ?>" >
						<span class="add-on"><i class="icon-time"></i></span>
					</div>
					<div style='margin:10px 0;'>
					<?php 
					
					if ($note_status) {
						$selection = -1;
						if (is_object($edit_journal)) { $selection = $edit_journal->noteStatus->status_id; }
						
						echo "<label for='status'>Post Status</label>
						<select name='status' >
							<optgroup label='Post Status'>";
						foreach ($note_status as $ns) {
								echo "<option value='".$ns->status_id."'";
								/*
								if ($selection == $ns->status_id) echo 'selected ';
								*/
								if ($edit_journal) {
									if (strcmp($ns->name,$edit_journal->noteStatus->name)==0)
										echo 'selected';
								} else {
									if (strcmp($ns->name,'Published') == 0) echo 'selected ';
								}
								echo ">".$ns->name."</option>";
								
						}
						echo "</optgroup>
						</select>
						</div>
						<div>";
					}
					?>
					<?php 
					if ($note_visibility) {
						$selection = -1;
						if ($edit_journal) $selection = $edit_journal->noteVisibility->visibility_id;

						echo "<label for='visibility'>Post Visibility</label>
						<select name='visibility' >
							<optgroup label='Post Visibility'>";
						foreach ($note_visibility as $ns) {
								echo "<option value='".$ns->visibility_id."'";
								if ($selection == $ns->visibility_id) echo 'selected ';
								echo ">".$ns->name."</option>";
								
						}
						echo "</optgroup>
						</select>
						</div>
						<div>";
					}
					?>
					
					<label for="btn_myFileInput" style='width:95px;'>No file choosen...</label>
					<span class="btn btn-white btn-file">
					<button type='button' id="btn_myFileInput" style='outline:none;'>Select image</button>
			
					<input type="file" name='note_image' accept="image/*" />
					</span>
					</div>
					
				</div>
			
			</div>
			
		</div>
		
		<!-- Metabox :: Categories -->		<div class="col-sm-4">
			
			<div class="panel panel-primary" data-collapsed="0">
		
				<div class="panel-heading">
					<div class="panel-title">
						Categories
					</div>
					
					<div class="panel-options">
						<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
					</div>
				</div>
				
				<div class="panel-body">
					<?php 
						$category_selections;
					?>
					<?php foreach ($selectedCategories as $key=>$value) echo $key;?>
					<select multiple="multiple" name="categories[]" class="form-control multi-select">
					<?php foreach ($category_topics as $topic) { ?>
						<option value="<?php echo $topic->category_id; if (isset($selectedCategories[$topic->category_id])) echo '" selected '; else echo '"'; ?>><?php echo $topic->display_name; ?></option>
					<?php } ?>
					</select>
					
				</div>
			
			</div>
			
		</div>
		
		<!-- <div class="clear"></div> -->
		
		<!-- Metabox :: Tags -->		<div class="col-sm-4">
			
			<div class="panel panel-primary" data-collapsed="0">
		
				<div class="panel-heading">
					<div class="panel-title">
						Tags
					</div>
					
					<div class="panel-options">
						<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
					</div>
				</div>
				
				<div class="panel-body">
					
					<p>Add Post Tags</p>
					<input type="text" name="tags" value="" class="form-control tagsinput" />
					
				</div>
			
			</div>
			
		</div>
		
	</div>
	
</form>

<!-- Footer -->
<footer class="main">
	
		
	&copy; 2014 <strong>ArQnet</strong> Prototype Version 1.0
	
</footer>	
<link rel="stylesheet" href="assets/js/wysihtml5/bootstrap-wysihtml5.css">
<link rel="stylesheet" href="assets/css/custom.css">
<script src="/assets/js/jquery.validate.min.js"></script>
<!--  <script src='/assets/js/bootstrap.min.js'></script> -->
<script src='http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/additional-methods.js'></script>
<script src="/assets/js/journal.js"></script>
<script src="/assets/js/journal_autosave.js"></script>
<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.custom.min.js"></script>
<script src="assets/js/wysihtml5/wysihtml5-0.4.0pre.min.js"></script>
<script src="assets/js/wysihtml5/bootstrap-wysihtml5.js"></script>

</div>

<div id="myThinker" title="...">
  <p class="validateTips">Submitting Journal Entry</p> 
</div>
