<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $user_id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string $user_ip
 * @property integer $is_active
 * @property string $date_created
 * @property string $date_modified
 * @property string $birthday
 * @property string $gender
 * @property string $location_city
 * @property string $location_state
 * @property integer $relationship_status_id
 * @property integer $orientation_id
 * @property string $profile_image
 * @property string $hometown_city
 * @property string $hometown_state
 * @property string $about_me
 * @property string $meaning_of_life
 * @property string $interests
 * @property string $favorite_music
 * @property string $favorite_movies
 * @property string $favorite_books
 * @property string $favorite_tv_shows
 * @property string $favorite_quotes
 * @property string $website
 * @property string $twiiter_username
 * @property string $facebook_username
 * @property string $instagram_username
 * @property string $googleplus_username
 * @property string $location
 * @property string $ethnicity
 * @property integer $image_id
 * @property string $facebook_url
 * @property string $twitter_url
 * @property string $linkedin_url
 * @property string $gplus_url
 * @property string $secure_browsing
 * @property string $text_msg_login_notifications
 * @property string $email_login_notifications
 * @property integer $max_login_attempts
 * @property string $followers
 * @property string $who_can_contact_me
 * @property string $who_can_look_me_up
 * @property string $who_can_see_my_journals
 *
 * The followings are the available model relations:
 * @property AeResponse[] $aeResponses
 * @property Answer[] $answers
 * @property CalendarEvent[] $calendarEvents
 * @property Note[] $notes
 * @property Question[] $questions
 * @property QuestionAction[] $questionActions
 * @property TopWords[] $topWords
 * @property RelationshipStatus $relationshipStatus
 * @property Orientation $orientation
 * @property Image $image
 * @property UserEthnicity[] $userEthnicities
 */
class User extends CActiveRecord
{
	
	private $seed = 'aifwgeai7whe8fhaow8';
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('is_active, relationship_status_id, orientation_id, image_id, max_login_attempts', 'numerical', 'integerOnly'=>true),
			array('username, first_name, last_name, user_ip, location_city, hometown_city, twiiter_username, facebook_username, instagram_username, googleplus_username', 'length', 'max'=>45),
			array('email, profile_image, website, location, ethnicity', 'length', 'max'=>100),
			array('password', 'length', 'max'=>64),
			array('gender', 'length', 'max'=>1),
			array('location_state, hometown_state', 'length', 'max'=>5),
			array('facebook_url, twitter_url, linkedin_url, gplus_url', 'length', 'max'=>200),
			array('secure_browsing, text_msg_login_notifications, email_login_notifications', 'length', 'max'=>3),
			array('followers, who_can_contact_me, who_can_look_me_up, who_can_see_my_journals', 'length', 'max'=>7),
			array('date_created, date_modified, birthday, about_me, meaning_of_life, interests, favorite_music, favorite_movies, favorite_books, favorite_tv_shows, favorite_quotes', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, username, email, password, first_name, last_name, user_ip, is_active, date_created, date_modified, birthday, gender, location_city, location_state, relationship_status_id, orientation_id, profile_image, hometown_city, hometown_state, about_me, meaning_of_life, interests, favorite_music, favorite_movies, favorite_books, favorite_tv_shows, favorite_quotes, website, twiiter_username, facebook_username, instagram_username, googleplus_username, location, ethnicity, image_id, facebook_url, twitter_url, linkedin_url, gplus_url, secure_browsing, text_msg_login_notifications, email_login_notifications, max_login_attempts, followers, who_can_contact_me, who_can_look_me_up, who_can_see_my_journals', 'safe', 'on'=>'search'),
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
			'aeResponses' => array(self::HAS_MANY, 'AeResponse', 'user_id'),
			'answers' => array(self::HAS_MANY, 'Answer', 'user_id'),
			'calendarEvents' => array(self::HAS_MANY, 'CalendarEvent', 'user_id'),
			'notes' => array(self::HAS_MANY, 'Note', 'user_id'),
			'questions' => array(self::HAS_MANY, 'Question', 'user_id'),
			'questionActions' => array(self::HAS_MANY, 'QuestionAction', 'user_id'),
			'topWords' => array(self::HAS_MANY, 'TopWords', 'user_id'),
			'relationshipStatus' => array(self::BELONGS_TO, 'RelationshipStatus', 'relationship_status_id'),
			'orientation' => array(self::BELONGS_TO, 'Orientation', 'orientation_id'),
			'image' => array(self::BELONGS_TO, 'Image', 'image_id'),
			'userEthnicities' => array(self::HAS_MANY, 'UserEthnicity', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'User',
			'username' => 'Username',
			'email' => 'Email',
			'password' => 'Password',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'user_ip' => 'User Ip',
			'is_active' => 'Is Active',
			'date_created' => 'Date Created',
			'date_modified' => 'Date Modified',
			'birthday' => 'Birthday',
			'gender' => 'Gender',
			'location_city' => 'Location City',
			'location_state' => 'Location State',
			'relationship_status_id' => 'Relationship Status',
			'orientation_id' => 'Orientation',
			'profile_image' => 'Profile Image',
			'hometown_city' => 'Hometown City',
			'hometown_state' => 'Hometown State',
			'about_me' => 'About Me',
			'meaning_of_life' => 'Meaning Of Life',
			'interests' => 'Interests',
			'favorite_music' => 'Favorite Music',
			'favorite_movies' => 'Favorite Movies',
			'favorite_books' => 'Favorite Books',
			'favorite_tv_shows' => 'Favorite Tv Shows',
			'favorite_quotes' => 'Favorite Quotes',
			'website' => 'Website',
			'twiiter_username' => 'Twiiter Username',
			'facebook_username' => 'Facebook Username',
			'instagram_username' => 'Instagram Username',
			'googleplus_username' => 'Googleplus Username',
			'location' => 'Location',
			'ethnicity' => 'Ethnicity',
			'image_id' => 'Image',
			'facebook_url' => 'Facebook Url',
			'twitter_url' => 'Twitter Url',
			'linkedin_url' => 'Linkedin Url',
			'gplus_url' => 'Gplus Url',
			'secure_browsing' => 'Secure Browsing',
			'text_msg_login_notifications' => 'Text Msg Login Notifications',
			'email_login_notifications' => 'Email Login Notifications',
			'max_login_attempts' => 'Max Login Attempts',
			'followers' => 'Followers',
			'who_can_contact_me' => 'Who Can Contact Me',
			'who_can_look_me_up' => 'Who Can Look Me Up',
			'who_can_see_my_journals' => 'Who Can See My Journals',
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

		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('user_ip',$this->user_ip,true);
		$criteria->compare('is_active',$this->is_active);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('date_modified',$this->date_modified,true);
		$criteria->compare('birthday',$this->birthday,true);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('location_city',$this->location_city,true);
		$criteria->compare('location_state',$this->location_state,true);
		$criteria->compare('relationship_status_id',$this->relationship_status_id);
		$criteria->compare('orientation_id',$this->orientation_id);
		$criteria->compare('profile_image',$this->profile_image,true);
		$criteria->compare('hometown_city',$this->hometown_city,true);
		$criteria->compare('hometown_state',$this->hometown_state,true);
		$criteria->compare('about_me',$this->about_me,true);
		$criteria->compare('meaning_of_life',$this->meaning_of_life,true);
		$criteria->compare('interests',$this->interests,true);
		$criteria->compare('favorite_music',$this->favorite_music,true);
		$criteria->compare('favorite_movies',$this->favorite_movies,true);
		$criteria->compare('favorite_books',$this->favorite_books,true);
		$criteria->compare('favorite_tv_shows',$this->favorite_tv_shows,true);
		$criteria->compare('favorite_quotes',$this->favorite_quotes,true);
		$criteria->compare('website',$this->website,true);
		$criteria->compare('twiiter_username',$this->twiiter_username,true);
		$criteria->compare('facebook_username',$this->facebook_username,true);
		$criteria->compare('instagram_username',$this->instagram_username,true);
		$criteria->compare('googleplus_username',$this->googleplus_username,true);
		$criteria->compare('location',$this->location,true);
		$criteria->compare('ethnicity',$this->ethnicity,true);
		$criteria->compare('image_id',$this->image_id);
		$criteria->compare('facebook_url',$this->facebook_url,true);
		$criteria->compare('twitter_url',$this->twitter_url,true);
		$criteria->compare('linkedin_url',$this->linkedin_url,true);
		$criteria->compare('gplus_url',$this->gplus_url,true);
		$criteria->compare('secure_browsing',$this->secure_browsing,true);
		$criteria->compare('text_msg_login_notifications',$this->text_msg_login_notifications,true);
		$criteria->compare('email_login_notifications',$this->email_login_notifications,true);
		$criteria->compare('max_login_attempts',$this->max_login_attempts);
		$criteria->compare('followers',$this->followers,true);
		$criteria->compare('who_can_contact_me',$this->who_can_contact_me,true);
		$criteria->compare('who_can_look_me_up',$this->who_can_look_me_up,true);
		$criteria->compare('who_can_see_my_journals',$this->who_can_see_my_journals,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function authenticate()
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			if($this->_identity->authenticate())
				Yii::log('authenticated!!!');
		}
	}
	
	public function verifyPassword($pw)
	{
		return $this->password == md5($this->seed.$pw);
	}
	
	public function setPassword($pw)
	{
		$this->password = md5($this->seed.$pw);
		$this->save();
	}
	
}
