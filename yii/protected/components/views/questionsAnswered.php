<?php 
	if ($questionsAnswered)
	{
		echo '<table class="table table-striped">'.
				'<thead>'.
					'<tr>'.
						'<th>Category</th>'.
						'<th>These are the questions you\'ve Answered</th>'.
						'<th>Your Answers</th>'.
						'<th>Date</th>'.
					'</tr>'.
				'</thead>'.
				'<tbody>';
		foreach ($questionsAnswered as $qa)
		{
			echo '<tr>'.
					'<td>'.
						$qa['category'].
					'</td>'.
					'<td>'.
						$qa['content'].
					'</td>'.
					'<td>';
			if ($qa['choice']) echo $qa['choice'];
			else echo $qa['user_answer'];
			echo		'</td>'.
					'<td>'.
						$qa['date_created'].
					'</td>'.						
				'</tr>';
		}
		echo	'</tbody>'.
			'</table>';
	} else
	{
		echo 'You have not ansered any questions yet.';
	}

?>
<!-- 
<table class="table table-striped">
	<thead>
		<tr>
			<th>Category</th>
			<th>These are the questions you've Answered</th>
			<th>Your Answers</th>
			<th>Date</th>
		</tr>
	</thead>
	
	<tbody>
		<tr>
			<td>Work</td>
			<td>How many girlfriend/boyfriends did you have in Highschool?</td>
			<th>3</th>
			<th>10/10/14</th>
		</tr>
		
		<tr>
			<td>Personal</td>
			<td>Do you love or hate your job? And Why?</td>
			<th>I love my job, because I get to be creative</th>
			<th>10/11/14</th>
		</tr>
		
		<tr>
			<td>Emotions</td>
			<td>How many times a week do you like to have sex</td>
			<th>At least 5 times</th>
			<th>10/12/14</th>
		</tr>
	</tbody>
</table>
 -->