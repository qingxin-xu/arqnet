<?php
class HeaderWidget extends CWidget {

	public function run() {
		$user = User::model()->findByAttributes(array('user_id'=>Yii::app()->user->Id));
		$image = Image::model()->findByAttributes(array('image_id'=>$user->image_id));
		$this->render('headerWidget', array(
				'user'=>$user,
				'image'=>$image,
				'powerbar'=>$this->controller->powerBarCalculation(Yii::app()->user->Id)
		));
	}
	
}