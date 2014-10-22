<?php

/**
 * This is the model class for table "event_value".
 *
 * The followings are the available columns in table 'event_value':
 * @property integer $event_value_id
 * @property integer $calendar_event_id
 * @property integer $event_definition_id
 * @property string $value
 *
 * The followings are the available model relations:
 * @property CalendarEvent $calendarEvent
 * @property EventDefinition $eventDefinition
 */
class EventValue extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'event_value';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('event_value_id', 'required'),
			array('event_value_id, calendar_event_id, event_definition_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('event_value_id, calendar_event_id, event_definition_id, value', 'safe', 'on'=>'search'),
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
			'calendarEvent' => array(self::BELONGS_TO, 'CalendarEvent', 'calendar_event_id'),
			'eventDefinition' => array(self::BELONGS_TO, 'EventDefinition', 'event_definition_id'),
			'eventNote'=>array(self::HAS_ONE,'EventNote','event_value_id'),
			'eventQuestion'=>array(self::HAS_ONE,'EventQuestion','event_value_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'event_value_id' => 'Event Value',
			'calendar_event_id' => 'Calendar Event',
			'event_definition_id' => 'Event Definition',
			'value' => 'Value',
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

		$criteria->compare('event_value_id',$this->event_value_id);
		$criteria->compare('calendar_event_id',$this->calendar_event_id);
		$criteria->compare('event_definition_id',$this->event_definition_id);
		$criteria->compare('value',$this->value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EventValue the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
