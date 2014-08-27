<?php

/**
 * This is the model class for table "event_definition".
 *
 * The followings are the available columns in table 'event_definition':
 * @property integer $event_definition_id
 * @property integer $event_subcategory_id
 * @property string $parameter
 * @property string $comment
 * @property string $label
 *
 * The followings are the available model relations:
 * @property EventSubcategory $eventSubcategory
 * @property EventValue[] $eventValues
 */
class EventDefinition extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'event_definition';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('event_subcategory_id', 'numerical', 'integerOnly'=>true),
			array('parameter', 'length', 'max'=>100),
			array('comment', 'length', 'max'=>45),
			array('label', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('event_definition_id, event_subcategory_id, parameter, comment, label', 'safe', 'on'=>'search'),
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
			'eventSubcategory' => array(self::BELONGS_TO, 'EventSubcategory', 'event_subcategory_id'),
			'eventValues' => array(self::HAS_MANY, 'EventValue', 'event_definition_id'),
			'eventUnits'=>array(self::MANY_MANY,'EventUnit','event_definition_unit_xref(event_definition_id,event_unit_id)')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'event_definition_id' => 'Event Definition',
			'event_subcategory_id' => 'Event Subcategory',
			'parameter' => 'Parameter',
			'comment' => 'Comment',
			'label' => 'Label',
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
		$criteria->compare('event_subcategory_id',$this->event_subcategory_id);
		$criteria->compare('parameter',$this->parameter,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('label',$this->label,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EventDefinition the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
