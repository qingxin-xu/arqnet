<?php

/**
 * This is the model class for table "category".
 *
 * The followings are the available columns in table 'category':
 * @property integer $category_id
 * @property string $description
 * @property string $display_name
 * @property string $category_type
 * @property integer $counter_category_id
 * @property integer $parent_category_id
 * @property integer $is_active
 * @property string $date_created
 * @property string $date_modified
 *
 * The followings are the available model relations:
 * @property CategoryQuestion[] $categoryQuestions
 * @property CategoryScore[] $categoryScores
 * @property CategoryScoreAggregate[] $categoryScoreAggregates
 */
class Category extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('counter_category_id, parent_category_id, is_active', 'numerical', 'integerOnly'=>true),
			array('description, display_name', 'length', 'max'=>45),
			array('category_type', 'length', 'max'=>14),
			array('date_created, date_modified', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('category_id, description, display_name, category_type, counter_category_id, parent_category_id, is_active, date_created, date_modified', 'safe', 'on'=>'search'),
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
			'categoryQuestions' => array(self::HAS_MANY, 'CategoryQuestion', 'category_id'),
			'categoryScores' => array(self::HAS_MANY, 'CategoryScore', 'category_id'),
			'categoryScoreAggregates' => array(self::HAS_MANY, 'CategoryScoreAggregate', 'category_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'category_id' => 'Category',
			'description' => 'Description',
			'display_name' => 'Display Name',
			'category_type' => 'Category Type',
			'counter_category_id' => 'Counter Category',
			'parent_category_id' => 'Parent Category',
			'is_active' => 'Is Active',
			'date_created' => 'Date Created',
			'date_modified' => 'Date Modified',
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

		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('display_name',$this->display_name,true);
		$criteria->compare('category_type',$this->category_type,true);
		$criteria->compare('counter_category_id',$this->counter_category_id);
		$criteria->compare('parent_category_id',$this->parent_category_id);
		$criteria->compare('is_active',$this->is_active);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('date_modified',$this->date_modified,true);

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
	
	public function getCategories() 
	{
		$list = Yii::app()->db->createCommand(
		'select category_id,description,display_name from category')->queryAll();
		return $list;
	}
}
