<?php 

	if ($questionsAsked)
	{
		echo '<table class="table table-striped">'.
				'<thead>'.
					'<tr>'.
						'<th>These are the questions you\'ve Asked</th>'.
						'<th>Date</th>'.
				'</thead>'.
				'<tbody>';
		
		foreach ($questionsAsked as $qa) {
			$nAnswers = $qa['nAnswers'].' people have answered this question';
			echo '<tr>'.
					'<td>'.
						'<span title="'.$nAnswers.'">'.$qa['content'].'</span>'.
					'</td>';
			echo 	'<td>'.
						$qa['date_created'].
					'</td>';
			echo '</tr>';
				 
		}
		
		echo '</table>';
						
	} else
	{
		echo "You have not asked any questions yet.";
	}
?>
<!--  
<table class="table table-striped">
			<thead>
				<tr>
					<th>Category</th>
					<th>These are the questions you've Asked</th>
					<th>Date</th>
				</tr>
			</thead>
			
			<tbody>
			 
				<tr>
					<td>School</td>
					<td>How many girlfriend/boyfriends did you have in Highschool?</td>
					<th>10/09/14</th>
				</tr>
				
				<tr>
					<td>Work</td>
					<td>Do you love or hate your job? And Why?</td>
					<th>10/10/14</th>
				</tr>
				
				<tr>
					<td>Love</td>
					<td>How many times a week do you like to have sex</td>
					<th>10/11/14</th>
				</tr>
			
			</tbody>
</table>
-->