<?php

/**
 * This is the model class for table "ae_response".
 *
 * The followings are the available columns in table 'ae_response':
 * @property integer $ae_response_id
 * @property integer $user_id
 * @property integer $words
 * @property integer $sentences
 * @property integer $hits
 * @property string $json_response
 * @property string $response_ts
 * @property string $date_created
 * @property string $date_modified
 * @property integer $is_active
 * @property string $source
 *
 * The followings are the available model relations:
 * @property User $user
 * @property CategoryScore[] $categoryScores
 * @property TopPeople[] $topPeoples
 * @property TopWords[] $topWords
 */
class AeResponse extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ae_response';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, words, sentences, hits, is_active', 'numerical', 'integerOnly'=>true),
			array('json_response, response_ts, date_created, date_modified', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ae_response_id, user_id, words, sentences, hits, json_response, response_ts, date_created, date_modified, is_active, source', 'safe', 'on'=>'search'),
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
			'categoryScores' => array(self::HAS_MANY, 'CategoryScore', 'ae_response_id'),
			'topPeoples' => array(self::HAS_MANY, 'TopPeople', 'ae_response_id'),
			'topWords' => array(self::HAS_MANY, 'TopWords', 'ae_response_id'),
			'note'=>array(self::HAS_ONE,'Note','ae_response_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ae_response_id' => 'Ae Response',
			'user_id' => 'User',
			'words' => 'Words',
			'sentences' => 'Sentences',
			'hits' => 'Hits',
			'json_response' => 'Json Response',
			'response_ts' => 'Response Ts',
			'date_created' => 'Date Created',
			'date_modified' => 'Date Modified',
			'is_active' => 'Is Active',
			'source' => 'Source',
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

		$criteria->compare('ae_response_id',$this->ae_response_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('words',$this->words);
		$criteria->compare('sentences',$this->sentences);
		$criteria->compare('hits',$this->hits);
		$criteria->compare('json_response',$this->json_response,true);
		$criteria->compare('response_ts',$this->response_ts,true);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('date_modified',$this->date_modified,true);
		$criteria->compare('is_active',$this->is_active);
		$criteria->compare('source',$this->source);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AeResponse the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getResponse($ae_response_id)
	{
		$list = Yii::app()->db->createCommand('
			select c.description,
				arc.value
			from ae_response_category arc
			join category c on arc.category_id = c.category_id
			where ae_response_id = :ae_response_id')->
			bindValues(array(
				':ae_response_id'=>$ae_response_id,
			))->queryAll();
		$res = new stdClass();
		if (count($list)==0) {
			return false;
		}
		foreach ($list as $data) {
			$res->{$data['description']} = $data['value'];
		}
		
		$list = Yii::app()->db->createCommand('
			select ae_rank,
				ae_value,
				score,
				count
			from top_words
			where ae_response_id = :ae_response_id')->
			bindValues(array(
				':ae_response_id'=>$ae_response_id,
			))->queryAll();
		if (count($list)==0) {
			return false;
		}
		foreach ($list as $data) {
			$res->{'topWords'.$data['ae_rank']} = $data['ae_value'];
			$res->{'topWordsCnt'.$data['ae_rank']} = $data['count'];
		}
		
		$list = Yii::app()->db->createCommand('
			select ae_rank,
				ae_value,
				score,
				count
			from top_people
			where ae_response_id = :ae_response_id')->
		bindValues(array(
				':ae_response_id'=>$ae_response_id,
		))->queryAll();
		if (count($list)==0) {
			return false;
		}
		foreach ($list as $data) {
			$res->{'topPeople'.$data['ae_rank']} = $data['ae_value'];
			$res->{'topPeopleCnt'.$data['ae_rank']} = $data['count'];
		}
		return $res;
	}
	
	public function getResponses($user_id)
	{
		$list = Yii::app()->db->createCommand('
			select ae_response_id
			from ae_response
			where user_id = :user_id
			order by response_ts asc')->
			bindValues(array(
				':user_id'=>$user_id,
			))->queryAll();
		$json_responses = array();
		foreach ($list as $get_ae_id) {
			if ($ae_response = self::getResponse($get_ae_id['ae_response_id'])) {
				array_push($json_responses, self::getResponse($get_ae_id['ae_response_id']));
			}
		}
		return $json_responses;		
	}
	
	public static function getResponseDate($duration,$from_date,$user_id)
	{
		if (!$duration) $duration = 30;
		if (!$from_date) $from_date = date('Y-m-d');
		$list = Yii::app()->db->createCommand(
			"select distinct(DATE(date_created)) as dates from ae_response 
			where date_created>=date_sub(date('".$from_date."'),interval :duration day)
			and DATE(date_created)<=date('".$from_date."')
			and user_id = :user_id
			order by(DATE(date_created)) DESC")->bindValues(
			array(':duration'=>$duration,':user_id'=>$user_id))->queryAll();
			
		$dates = array();
		foreach ($list as $d) {array_push($dates,$d['dates']);}
		$curdate = $from_date;
		if (count($dates)==0 || strcmp($dates[0],$curdate) != 0) array_unshift($dates,$curdate);
		return $dates;
	}
}
