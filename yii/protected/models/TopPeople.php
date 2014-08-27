<?php

/**
 * This is the model class for table "top_people".
 *
 * The followings are the available columns in table 'top_people':
 * @property integer $top_people_id
 * @property integer $user_id
 * @property integer $ae_response_id
 * @property integer $ae_rank
 * @property string $ae_value
 * @property integer $score
 * @property integer $count
 *
 * The followings are the available model relations:
 * @property AeResponse $aeResponse
 */
class TopPeople extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'top_people';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, ae_response_id, ae_rank, score, count', 'numerical', 'integerOnly'=>true),
			array('ae_value', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('top_people_id, user_id, ae_response_id, ae_rank, ae_value, score, count', 'safe', 'on'=>'search'),
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
			'aeResponse' => array(self::BELONGS_TO, 'AeResponse', 'ae_response_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'top_people_id' => 'Top People',
			'user_id' => 'User',
			'ae_response_id' => 'Ae Response',
			'ae_rank' => 'Ae Rank',
			'ae_value' => 'Ae Value',
			'score' => 'Score',
			'count' => 'Count',
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

		$criteria->compare('top_people_id',$this->top_people_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('ae_response_id',$this->ae_response_id);
		$criteria->compare('ae_rank',$this->ae_rank);
		$criteria->compare('ae_value',$this->ae_value,true);
		$criteria->compare('score',$this->score);
		$criteria->compare('count',$this->count);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TopPeople the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
