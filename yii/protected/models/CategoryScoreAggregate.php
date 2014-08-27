<?php

/**
 * This is the model class for table "category_score_aggregate".
 *
 * The followings are the available columns in table 'category_score_aggregate':
 * @property integer $category_score_aggregate_id
 * @property integer $report_range_definition_id
 * @property integer $category_id
 * @property string $score
 *
 * The followings are the available model relations:
 * @property ReportRangeDefinition $reportRangeDefinition
 * @property Category $category
 */
class CategoryScoreAggregate extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'category_score_aggregate';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('report_range_definition_id, category_id', 'numerical', 'integerOnly'=>true),
			array('score', 'length', 'max'=>5),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('category_score_aggregate_id, report_range_definition_id, category_id, score', 'safe', 'on'=>'search'),
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
			'reportRangeDefinition' => array(self::BELONGS_TO, 'ReportRangeDefinition', 'report_range_definition_id'),
			'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'category_score_aggregate_id' => 'Category Score Aggregate',
			'report_range_definition_id' => 'Report Range Definition',
			'category_id' => 'Category',
			'score' => 'Score',
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

		$criteria->compare('category_score_aggregate_id',$this->category_score_aggregate_id);
		$criteria->compare('report_range_definition_id',$this->report_range_definition_id);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('score',$this->score,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CategoryScoreAggregate the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
