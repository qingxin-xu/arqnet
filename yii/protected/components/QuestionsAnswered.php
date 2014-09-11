<?php
class QuestionsAnswered extends CWidget {
	
	public function run() {
		//$questionsAnswered = Question::getQuestionsAnswered(Yii::app()->user->Id);
		$this->render('questionsAnswered', array(
			'questionsAnswered'=>null
		));
	}
	
}
?>