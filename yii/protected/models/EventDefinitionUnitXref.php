<?php

/**
 * This is the model class for table "event_definition_unit_xref".
 *
 * The followings are the available columns in table 'event_definition_unit_xref':
 * @property integer $event_definition_id
 * @property integer $event_unit_id
 *
 * The followings are the available model relations:
 * @property EventDefinition $eventDefinition
 * @property EventUnit $eventUnit
 */
class EventDefinitionUnitXref extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'event_definition_unit_xref';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('event_definition_id, event_unit_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('event_definition_id, event_unit_id', 'safe', 'on'=>'search'),
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
			'eventDefinition' => array(self::BELONGS_TO, 'EventDefinition', 'event_definition_id'),
			'eventUnit' => array(self::BELONGS_TO, 'EventUnit', 'event_unit_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'event_definition_id' => 'Event Definition',
			'event_unit_id' => 'Event Unit',
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

		$criteria->compare('event_definition_id',$this->event_definition_id);
		$criteria->compare('event_unit_id',$this->event_unit_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EventDefinitionUnitXref the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
