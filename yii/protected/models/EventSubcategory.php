<?php

/**
 * This is the model class for table "event_subcategory".
 *
 * The followings are the available columns in table 'event_subcategory':
 * @property integer $event_subcategory_id
 * @property integer $event_category_id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property EventDefinition[] $eventDefinitions
 * @property EventCategory $eventCategory
 */
class EventSubcategory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'event_subcategory';
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
			array('name', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('event_subcategory_id, event_category_id, name', 'safe', 'on'=>'search'),
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
			'eventDefinitions' => array(self::HAS_MANY, 'EventDefinition', 'event_subcategory_id'),
			'eventCategory' => array(self::BELONGS_TO, 'EventCategory', 'event_category_id'),
			//'eventDefinitions' => array(self::HAS_ONE, 'EventDefinition', 'event_subcategory_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'event_subcategory_id' => 'Event Subcategory',
			'event_category_id' => 'Event Category',
			'name' => 'Name',
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

		$criteria->compare('event_subcategory_id',$this->event_subcategory_id);
		$criteria->compare('event_category_id',$this->event_category_id);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EventSubcategory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
