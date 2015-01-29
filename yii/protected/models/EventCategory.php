<?php

/**
 * This is the model class for table "event_category".
 *
 * The followings are the available columns in table 'event_category':
 * @property integer $event_category_id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property EventSubcategory[] $eventSubcategories
 */
class EventCategory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'event_category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('event_category_id, name', 'safe', 'on'=>'search'),
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
			'eventSubcategories' => array(self::HAS_MANY, 'EventSubcategory', 'event_category_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
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
	 * @return EventCategory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getCategories()
	{
		$hash = array();
		$cmd = 'select c.name as Category,sc.name as SubCategory,d.parameter as type from event_subcategory sc join event_definition d on d.event_subcategory_id = sc.event_subcategory_id join event_category c on c.event_category_id=sc.event_category_id';
		$list = Yii::app()->db->createCommand($cmd)->queryAll();
		/*
		foreach ($list as $l)
		{
			if (!$hash[$l['Category']]) {
				$hash[$l['Category']] = array($l['SubCategory']=>a$l);
			} else
			{
				if ($hash[$l['Category']][$l['SubCategory']])
			}
		}
		*/
		return $list;
	}
	
	public static function _getCategories($cat)
	{
		$categories = array();
		foreach ($cat as $c)
		{
			$categories[$c->name]=array();
			foreach ($c->eventSubcategories as $esc)
			{
				$categories[$c->name][$esc->name] = array();
				$categories[$c->name][$esc->name]['capping_event']=$esc->cap_event;
				$categories[$c->name][$esc->name]['labels'] = array();
				foreach ($esc->eventDefinitions as $ed)
				{
					$categories[$c->name][$esc->name]['labels'][$ed->label] = array(
							'type'=>$ed->parameter,
							'label'=>$ed->label,
							'unit'=>array(),
							'name'=>$ed->event_definition_id
					);
					foreach ($ed->eventUnits as $eu) {
						array_push($categories[$c->name][$esc->name]['labels'][$ed->label]['unit'],array(
						'event_unit_id'=>$eu->event_unit_id,
						'name'=>$eu->name
						));
					}
				}
			}
		
		}
		return $categories;
	}
}
