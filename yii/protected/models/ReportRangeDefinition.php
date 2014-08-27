<?php

/**
 * This is the model class for table "report_range_definition".
 *
 * The followings are the available columns in table 'report_range_definition':
 * @property integer $report_range_definition_id
 * @property string $code
 * @property string $description
 * @property string $report_type
 * @property integer $running_day_range
 *
 * The followings are the available model relations:
 * @property CategoryScoreAggregate[] $categoryScoreAggregates
 */
class ReportRangeDefinition extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'report_range_definition';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('running_day_range', 'numerical', 'integerOnly'=>true),
			array('code, description', 'length', 'max'=>45),
			array('report_type', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('report_range_definition_id, code, description, report_type, running_day_range', 'safe', 'on'=>'search'),
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
			'categoryScoreAggregates' => array(self::HAS_MANY, 'CategoryScoreAggregate', 'report_range_definition_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'report_range_definition_id' => 'Report Range Definition',
			'code' => 'Code',
			'description' => 'Description',
			'report_type' => 'Report Type',
			'running_day_range' => 'Running Day Range',
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

		$criteria->compare('report_range_definition_id',$this->report_range_definition_id);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('report_type',$this->report_type,true);
		$criteria->compare('running_day_range',$this->running_day_range);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ReportRangeDefinition the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
