<?php

/**
 * This is the model class for table "question_flag".
 *
 * The followings are the available columns in table 'question_flag':
 * @property integer $question_flag_id
 * @property integer $user_id
 * @property integer $question_id
 * @property integer $question_flag_type_id
 * @property timestamp $date_marked
 *
 * The followings are the available model relations:
 * 
 */
class QuestionFlag extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'question_flag';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('question_flag_id, user_id, question_id, question_flag_type_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('question_flag_id, user_id, question_id, question_flag_type_id', 'safe', 'on'=>'search'),
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
			'questions'=>array(self::HAS_MANY,'Question','question_id'),
			'questionFlagType'=>array(self::BELONGS_TO,'QuestionFlagType','question_flag_type_id'),
			'user'=>array(self::BELONGS_TO,'User','user_id'),
		);
		
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'question_flag_id' => 'Flag',
			'question_id' => 'Question',
			'question_flag_type_id' => 'Flag Type',
			'user_id' => 'User',
			'date_marked'=>'Date Flagged'
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

		$criteria->compare('question_flag_id',$this->question_flag_id);
		$criteria->compare('question_id',$this->question_id);
		$criteria->compare('question_flag_type_id',$this->question_flag_type_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('date_marked',$this->date_marked);


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Category the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
}
