<?php

/**
 * This is the model class for table "note".
 *
 * The followings are the available columns in table 'note':
 * @property integer $note_id
 * @property integer $user_id
 * @property string $title
 * @property string $content
 * @property integer $image_id
 * @property string $date_created
 * @property string $date_modified
 * @property integer $show_on_frontpage
 * @property integer $stick_post
 * @property string $publish_date
 * @property string $publish_time
 * @property integer $ae_response_id
 * @property integer $status_id
 * @property integer $visibility_id
 * @property integer $is_active
 *
 * The followings are the available model relations:
 * @property User $user
 * @property NoteTag[] $noteTags
 */
class Note extends CActiveRecord
{
	/*
	 * I think this need to be defined
	 */
	public $title;
	public $show_on_frontpage;
	public $stick_post;
	public $publish_date;
	public $image_id;
	public $errorCode = "This is an error";
	public $is_active;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'note';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, image_id, show_on_frontpage, stick_post, ae_response_id, is_active', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>200),
			array('content, date_created, date_modified, publish_date, publish_time','safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('note_id, user_id, title, content, image_id, date_created, date_modified, show_on_frontpage, stick_post, publish_date, publish_time, ae_response_id, is_active', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'noteTags' => array(self::HAS_MANY, 'NoteTag', 'note_id'),
			'noteCategories'=>array(self::HAS_MANY, 'NoteCategory', 'note_id'),
			'noteStatus'=>array(self::BELONGS_TO,'NoteStatus','status_id'),
			'noteVisibility'=>array(self::BELONGS_TO,'NoteVisibility','visibility_id'),
			'AeResponse'=>array(self::BELONGS_TO,'AeResponse','ae_response_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'note_id' => 'Note',
			'user_id' => 'User',
			'title' => 'Title',
			'content' => 'Content',
			'image_id' => 'Image',
			'date_created' => 'Date Created',
			'date_modified' => 'Date Modified',
			'show_on_frontpage' => 'Show On Frontpage',
			'stick_post' => 'Stick Post',
			'publish_date' => 'Publish Date',
			'publish_time' => 'Publish Time',
			'ae_response_id' => 'AE Response',
			'is_active' => 'Is Active',
				
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

		$criteria->compare('note_id',$this->note_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('image_id',$this->image_id);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('date_modified',$this->date_modified,true);
		$criteria->compare('show_on_frontpage',$this->show_on_frontpage);
		$criteria->compare('stick_post',$this->stick_post);
		$criteria->compare('publish_date',$this->publish_date,true);
		$criteria->compare('ae_response_id',$this->ae_response_id);
		$criteria->compare('is_active',$this->is_active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Note the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function recentJournals($from_date,$to_date) {
		$list = Yii::app()->db->createCommand('select n.note_id,
				n.content,
				n.title,
				n.date_created,
				n.date_modified
				from note n
				join user u on n.user_id = u.user_id
				join note_status ns on ns.status_id = n.status_id
				where n.user_id = :user_id
				and ns.name= :status
				and publish_date <= date(:from_date) and publish_date>=date(:to_date)
				order by n.note_id desc')->
			bindValues(array(
				':user_id'=>Yii::app()->user->id,
				':status'=>'Published',
				':from_date'=>$from_date,
				':to_date'=>$to_date
		))->queryAll();
		return $list;
	}
}
