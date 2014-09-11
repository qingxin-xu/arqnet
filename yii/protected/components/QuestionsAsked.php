<?php
class QuestionsAsked extends CWidget {
	
	public function run() {
		//$questionsAsked = Question::getQuestionsAsked(Yii::app()->user->Id);
		$this->render('questionsAsked', array(
			'questionsAsked'=>null
		));
	}
	
}
?>