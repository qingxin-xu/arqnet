<!-- 
<form enctype="multipart/form-data" method="post" action="/test">
<input type="file" name="image">
<input type="submit" name="submit" value="Submit">
</form>
-->

<!-- testing /createJournal
<form id="foo" enctype="multipart/form-data">
Title<br>
<input type="text" name="post_title" value="test note title"><br>
Content<br>
<textarea name="post_content">test note content</textarea><br>
Show on Frontpage<br>
<input type="checkbox" name="show_on_frontpage" checked><br>
Stick Post<br>
<input type="checkbox" name="stick_post" checked><br>
Publish Date<br>
					<div class="input-group">
						<input type="text" name="publish_date" class="form-control datepicker" value="Thu, 27 March 2014" data-format="D, dd MM yyyy">
						
						<div class="input-group-addon">
							<a href="#"><i class="entypo-calendar"></i></a>
						</div>
					</div><br>
Image<br>
<input type="file" name="image"><br>
<input type="submit" value="Submit">
</form>
-->

<form id="foo" enctype="multipart/form-data">
Content<br>
<textarea name="content"></textarea><br>
Quantitative<br>
<input type="checkbox" name="quantitative"><br>
Choice 1<br>
<input type="text" name="choice_1"><br>
Choice 2<br>
<input type="text" name="choice_2"><br>
Choice 3<br>
<input type="text" name="choice_3"><br>
Choice 4<br>
<input type="text" name="choice_4"><br>
<input type="submit" value="Submit">
</form>

<script type="text/javascript">
	//callback handler for form submit
	$("#foo").submit(function(e)
	{
	    //var postData = $(this).serializeArray();
	    var formData = new FormData($(this)[0]);
	    var formURL = $(this).attr("action");
	    $.ajax(
	    {
	        url : '/createQuestion',
	        type: "POST",
	        data : formData,
	        success:function(data, textStatus, jqXHR)
	        {
	            console.log('successful post');
	        },
	        error: function(jqXHR, textStatus, errorThrown)
	        {
	        	console.log('failed post');   
	        },
	        async: false,
	        cache: false,
	        contentType: false,
	        processData: false
	    });
	    e.preventDefault(); //STOP default action
	});
	 
	//$("#foo").submit(); //Submit  the FORM
</script>