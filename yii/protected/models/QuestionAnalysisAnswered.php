<?php

/**
 * This is the model class for table "question_analysis_choice".
 *
 * The followings are the available columns in table 'question_choice':
 * @property integer $question_analysis_answered_id
 * @property integer $question_choice_id
 * @property integer $category_id
 * @property decimal(2,1) $value
 *
 * The followings are the available model relations:
 * @property Category $category
 * @property QuestionChoice $questionChoice
 */
class QuestionAnalysisAnswered extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'question_analysis_answered';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('question_choice_id, question_analysis_answered_id, category_id', 'numerical', 'integerOnly'=>true),
			array('value','numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('question_analysis_answered_id, question_choice_id, category_id, value', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
			'question' => array(self::BELONGS_TO, 'Question', 'question_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'question_analysis_answered_id' => 'Question Analysis Answered',
			'question_choice_id' => 'Question Choice',
			'category_id' => 'Category'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('question_analysis_answered_id',$this->question_analysis_answered_id);
		$criteria->compare('question_choice_id',$this->question_choice_id);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('value',$this->value);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return QuestionChoice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
