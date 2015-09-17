<?php

class SiteController extends Controller
{
	/* The maximum word count for the power bar */
	var $powerbarMax = 600;
	/* How many days to average over for the power bar calculation */
	var $powerbarDays = 5;
	
    /* How many times a question can be flagged before it is taken out of the pool of questions */
    var $flagThreshold = 10;
    var $defaultQuestionStatus = 'Submitted';
    /* for journal pagers, how many journals to request at a time */
    var $RECENTPOSTCOUNT = 5;

    /* 
	 * The default range of dates to show on the dashboard slider
	 */
    var $dashboardRangeDefault = 30;
    /* 
	 * probably should be deprecated 
	 * These were used for encoding top people before sending to AE
	 */
    var $AEEncodings = array(
        '$' => 'ARQDSARQ',
        '!' => 'ARQWMARQ',
        '_' => 'ARQUSARQ',
        '-' => 'ARQDSHRQ',
        '"' => 'ARQDQARQ',
        '#' => 'ARQHTARQ',
        '%' => 'ARQPERARQ',
        '&' => 'ARQAMPARQ',
        '(' => 'ARQLPARQ',
        ')' => 'ARQRPARQ',
        '*' => 'ARQASKARQ',
        ',' => 'ARQCMAARQ',
        '.' => 'ARQPRDARQ',
        '/' => 'ARQSLARQ',
        ':' => 'ARQCLNARQ',
        ';' => 'ARQSCNARQ',
        //'<'=>'ARQLESSTHANARQ',
        '=' => 'ARQEQARQ',
        //'>'=>'ARQGREATERARQ',
        '?' => 'ARQQMARQ',
        '@' => 'ARQATARQ',
        '[' => 'ARQLSBARQ',
        "\\" => 'ARQBSARQ',
        ']' => 'ARQRSBARQ',
        '^' => 'ARQCTARQ',
        '`' => 'ARQAAARQ',
        '{' => 'ARQLCBARQ',
        '|' => 'ARQPARQ',
        '}' => 'ARQRCBARQ',
        '~' => 'ARQTARQ'
    );

    var $dashboard_topics = array(
        'love',
        'sex',
        'family',
        'ambition',
        'leisure',
        'beliefs',
        'health',
        'work',
        'money',
        'school',
        'home',
        'death',
        'self',
        'food',
        'fitness',
        'other'
    );
    var $aer_categories = array(
        'negative',
        'positive',
        'thinking',
        'feeling',
        'fantasy',
        'reality',
        'passive',
        'proactive',
        'disconnected',
        'connected',
        'happy',
        'anxious',
        'sad',
        'angry',
        'love',
        'sex',
        'family',
        'ambition',
        'leisure',
        'religion',
        'health',
        'work',
        'money',
        'school',
        'home',
        'death',
    );

    //var $test = 'hello world';

    /*public function __construct()
	{
		parent::__construct();
	}*/

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        if (Yii::app()->request->getQuery('r') == 'gii') {
            $this->render('index');
        } else {
            if (Yii::app()->user->isGuest) {
                $this->redirect('login');
            } else {
                $this->redirect('dashboard');
            }
        }
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact()
    {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                    "Reply-To: {$model->email}\r\n" .
                    "MIME-Version: 1.0\r\n" .
                    "Content-Type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $model = new LoginForm;

        // if it is ajax validation request
        //if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
        //{
        //	echo CActiveForm::validate($model);
        //	Yii::app()->end();
        //}

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->layout = 'arqLayout1';
        $this->setPageTitle('Login');
        $this->render('login', array('model' => $model));
    }

    public function beforeAction($action = null)
    {
        $action_requiring_auth = array(
            'dashboard',
            'profile',
            'journal',
            'settings',
            'calendar',
            'arq',
            'recentJournals',
            'myJournals',
        );

        if (in_array(Yii::app()->controller->action->id, $action_requiring_auth) and Yii::app()->user->isGuest) {
            $this->redirect('login');
            Yii::app()->end();
        }

        return true;
    }

    /**
     * Get location autocomplete data.
     */
    public function actionSearchLocal()
    {
        if ($_GET['name'] != "") {
            $city = $_GET['name'];
        }

        $criteria = new CDbCriteria();

        $criteria->addSearchCondition('city', $city);

        $location = Cities::model()->findAll($criteria);
        foreach ($location as $val) {
            echo $val['city'] . ', ' . $val['state_code'] . "\n";

        }


    }

    public function actionRegister()
    {
        $this->layout = 'arqLayout1';
        $this->setPageTitle('Register');
        $ethnicity = Ethnicity::model()->findAll();
        $now = Date("Y");
        //70岁封顶 10岁开始
        for ($i = $now - 70; $i <= $now - 10; $i++) {
            $years[] = $i;
        }

        $this->render('register', array('ethnicity' => $ethnicity,
            'years' => $years));
    }

    public function actionResetPassword()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }

        header('Content-type: application/json');

        $userEmail = Yii::app()->request->getPost('email', '');
        if (!$userEmail) {
            echo CJSON::encode(array(
                'success' => -999,
                'msg' => 'No email specified'
            ));
            Yii::app()->end();
        }

        $user = User::model()->findByAttributes(array('email' => $userEmail));
        if (!$user) {
            echo CJSON::encode(array(
                'success' => -999,
                'msg' => 'No account with the specified email was found'
            ));
            Yii::app()->end();
        }
        $pass = Yii::app()->epassgen->generate(10, 2, 3, 2);
        $user->setPassword($pass);
        $settings = array('resetPassword' => $pass, 'username' => $user->username);
        $this->SendMail($userEmail, 'passwordReset', $settings);
        echo CJSON::encode(array(
            'success' => 1,
            'msg' => 'An email with your reset password has been sent to you',
            'redirect' => '/login'
        ));
        Yii::app()->end();

    }

    public function actionDashboard()
    {
        $this->layout = 'arqLayout2';
        $this->setPageTitle('Dashboard');

        // The unique dates with ae_responses from today to 30 days ago
        $user_id = Yii::app()->user->id;

        $myCurrent = date('Y-m-d');
        $default_range = $this->dashboardRangeDefault;

        $showTracker = null;
        if (isset($_GET['goto'])) {
            $showTracker = true;
            $today = date('Y-m-d', strtotime($_GET['goto']));
        } else {
            $today = date('Y-m-d');
        }
        $day = 24 * 3600;
        $yesterday = date('Y-m-d', strtotime($today) - $day);
        $end_date = date('Y-m-d', strtotime($today) - 30 * $day);
        $current_time = date('h:i a');
        $activities = $this->calendarActivities($end_date, $today, $user_id);

        //$dashboardData = array('eventData'=>array(),'trackerData'=>array());//$this->getDashboardData(array('minDate'=>$this->getEarliestNoteDate())/*30, null, $today*/, $user_id);
        //$dashboardData = $this->getDashboardData(30, null, $today, $user_id);
        // Get all tracker and AE data for the past 90 days
        $dashboardData = $this->_getDashboardData(90);
        $event_units = EventUnit::model()->findall();
        $units = array();
        foreach ($event_units as $eu) {
            array_push($units, $eu->name);
        }

        /*
		 * Question
		 */

        $categories = $this->getQuestionCategories();
        $randomQuestion = null;
        while (!$randomQuestion) {
            $randInt = rand(0, count($categories) - 1);
            $randomQuestion = $this->getRandomQuestionByCategory($categories{$randInt});
        }

        $this->render('dashboard',
            array(
                '_pairs' => array('thinking' => 'feeling', 'reality' => 'fantasy', 'negative' => 'positive', 'proactive' => 'passive', 'connected' => 'disconnected'),
                'moods' => array("angry", "happy", "sad", "anxious"),
                'responseCount' => $this->dashboardRangeDefault,//count($dashboardData{'eventData'}),//$responses{'responseCount'},//$sliderCount,
                'avg' => $dashboardData{'eventData'},//$dashboardData{'eventData'},//$responses{'avg'},//$avgResponses,
                /* The default number of days to display on main slider */
                'default_range' => $default_range,
                'topics' => $this->dashboard_topics,
                'end_date' => date('Y-m-d'),
                'post_status' => NoteStatus::model()->findByAttributes(array('name' => 'Published')),
                'post_visibility' => NoteVisibility::model()->findByAttributes(array('name' => 'Public')),
                'recentActivity' => $this->recentActivities($today, $yesterday),
                'activities' => $activities,
                'trackerData' => $dashboardData{'trackerData'},//$dashboardData{'trackerData'},
                //'trackerDates'=>$trackerInfo{'trackerDates'},
                'event_units' => $units,
                'randomQuestion' => $randomQuestion,
                'question_flags' => $this->getQuestionFlags(),
                'current_time' => $current_time,
                'showTracker' => $showTracker,
            )
        );
    }

    /**
     * Change date range for slider on dashboard
     * @throws CHttpException
     */
    public function actionChangeDateRange()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        header('Content-type: application/json');
        $user_id = Yii::app()->user->id;
        $start_date = Yii::app()->request->getPost('start_date', '');
        $end_date = Yii::app()->request->getPost('end_date', '');

        if (!$start_date || !$end_date) {
            echo CJSON::encode(array(
                'success' => -99,
                'msg' => 'Both a start and end date must be specified'
            ));
            Yii::app()->end();
        }
        $start_date = date('Y-m-d', strtotime($start_date));
        $end_date = date('Y-m-d', strtotime($end_date));

        $diff = abs(strtotime($end_date) - strtotime($start_date));
        $diff = $diff / (24 * 60 * 60);
        #MyStuff::Log('CHANGE DATE RANGE '.$diff.' '.$end_date);
        //$responses = $this->getDashboardResponses($diff,$end_date,$user_id);
        //$dashboardData = $this->getDashboardData($diff, $start_date, $end_date, $user_id);
        $dashboardData = $this->getDashboardData(array('duration'=>$diff,'from_date'=>$end_date,'to_date'=>$start_date),$user_id);
        echo CJSON::encode(array(
            'success' => 1,
            'responses' => $dashboardData{'eventData'},
            'responseCount' => count($dashboardData{'eventData'}),
            'trackerData' => $dashboardData{'trackerData'},
        ));
        Yii::app()->end();
    }

    public function actionGetDashboardData() {
    	if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
    		throw new CHttpException('403', 'Forbidden access.');
    	}
    	header('Content-type: application/json');
    	$user_id = Yii::app()->user->id;
    	$from_date = Yii::app()->getRequest()->getQuery('from_date');
   		$duration = Yii::app()->getRequest()->getQuery('duration');
   		if (!$duration) $duration = 90;
    	$dashboardData = $this->_getDashboardData($duration,$from_date);
    	MyStuff::Log("DDData");MyStuff::Log($dashboardData);
    	echo CJSON::encode(array(
    			'success' => 1,
    			'responses' => $dashboardData{'eventData'},
    			'trackerData' => $dashboardData{'trackerData'},
    	));
    	Yii::app()->end();
    }
    
    
    public function actionProfile()
    {
        $this->layout = 'arqLayout2';
        $this->setPageTitle('Profile');
        $user = User::model()->findByAttributes(
            array('user_id' => Yii::app()->user->id));

        $image = '';

        $me = array();
        if ($user) {
            foreach ($user as $key => $value) {
                if (strcmp('password', $key) != 0 && strcmp('user_id', $key) != 0)
                    $me{$key} = $value;
            }
            $image = Image::model()->findByAttributes(array('image_id' => $user->image_id));
        }

        $o = Orientation::model()->findAll();
        $orientations = array();
        $r = RelationshipStatus::model()->findAll();
        $relationships = array();
        foreach ($o as $orientation) {
            array_push($orientations, array('id' => $orientation->orientation_id, 'description' => $orientation->description));
        }
        foreach ($r as $relationship) {
            array_push($relationships, array('id' => $relationship->relationship_status_id, 'description' => $relationship->description));
        }
        $this->render('profile', array(
            'orientations' => $orientations,
            'relationships' => $relationships,
            'profile' => $me,
            'image' => $image
        ));
    }

    public function actionJournal()
    {
        $journal_id = 0;
        if (isset($_GET['journal_id'])) {
            $journal_id = $_GET['journal_id'];
        }
        $edit_journal = null;
        $selectedCategories = array();
        if ($journal_id > 0) {
            $edit_journal = Note::model()->with('noteCategories', 'noteTags', 'noteStatus', 'noteVisibility')->findbyPk($journal_id);
            if ($edit_journal) {
                foreach ($edit_journal['noteCategories'] as $c) {
                    $selectedCategories[$c->category_id] = 1;
                }
            }
        }
        $this->layout = 'arqLayout2';
        $this->setPageTitle('Journal');
        $topics = Category::model()->findAllByAttributes(
            array('category_type' => 'mood')
        );

        /* pare down topic model objects to those defined in this.dashboard_topics */
        $journal_topics = array();
        foreach ($topics as $topic) {
            foreach ($this->dashboard_topics as $dbtopic) {
                if (strcmp($topic->description, $dbtopic) == 0) {
                    array_push($journal_topics, $topic);
                    continue;
                }
            }
        }

        $this->render('journal',
            array(
                'category_topics' => $journal_topics,
                'note_status' => NoteStatus::model()->findAll('t.name!=:_name', array(':_name' => 'In Queue')),
                'note_visibility' => NoteVisibility::model()->findAll(),
                'edit_journal' => $edit_journal,
                'selectedCategories' => $selectedCategories
            )
        );
    }

    public function actionSettings()
    {
        $user = User::model()->findByAttributes(
            array('user_id' => Yii::app()->user->id)
        );
        $cat = EventCategory::model()->with('eventSubcategories')->findAll('t.name=:_name', array(':_name' => 'Milestones'));
        $milestones = EventCategory::_getCategories($cat);
        $is_bound = BindingAccount::model()->findAllByAttributes(array('arq_id' => Yii::app()->user->id));
        //被绑定的第三方账户链接
        $third_part_account = null;
        if (!empty($is_bound)) {
            $third_part_account = $is_bound[0]['third_part_account'];
        }
        $userIcon = Image::model()->findByAttributes(
            array('image_id' => $user['image_id'])
        );
        if (isset($userIcon['path'])) {
            $fromFacebook = strpos($userIcon['path'], "https://");
            if ($fromFacebook === false) {
                $newImage = explode(DIRECTORY_SEPARATOR, $userIcon['path']);
                $userIcon['path'] = "/" . $newImage[1] . '/' . $newImage[2] . '/' . $newImage[3] . '/' . $newImage[5];
            }
        }
        $image_path = null;
        if (!empty($user->image_id)) {
            $image_path = Image::model()->findAllByPk($user->image_id);
        }
        $image_path = $image_path[0]['path'];
        /*	if ($image_path && $image_path[0]['path']) {
			$fromFacebook = strpos($image_path[0]['path'], "https://");
			if ($fromFacebook === false) {
				$newImage = explode("\\", $image_path[0]['path']);					
				$image_path = "/" . $newImage[1] . '/' . $newImage[2] . '/' . $newImage[3] . '/' . $newImage[5];
			} else{ 
				$image_path = $image_path[0]['path'];
			}
		} else {
			$image_path = "";
		}
		*/
        $this->layout = 'arqLayout2';
        $this->setPageTitle('Settings');
        $this->render('settings', array(
            'user' => $user,
            'milestones' => $milestones,
            'is_bound' => $is_bound,
            'third_part_account' => $third_part_account,
            'is_auto' => isset($is_bound[0]['auto_update']) ? $is_bound[0]['auto_update'] : "",
            'image_path' => $image_path
        ));
    }

    /**
     * unlink the bound account from settings page
     */
    public function actionUnlink()
    {
        $unlinkId = Yii::app()->getRequest()->getQuery('param');
        $user_id = Yii::app()->user->id;
        switch ($unlinkId) {
            case 1:
                BindingAccount::model()->deleteAllByAttributes(array('arq_id' => $user_id));

                $this->redirect("/settings");
                break;
            default:
                $this->redirect("/settings");

        }
    }

    /**
     * facebook link account auto update notes in the back_end
     */
    public function actionAutoUpdate()
    {
        $is_auto_update = Yii::app()->request->getPost('auto_update', '');
        $third_party = Yii::app()->request->getPost('third_party', '');
        if ($third_party) {
            //判断该账户是否已经绑定相关第三方账户
            $is_bound = BindingAccount::model()->findByAttributes(array('arq_id' => Yii::app()->user->id, 'third_party' => $third_party));
            if ($is_bound) {
                //更新autoflag
                BindingAccount::model()->updateByPk($is_bound['binding_account_id'], array('auto_update' => $is_auto_update == 'true' ? 1 : 0));
                echo CJSON::encode(array(
                    'success' => 1,
                    'error' => ''
                ));
            }

        } else {
            echo CJSON::encode(array(
                'success' => 0,
                'error' => 'Operation Failed'
            ));
        }
    }

    public function diffViewDate($data,$view, $drag=null)
    {
    	

        if (!empty($data)) {
            foreach ($data as $key => $YourNotes) {
	    	if($drag) {
		    	$notesFrom = "Track";
				$YourNotes['title'] = $YourNotes['value'];
				$day_event = explode(" ", $YourNotes['start_date']);
				$myEvents[$key]['event_date'] = $YourNotes['start_date'];
				$myEvents[$key]['day_event'] = $day_event[0];
				$myEvents[$key]['event_type'] = 'typeInEvents';
				$myEvents[$key]['event_name'] = strip_tags($YourNotes['value']);
				$myEvents[$key]['description'] = strip_tags($YourNotes['value']);
				$myEvents[$key]['note_id'] = $YourNotes['calendar_event_id'];
				$myEvents[$key]['event_name'] = $YourNotes['count(*)'];
				$myEvents[$key]['notesFrom'] = $notesFrom;
			} else {
				if (empty($YourNotes['title'])) {
		        	$YourNotes['title'] = $YourNotes['content'];
				 	$myEvents[$key]['description'] = "goto week view to see more";
		        }
		        $yourImage = "";
		        $notesFrom = "arq";
		        if (!empty($YourNotes['fb_message_id'])) {
		        	$notesFrom = "facebook";
		        }
		        if (!empty($YourNotes['fb_image_ids'])) {
		        	$yourImage = Image::model()->findByAttributes(array('image_id' => $YourNotes['fb_image_ids']));
		        }
				$yourVideo = "";
		        if (!empty($YourNotes['fb_video_ids'])) {
		                $yourVideo = Image::model()->findByAttributes(array('image_id' => $YourNotes['fb_video_ids']));
		        }
		        $myEvents[$key]['videos'] = empty($yourVideo) ? "" : $yourVideo['path'];
		        $myEvents[$key]['images'] = empty($yourImage) ? "" : $yourImage['path'];
		        $day_event = explode(" ", $YourNotes['date_created']);
		        $myEvents[$key]['event_date'] = $YourNotes['date_created'];
		        $myEvents[$key]['day_event'] = $day_event[0];
		        $myEvents[$key]['event_type'] = 'typeInEvents';
		        $myEvents[$key]['event_name'] = strip_tags($YourNotes['title']);
		        $myEvents[$key]['description'] = strip_tags($YourNotes['content']);
				$myEvents[$key]['note_id'] = $YourNotes['note_id'];
		        if($view=="month") {
		                $myEvents[$key]['event_name'] = $YourNotes['count(*)'];
		                $myEvents[$key]['description'] = "goto week view to see more";
		                        /*if ($YourNotes['count(*)'] == 1) {
		                            $myEvents[$key]['event_name'] = strip_tags($YourNotes['title']);
		                            $myEvents[$key]['description'] = strip_tags($YourNotes['content']);
		                        }*/
		        }
		    
		        $myEvents[$key]['notesFrom'] = $notesFrom;
		    	
		    }
            }
        } else {
			$myEvents = array();
		}

        $eventsHash = array('tracker' => array(), 'events' => array(), 'other' => array());
       
        //判断notes来源以及类型
                foreach ($myEvents as $ev) {
                    if (strcmp($ev['event_type'], 'tracker') == 0) {
                        array_push($eventsHash['tracker'], $ev);
                    } else if (strcmp($ev['event_type'], 'events') == 0) {
                        array_push($eventsHash['events'], $ev);
                    } else {
                        array_push($eventsHash['other'], $ev);
                    }
                }
        	
        return $eventsHash;
    }


    /*
	* this function need to be optimized by daniel
	*/
    public function actionCalendar()
    {

        $user_id = Yii::app()->user->Id;
        $events = array();//CalendarEvent::getEvents(Yii::app()->user->Id);
        $_events = CalendarEvent::model()->with('eventValues')->findAll('t.user_id=:_user_id', array(':_user_id' => $user_id));

        $eventData = array();//$this->getCalendarEventData();
        $milestones = $this->getMilestoneEvents();
        $cat = EventCategory::model()->with('eventSubcategories', 'eventSubcategories.eventDefinitions')->findAll('t.name!=:_name and t.name!=:_other', array(':_name' => 'Milestones', ':_other' => 'ARQ'));
        /* We have to unroll the category-subcategory->defintion map
		 * to get it to render in JSON
		 * Otherwise we would have to do multiple SQL queries
		 */
        $categories = EventCategory::_getCategories($cat);

        /* Navigate to specific date */
        $atDate = null;
        $end_date = null;
        if (isset($_GET['atDate'])) {
            $atDate = $_GET['atDate'];
            $end_date_ = $this->getFirstDayOfNextMonth(str_replace('_', '-', $atDate));
        } else {
            $end_date = $this->getFirstDayOfNextMonth(date('Y-m-d'));
        }

        $start_date = date('Y-m-d', strtotime('-1 month', strtotime($end_date)));
        $myEvents = $this->calendarActivities($start_date, $end_date, $user_id);
		$myEvents = array();
	
//var_dump($myEvents);exit;
        //todo add by daniel
        //$events = CalendarEvent::getEvents(Yii::app()->user->Id);

        $userId = Yii::app()->user->Id;

        $allYourNotesForWeek = Note::model()->findAllByAttributes(array('user_id' => $userId));
        $eventsHashForWeek = $this->diffViewDate($allYourNotesForWeek, 'week');
	


        $events = array();
        $sqlForFB = "select *, count(*)
						from note
						where user_id=$user_id and fb_message_id is not null
						group by DATE_FORMAT(date_created,'%Y-%m-%d')
						";
        $allYourNotesForFB = Yii::app()->db->createCommand($sqlForFB)->queryAll();
        $eventsHashForFB = $this->diffViewDate($allYourNotesForFB,'month');
	
	$sqlForArq = "select *, count(*)
				from note
				where user_id=$user_id and fb_message_id is null
				group by DATE_FORMAT(date_created,'%Y-%m-%d')
							";
	$allYourNotesForArq = Yii::app()->db->createCommand($sqlForArq)->queryAll();
	$eventsHashForArq = $this->diffViewDate($allYourNotesForArq,'month');
	
	$eventsHash = array_merge_recursive($eventsHashForFB, $eventsHashForArq);
	//var_dump($eventsHash);exit;
	
	//month view data drag event 
	$sqlForDrag = "select *, count(*) from calendar_event as c LEFT JOIN event_value as e 
		      	on c.calendar_event_id = e.calendar_event_id
			where c.user_id=$user_id
			group by DATE_FORMAT(c.start_date,'%Y-%m-%d')";
			
	$allYourNotesForDrag = Yii::app()->db->createCommand($sqlForDrag)->queryAll();
	$eventsHashForDrag = $this->diffViewDate($allYourNotesForDrag,'month','drag');
	$eventsHash = array_merge_recursive($eventsHash, $eventsHashForDrag);
        if (isset($userIcon['path'])) {
            $fromFacebook = strpos($userIcon['path'], "https://");
            if ($fromFacebook === false) {
                $newImage = explode("\\", $userIcon['path']);
                $userIcon['path'] = "/" . $newImage[1] . '/' . $newImage[2] . '/' . $newImage[3] . '/' . $newImage[5];
            }
        }


        $this->layout = 'arqLayout2';
        $this->setPageTitle('Calendar');

        $this->render('calendar', array(
            'data' => $eventsHash,
            'dataForWeek' => $eventsHashForWeek,
            'goto' => $atDate,
            'eventData' => $eventData,
            'milestones' => $milestones,
            'categories' => $categories,
            'myEvents' => $myEvents,
	    'third_part' => '',
        ));

//		$this->render('calendar',array(
//				'categories'=>$categories,
//				'myEvents'=>$myEvents,
//				'goto'=>$atDate,
//				'eventData'=>$eventData,
//				'milestones'=>$milestones
//		));
    }

    /*
	 * Service to return cappable events based on the 
	 * input capping event
	 * 
	 * this probably just feeds a drop down list
	 */
    public function actionCappableEvents()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        header('Content-type: application/json');
        $user_id = Yii::app()->user->id;
        if (!$user_id) {
            echo CJSON::encode(array(
                'success' => -1,
                'msg' => 'cannot verify user'
            ));
            Yii::app()->end();
        }

        $cap_date = Yii::app()->request->getPost('cap_date', '');
        /* This is obsolete because of the new fullcalendar plugin we are now using 
		if ($cap_date) {
			$cap_date = DateTime::createFromFormat('Y-m-d\TH:i:s.uZ',$cap_date);
			$cap_date = $cap_date->format('Y-m-d');
		} else {
			echo CJSON::encode(array(
					'success'=>-2,
					'msg'=>'Ending date not specified'
			));
			Yii::app()->end();			
		}
		*/
        if (!$cap_date) {
            echo CJSON::encode(array(
                'success' => -2,
                'msg' => 'Ending date not specified'
            ));
            Yii::app()->end();
        }
        $defn_label = Yii::app()->request->getPost('label', '');

        if (!$defn_label) {
            echo CJSON::encode(array(
                'success' => -3,
                'msg' => 'No event was specified'
            ));
            Yii::app()->end();
        }

        /* The capping subcategory */
        $esc = EventSubcategory::model()->with(array(
            'eventDefinitions' => array('condition' => "label='" . $defn_label . "'")))->find();

        if (!$esc) {
            echo CJSON::encode(array(
                'success' => -4,
                'msg' => 'Could not locate any matching ending events'
            ));
            Yii::app()->end();
        }

        /* The cappable subcategory(ies) */
        $cappable_sc = EventSubcategory::model()->with()
            ->find('t.capping_subcategory_id=:_id', array(':_id' => $esc->event_subcategory_id));

        /*
		 * Event values that have not been capped
		 * that are before the cap date
		 * and that are of the type cappable_sc
		 */

        $event_values = EventValue::model()->with(array(
            'calendarEvent' => array('condition' => "start_date<=date('" . $cap_date . "')"),
            'eventDefinition' => array('condition' => "event_subcategory_id=" . $cappable_sc->event_subcategory_id)
        ))->findall('capped_event_value_id is :_null', array(':_null' => null));

        $options = array();
        foreach ($event_values as $ev) {
        	/*
				There's always a Note definition for every event
				But we really only want to identify the value
				corresponding to the boolean (yes/no) definition
			*/
        	if (strcmp('boolean',$ev->eventDefinition->parameter)==0) {
	            array_push($options, array(
	                'event_value_id' => $ev->event_value_id,
	                'event_date' => $ev->calendarEvent->start_date
	            ));
        	}
        }
        echo CJSON::encode(array(
            'success' => 1,
            'cap_date' => $cap_date,
            'defn' => $defn_label,
            'esc' => $esc,
            'cappable' => $cappable_sc,
            'event_values' => $options,
            'values' => array()//$avgResponses,
        ));
        Yii::app()->end();
    }

    /*
	 * Service to grab user calendar activities for a specified time frame
	 */

    public function actionCalendarActivities()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        header('Content-type: application/json');
        $user_id = Yii::app()->user->id;
        $start_date = Yii::app()->request->getPost('start', '');
        $end_date = Yii::app()->request->getPost('end', '');
        $myEvents = $this->calendarActivities($start_date, $end_date, $user_id);
        echo CJSON::encode(array(
            'success' => 1,
            'events' => $myEvents
        ));
        Yii::app()->end();
    }

    public function actionArq()
    {
        $this->layout = 'arqLayout2';
        $this->setPageTitle('Questions & Answers');
        //$question = $this->getRandomQuestion();
        $categories = $this->getQuestionCategories();
        $randomQuestions = array();

        foreach ($categories as $c => $category) {
            $randomQuestions{$category{'name'}} = $this->getRandomQuestionByCategory($category);
        }
        $randomQuestion = null;
        while (!$randomQuestion) {
            $randInt = rand(0, count($categories) - 1);
            $randomQuestion = $this->getRandomQuestionByCategory($categories{$randInt});
        }
        $goto = null;
        if (isset($_GET['goto'])) {
            $goto = $_GET['goto'];
        }
        $sortByDate = null;
        if (isset($_GET['onDate'])) {
            $sortByDate = $_GET['onDate'];
        }

        $this->render('arq', array(
            'question_statuses' => $this->getQuestionStatuses(),
            'question_types' => $this->getQuestionTypes(),
            'randomQuestion' => $randomQuestion,
            'question' => null,
            'question_flags' => $this->getQuestionFlags(),
            'cachedQuestion' => null,
            'categories' => $categories,
            'randomQuestionsByCategory' => $randomQuestions,
            'answeredQuestions' => $this->getMyAnsweredQuestions(),
            'questionsAsked' => $this->getMyQuestions(),
            'goto' => $goto,
            'sortByDate' => $sortByDate
        ));
    }

    /*
	 * Skip specified question and return another random question
	 * from any category
	 */
    public function actionSkipQuestion()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        header('Content-type: application/json');
        $user_id = Yii::app()->user->id;
        $question_id = Yii::app()->request->getPost('question_id', '');
        if (!$question_id) {
            echo CJSON::encode(array(
                'success' => -5,
                'error' => 'Unrecognized question',
            ));
            Yii::app()->end();
        }
        $different = -1;
        while ($different < 0) {
            $categories = $this->getQuestionCategories();
            $randInt = rand(0, count($categories) - 1);
            $question = $this->getRandomQuestionByCategory($categories{$randInt});
            if ($question && $question{'question_id'} != $question_id) $different = 1;
        }

        echo CJSON::encode(array(
            'question' => $question,
            'success' => 1
        ));
        Yii::app()->end();

    }

    /*
	 * Flag question as either unclear, inappropriate or as liked
	 * if Liked
	 */
    public function actionFlagQuestion()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        header('Content-type: application/json');
        $user_id = Yii::app()->user->id;
        $myQuestion = null;
        $question_id = Yii::app()->request->getPost('question_id', '');
        if (!$question_id) {
            echo CJSON::encode(array(
                'success' => -5,
                'error' => 'Unrecognized question',
            ));
            Yii::app()->end();
        }
        $question_flag_type_id = Yii::app()->request->getPost('question_flag_type_id', '');
        if (!$question_flag_type_id) {
            echo CJSON::encode(array(
                'success' => -10,
                'error' => 'Unrecognized flag type',
            ));
            Yii::app()->end();
        }

        //Check if this user has already flagged this question
        $flagged = QuestionFlag::model()->findByAttributes(array(
            'user_id' => $user_id,
            'question_id' => $question_id

        ));

        if ($flagged) {
            echo CJSON::encode(array(
                'success' => -15,
                'error' => 'You have already flagged this question',
            ));
            Yii::app()->end();
        }
        $flag = new QuestionFlag();
        $flag->question_id = $question_id;
        $flag->user_id = $user_id;
        $flag->question_flag_type_id = $question_flag_type_id;
        if ($flag->save()) {

            //Now check if this question's status should change
            $flagName = Yii::app()->request->getPost('name', '');
            if ($flagName && (strcmp($flagName, "Inappropriate") == 0 || strcmp($flagName, 'Unclear') == 0)) {
                $flags = QuestionFlag::model()->findByAttributes(array(
                    'question_id' => $question_id,
                    'question_flag_type_id' => $question_flag_type_id
                ));

                $flagStatusName = 'Flagged ' . $flagName;
                $flagStatus = QuestionStatus::model()->findByAttributes(array('name' => $flagStatusName));

                if ($flagStatus && count($flags) >= $this->flagThreshold) {
                    $question = Question::model()->findByPk($question_id);
                    if ($question) {
                        $question->question_status_id = $flagStatus->question_status_id;
                        $question->update();
                    }
                }

                //Generate a new question
                $different = -1;
                while ($different < 0) {
                    $categories = $this->getQuestionCategories();
                    $randInt = rand(0, count($categories) - 1);
                    $myQuestion = $this->getRandomQuestionByCategory($categories{$randInt});
                    if ($myQuestion && $myQuestion{'question_id'} != $question_id) $different = 1;
                }
            }

            echo CJSON::encode(array(
                'success' => 1,
                'question' => $myQuestion
            ));
            Yii::app()->end();
        } else {
            echo CJSON::encode(array(
                'success' => -1,
                'error' => $flag->getErrors()
            ));
            Yii::app()->end();
        }

    }

    public function actionRandomQuestionByCategory()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        header('Content-type: application/json');
        $user_id = Yii::app()->user->id;
        $question_category_id = Yii::app()->request->getPost('question_category_id', '');
        if (!$question_category_id) {
            echo CJSON::encode(array(
                'success' => -5,
                'error' => 'Unrecognized question category',
            ));
            Yii::app()->end();
        }
        $questionCategory = QuestionCategory::model()->findByPk($question_category_id);
        if (!$questionCategory) {
            echo CJSON::encode(array(
                'success' => -5,
                'error' => 'Could not locate the specified question category',
            ));
            Yii::app()->end();
        }

        $question_id = Yii::app()->request->getPost('question_id', '');
        if (!$question_id) {
            echo CJSON::encode(array(
                'success' => -10,
                'error' => 'Unrecognized question',
            ));
            Yii::app()->end();
        }

        $different = -1;
        $category = array('question_category_id' => $questionCategory->question_category_id, 'name' => $questionCategory->name);
        while ($different < 0) {
            $question = $this->getRandomQuestionByCategory($category);
            if ($question && $question{'question_id'} != $question_id) $different = 1;
        }

        echo CJSON::encode(array(
            'question' => $question,
            'success' => 1
        ));
        Yii::app()->end();
    }

    public function actionRecentJournals()
    {
        $user_id = Yii::app()->user->Id;
        $this->layout = 'arqLayout2';
        $this->setPageTitle('Recent Journals');
        $this->render('recent-journals',
            array(
                'my_journals' => Note::model()->with('noteStatus', 'noteVisibility')->findAll(array(
                    'condition' => "user_id=$user_id",
                    'order' => 'date_created DESC'
                ))
            )
        );
    }

    public function actionMyJournals()
    {
        $this->layout = 'arqLayout2';
        $this->setPageTitle('My Journals');
        $user_id = Yii::app()->user->Id;
        $today = date('Y-m-d');
        $day = 24 * 3600;
        $yesterday = date('Y-m-d', strtotime($today) - $day);
        $end_date = date('Y-m-d', strtotime($today) - 30 * $day);

        $noteVisibility = NoteVisibility::model()->findAll();
        $nv = array();
        foreach ($noteVisibility as $noteViz) {
            array_push($nv, array(
                'id' => $noteViz->visibility_id,
                'name' => $noteViz->name
            ));
        }

        //$activities = $this->calendarActivities($end_date, $today, $user_id);
        $journalDates = $this->getJournalDates();
        
	$renderNotes = $this->journalPager(0, null);
        if (isset($_GET['goto'])) {
            $goto = explode('-', $_GET['goto']);
            if (count($goto) > 0) {
                $dateObj = array('year' => $goto[0], 'month' => $goto[1], 'day' => $goto[2]);
                $renderNotes = $this->getMyJournalsByDate($dateObj, 0, $this->RECENTPOSTCOUNT);
            } else {
                $renderNotes = $this->journalPager(0, null);
            }

        } 
	
	if(isset($_GET['journal_id'])) {
		$renderNotes = $this->getMyJournalsByID($_GET['journal_id']);
	}
	
	
	

        $this->render('my-journals', array(
            //'activities' => $activities,
            'note_visibility' => $noteVisibility,
            'journalDates' => $journalDates,
            'renderNotes' => $renderNotes,
            'taggedNotes' => $this->getMyTaggedJournals()
        ));
    }

private function getMyJournalsByID($note_id){
		$user_id = Yii::app()->user->id;
		$offset = 0;
		$limit = 5;
		$condition = 'user_id = '.$user_id.' and note_id='.$note_id;
		$criteria = new CDbCriteria(
			array(
				'condition'=>$condition,
				'limit'=>$limit,
				'offset'=>$offset,
				'order'=>'publish_date DESC, publish_time DESC'
			)
		);
		$notes = Note::model()->findAll($criteria);
		//$notes = NoteStatus::model()->findByAttributes(array('note_id'=>$note_id));
		$results = array('limit'=>$limit,'count'=>count($notes),'nData'=>count($notes),'offset'=>$offset,'data'=>array());
		$results['data'] = $this->unrollNotes($notes);
		return $results;

	}
	    
	     
    public function actionGetMyJournals()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        $user_id = Yii::app()->user->Id;
        header('Content-type: application/json');

        if (!$user_id) {
            echo CJSON::encode(array(
                'success' => -1,
                'error' => 'Unknow user',
            ));
            Yii::app()->end();
        }
        $offset = Yii::app()->request->getPost('offset', '');
        $limit = Yii::app()->request->getPost('limit', '');
        if (!$offset || !$limit) {
            echo CJSON::encode(array(
                'success' => -1,
                'error' => 'Unknow range of entries to retrieve',
            ));
            Yii::app()->end();
        }
        $results = $this->journalPager($offset, $limit);
        echo CJSON::encode(array(
            'success' => 1,
            'entries' => $results
        ));
        Yii::app()->end();


    }

    public function actionGetMyJournalsByTag()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        $user_id = Yii::app()->user->Id;
        header('Content-type: application/json');

        if (!$user_id) {
            echo CJSON::encode(array(
                'success' => -1,
                'error' => 'Unknow user',
            ));
            Yii::app()->end();
        }
        $offset = Yii::app()->request->getPost('offset', '');
        $limit = Yii::app()->request->getPost('limit', '');

        $tag = Yii::app()->request->getPost('tag', '');

        $results = $this->getMyJournalsByTag($tag, $offset, $limit);
        echo CJSON::encode(array(
            'success' => 1,
            'entries' => $results
        ));
        Yii::app()->end();
    }

    public function actionGetMyJournalsByDate()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        $user_id = Yii::app()->user->Id;
        header('Content-type: application/json');

        if (!$user_id) {
            echo CJSON::encode(array(
                'success' => -1,
                'error' => 'Unknow user',
            ));
            Yii::app()->end();
        }
        $offset = Yii::app()->request->getPost('offset', '');
        $limit = Yii::app()->request->getPost('limit', '');

        $dateObj = array(
            'year' => Yii::app()->request->getPost('year', ''),
            'month' => Yii::app()->request->getPost('month', ''),
            'day' => Yii::app()->request->getPost('day', '')
        );

        $results = $this->getMyJournalsByDate($dateObj, $offset, $limit);
        echo CJSON::encode(array(
            'success' => 1,
            'entries' => $results
        ));
        Yii::app()->end();
    }

    public function actionForgotPassword()
    {
        $this->layout = 'arqLayout1';
        $this->setPageTitle('Forgot Password');
        $this->render('extra-forgot-password');
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionDoLogin()
    {
        Yii::log('step 0', 'trace');
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        $username = Yii::app()->request->getPost('username', '');
        $password = Yii::app()->request->getPost('password', '');
        $auth = new LoginForm();
        $auth->attributes = array(
            'username' => $username,
            'password' => $password,
            'rememberMe' => true,
        );
        if ($auth->validate() && $auth->login()) {
            // if the user is linded to the third party and has click auto-update button
            $is_bound = BindingAccount::model()->findAllByAttributes(array('arq_id' => Yii::app()->user->id));
            if ($is_bound) {
                if ($is_bound[0]['third_party'] == "facebook" && $is_bound[0]['auto_update'] == 1) {
                    Yii::app()->session['auto_update'] = 1;
                    header('Content-type: application/json');
                    echo CJSON::encode(array(
                        'success' => 1,
                        'redirect' => '/FBLogin/connect/',
                    ));
                    Yii::app()->end();

                }
            }

            header('Content-type: application/json');
            echo CJSON::encode(array(
                'success' => 1,
                'redirect' => '/dashboard',
            ));
            Yii::app()->end();
        } else {
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'success' => 0,
                'error' => $auth->errorCode,
            ));
            Yii::app()->end();
        }

    }

    public function actionDoRegister()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        $user = new User();
        $user->first_name = Yii::app()->request->getPost('fname', '');
        $user->last_name = Yii::app()->request->getPost('lname', '');
        $user->gender = Yii::app()->request->getPost('gender', '');

        $get_dob = Yii::app()->request->getPost('birthdate', '');

//    	$get_dob = explode('/', $get_dob);
//    	$get_dob = $get_dob[2].'-'.$get_dob[0].'-'.$get_dob[1];
        $user->birthday = $get_dob;

        $user->location = Yii::app()->request->getPost('location', '');
        $user->username = Yii::app()->request->getPost('username', '');

        $username_exists = User::model()->countByAttributes(array(
            'username' => $user->username
        ));

        if ($username_exists) {
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'success' => 0,
                'error' => 'Username already exists',
            ));
            Yii::app()->end();
        }

        $user->email = Yii::app()->request->getPost('email', '');

        $email_exists = User::model()->countByAttributes(array(
            'email' => $user->email
        ));

        if ($email_exists) {
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'success' => 0,
                'error' => 'Email already exists',
            ));
            Yii::app()->end();
        }

        $get_relationship_status = Yii::app()->request->getPost('relationship_status', '');
        $rs = RelationshipStatus::model()->findByAttributes(array(
            'description' => $get_relationship_status
        ));

        $user->relationship_status_id = $rs->relationship_status_id;

        $user->ethnicity = Yii::app()->request->getPost('ethnicity', '');

        $get_orientation = Yii::app()->request->getPost('orientation', '');
        $orie = Orientation::model()->findByAttributes(array(
            'description' => $get_orientation
        ));
        $user->orientation_id = $orie->orientation_id;
        $user->date_created = new CDbExpression('NOW()');
        //if register from facebook
        if (Yii::app()->request->getPost('loginWith', '') == "facebook") {
            $user->image_id = Yii::app()->request->getPost('image_id', '');
            $user->register_from = Yii::app()->request->getPost('register_from', '');
            $user->facebook_url = Yii::app()->request->getPost('facebook_url', '');
            $encrypt_pwd = Yii::app()->request->getPost('password', '');
            //facebook login pwd
            $encrypt_pwd = $this->encrypt($encrypt_pwd, 'E', 'danielcome');
            $user->encrypt_pwd = $encrypt_pwd;

        }
        try {
            $user_inserted = $user->save();
        } catch (Exception $e) {
            Yii::app()->end();
        }

        if ($user_inserted) {
            $pass = Yii::app()->request->getPost('password', '');
            $user->setPassword($pass);

            $auth = new LoginForm();
            $auth->attributes = array(
                'username' => $user->username,
                'password' => $pass,
                'rememberMe' => true,
            );
            if ($auth->validate() && $auth->login()) {
                header('Content-type: application/json');
                echo CJSON::encode(array(
                    'success' => 1,
                    'redirect' => '/dashboard',
                ));
                Yii::app()->end();
            } else {
                header('Content-type: application/json');
                echo CJSON::encode(array(
                    'success' => 0,
                    'error' => $auth->errorCode,
                ));
                Yii::app()->end();
            }
        }
    }

    public function actionUpdateAboutMe()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        $userid = Yii::app()->user->Id;
        if (!$userid) {
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'success' => -5,
                'error' => 'Unknown User',
            ));
            Yii::app()->end();
        }

        $user = User::model()->findByPk($userid);
        if (!$user) {
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'success' => -5,
                'error' => 'Unknown User',
            ));
            Yii::app()->end();
        }

        foreach ($user as $key => $value) {
            $fieldValue = Yii::app()->request->getPost($key, '');
            if ($fieldValue && $value != $fieldValue) {
                $user->$key = $fieldValue;
            }
        }

        try {
            $user_inserted = $user->update();
        } catch (Exception $e) {
            Yii::app()->end();
        }

        if ($user_inserted) {
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'success' => 1,
                'redirect' => '/dashboard',
            ));
            Yii::app()->end();
        } else {
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'success' => 0,
                'error' => "Unable to update profile at this time",
            ));
            Yii::app()->end();
        }
    }

    public function actionUpdateProfile()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        $userid = Yii::app()->user->Id;
        if (!$userid) {
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'success' => -5,
                'error' => 'Unknown User',
            ));
            Yii::app()->end();
        }

        $user = User::model()->findByPk($userid);
        if (!$user) {
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'success' => -5,
                'error' => 'Unknown User',
            ));
            Yii::app()->end();
        }

        foreach ($user as $key => $value) {
            $fieldValue = Yii::app()->request->getPost($key, '');
            if ($fieldValue && $key != 'password' && $value != $fieldValue) {
                $user->$key = $fieldValue;
            }
        }

        $username_exists = User::model()->countByAttributes(array(
            'username' => $user->username
        ));

        if ($username_exists > 1) {
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'success' => -10,
                'error' => 'Username already exists',
            ));
            Yii::app()->end();
        }

        $email_exists = User::model()->countByAttributes(array(
            'email' => $user->email
        ));

        if ($email_exists > 1) {
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'success' => 0,
                'error' => 'Email already exists',
            ));
            Yii::app()->end();
        }

        $relationship_status = Yii::app()->request->getPost('relationship_status', '');
        if ($relationship_status) {
            $user->relationship_status_id = $relationship_status;
        }

        $orientation = Yii::app()->request->getPost('orientation', '');
        if ($orientation) {
            $user->orientation_id = $orientation;
        }

        // Let's do this at the end
        $password = Yii::app()->request->getPost('password', '');
        if ($password != '') {
            $user->setPassword($password);
        }

        try {
            $user_inserted = $user->update();
        } catch (Exception $e) {
            Yii::app()->end();
        }

        if ($user_inserted) {
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'success' => 1,
                'redirect' => '/dashboard',
            ));
            Yii::app()->end();
        } else {
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'success' => 0,
                'error' => "Unable to update profile at this time",
            ));
            Yii::app()->end();
        }
    }

    public function actionDeleteJournalEntry()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        header('Content-type: application/json');
        $note_id = Yii::app()->request->getPost('note_id', -1);
        #MyStuff::Log('DELETE');

        if (!$note_id || $note_id < 0) {
            echo CJSON::encode(array(
                'success' => -1,
                'msg' => 'Entry not found'
            ));
            Yii::app()->end();
        }

        $note = Note::model()->findByPk($note_id);

        if (!$note) {
            echo CJSON::encode(array(
                'success' => -1,
                'msg' => 'Entry not found'
            ));
            Yii::app()->end();
        }
        $note_array = array('note_id' => $note_id);

        $this->deleteAE_Response($note->ae_response_id, $note);
        NoteTag::model()->deleteAllByAttributes($note_array);
        NoteCategory::model()->deleteAllByAttributes($note_array);
        $eventNote = EventNote::model()->findbyAttributes($note_array);
        if ($eventNote) {
        	$eventValue = EventValue::model()->findByPk($eventNote->event_value_id);
        }
        if ($eventValue) {
        	$calendarEvent = CalendarEvent::model()->findByPk($eventValue->calendar_event_id);
        }
        
        EventNote::model()->deleteAllByAttributes($note_array);
        
        if ($eventNote && $eventNote->hasAttribute('event_value_id')) {
        	EventValue::model()->deleteByPk($eventNote->event_value_id);
        }
        if ($eventValue && $eventValue->hasAttribute('calendar_event_id')) {
        	CalendarEvent::model()->deleteByPk($eventValue->calendar_event_id);
        }
        
        $note_date_created = $note->date_created;
        $note_publish_date = $note->publish_date;
        $note->delete();

        $this->runAEJournalDaily($note_publish_date);

        echo CJSON::encode(array(
            'success' => 1,
            'id' => Yii::app()->request->getPost('note_id', '-999')
        ));
        Yii::app()->end();
    }

    public function actionCreateOrUpdateJournal()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }

        $calEvent = '';
        $title = Yii::app()->request->getPost('post_title', '');

        $old_status_id = '';
        $old_publish_date = '';

        $note_id = Yii::app()->request->getPost('journal_id', 0);
        if ($note_id > 0) {
            //Do update
            /*
			 * TODO: implement auto save
			 */
            $note = Note::model()->findByPk($note_id);

            $old_status_id = $note->status_id;
            $old_publish_date = $note->publish_date;

            if ($note->title != $title) {
                $note->title = $title;
            }
            if ($note->content != Yii::app()->request->getPost('post_content', '')) {
                $note->content = Yii::app()->request->getPost('post_content', '');
                $note->stripped_content = Yii::app()->request->getPost('stripped_content', '');
            }
            $get_publish_date = Yii::app()->request->getPost('publish_date', '');
            $publish_date = DateTime::createFromFormat('D, d F Y', $get_publish_date);

            if ($note->status_id != Yii::app()->request->getPost('status')) {
                $note->status_id = Yii::app()->request->getPost('status');
            }
            if ($note->visibility_id != Yii::app()->request->getPost('visibility')) {
                $note->visibility_id = Yii::app()->request->getPost('visibility');
            }
            /*
			 * change start date in event for this note
			 * we get the calendar event here, and update it below
			 * once we know the new publish date/time
			 */
            $eventNote = EventNote::model()->findByAttributes(array('note_id' => $note_id));
            if ($eventNote) {
                $calEvent = CalendarEvent::model()->with(array(
                    'eventValues' => array('condition' => 'eventValues.event_value_id =' . $eventNote->event_value_id)
                ))->findAll();
            }
        } else {
 
            $note = new Note();
            if (!Yii::app()->user->Id > 0) {
                header('Content-type: application/json');
                echo CJSON::encode(array(
                    'success' => 0,
                    'error' => 'Invalid user_id',
                ));
                Yii::app()->end();
            }
            $note->user_id = Yii::app()->user->Id;
            $note->title = $title;
            $note->content = Yii::app()->request->getPost('post_content', '');
            $note->stripped_content = Yii::app()->request->getPost('stripped_content','');

            $note->date_created = new CDbExpression('NOW()');
            $note->is_active = 1;

            $note->visibility_id = Yii::app()->request->getPost('visibility');

            /* Calendar event */
            $event_definition = EventDefinition::model()->findByAttributes(array(
                'parameter' => 'Note:'
            ));

            if ($event_definition) {
                $myValue;
                if ($title) {
                    $myValue = $title;
                } else {
                    $myValue = $note->stripped_content;
                }

                $myValue = substr($myValue, 0, 15) . '...';
                $cal = new CalendarEvent();
                $cal->user_id = Yii::app()->user->Id;
                $cal->start_date = $note->date_created;
                $cal->all_day = 0;
                $cal->save();
				$calEvent = array('0'=>$cal);
                /* Event value */
                $event_value = new EventValue();
                $event_value->calendar_event_id = $cal->calendar_event_id;
                $event_value->value = $myValue;
                $event_value->event_definition_id = $event_definition->event_definition_id;
                $event_value->save();

                /* Event note */
                $note->save();
                $note->refresh();
                $event_note = new EventNote();
                $event_note->note_id = $note->note_id;
                $event_note->event_value_id = $event_value->event_value_id;
                $event_note->save();

            }

        }

        /* This is the logic to determine if 'In Queue' status should be used */
        $get_publish_date = Yii::app()->request->getPost('publish_date', '');
        $get_publish_date = date('D, d F Y', strtotime($get_publish_date));

        $publish_date = DateTime::createFromFormat('D, d F Y', $get_publish_date);
        $note->publish_date = $publish_date->format('Y-m-d');
        $get_publish_time = Yii::app()->request->getPost('publish_time', '');
	
        $note->publish_time = $get_publish_time;
        /* 
		 * Check if post is in the future 
		 * If it is, ignore posted status and convert it to 'In Queue'
		 * Otherwise use posted status for note status
		 */
	
        $publish_datetime = strtotime($get_publish_date . ' ' . $get_publish_time);
	
        $current_datetime = strtotime(date('Y-m-d h:i:s a'));
	
        if ($current_datetime >= $publish_datetime) {
            $note_status_id = Yii::app()->request->getPost('status');
            $note->status_id = $note_status_id;
        } else {
            //publish date is in the future
            $note_status = NoteStatus::model()->findByAttributes(array('name' => 'In Queue'));
            if ($note_status && $note_status->status_id) {
                $note->status_id = $note_status->status_id;
                $note_status_id = $note_status->status_id;
            }
        }

        /* 
		 * If we are doing an update the publish date/time may have changed
		 */
        if ($calEvent) {
            $start_date = date('Y-m-d H:i:s', $publish_datetime);
            $calEvent[0]->start_date = $start_date;
            $calEvent[0]->save();
        }
        /*
		 * Once we know the status of this note, we can decide if we need to:
		 * 
		 * create an ae_response
		 * delete an existing ae_response
		 * delete AND create an ae_response
		 * do nothing
		 * 
		 * Note also that we need to check if a title exists; if there is no title,
		 * we treat this as a draft
		 */
        $draft_status = NoteStatus::model()->findByAttributes(array('name' => 'Draft'));

        $ae_response = null;
        if ($note_id > 0) {
            $ae_response = AeResponse::model()->findbyPk($note->ae_response_id);
        }

        if ($note_status_id) {
            if ($draft_status->status_id != $note_status_id) {
                /*
				 * This is not a draft, so create ae_response
				 */
                if ($ae_response) {

                    /* 
					 * An ae_response already exists, so trash it before creating
					 * a new one
					 */
                    CategoryScore::model()->deleteAllByAttributes(array(
                        'ae_response_id' => $ae_response->ae_response_id
                    ));
                    TopWords::model()->deleteAllByAttributes(array(
                        'ae_response_id' => $ae_response->ae_response_id
                    ));
                    TopPeople::model()->deleteAllByAttributes(array(
                        'ae_response_id' => $ae_response->ae_response_id
                    ));

                    $ae_response_id = $this->createAEResponse();
                    $note->ae_response_id = $ae_response_id;
                    $note->save();
                    $ae_response->delete();

                } else {
                    /*
					 * No ae_response yet, so just create one
					 */

                    $ae_response_id = $this->createAEResponse();
                    $note->ae_response_id = $ae_response_id;
                }

                $note->save();
                $note->refresh();

                $this->runAEJournalDaily(date('Y-m-d', strtotime($note->publish_date)));
                /*
				 * Scrub analysis on old publish date if it is different than
				 * the new publish date
				 */

                if ($old_publish_date && strcmp($note->publish_date, $old_publish_date) != 0) {
                    $this->runAEJournalDaily($old_publish_date);
                }
            } else {
                /*
				 * Status is 'Draft', so if an ae_response exists, delete it
				 */
                if ($ae_response) {
                    CategoryScore::model()->deleteAllByAttributes(array(
                        'ae_response_id' => $ae_response->ae_response_id
                    ));
                    TopWords::model()->deleteAllByAttributes(array(
                        'ae_response_id' => $ae_response->ae_response_id
                    ));
                    TopPeople::model()->deleteAllByAttributes(array(
                        'ae_response_id' => $ae_response->ae_response_id
                    ));
                    $note->ae_response_id = NULL;
                    $note->save();
                    $ae_response->delete();
                }
                /*
				 * We must rerun for the original publish date to remove
				 * the contribution from this journal that is now a draft
				 */
                if ($old_publish_date) {
                    $this->runAEJournalDaily($old_publish_date);
                }
            }
        }

        try {
            $inserted = $note->save();
        } catch (Exception $e) {
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'success' => 0,
                'error' => $note->errorCode,
                'msg' => $e
            ));
            Yii::app()->end();
        }

        $noteImg = '';
        if ($inserted) {
            /* upload note image */
            $image_id = 0;
            if (array_key_exists('note_image', $_FILES) && $_FILES && $_FILES['note_image'] && $_FILES['note_image']['size'] > 0) {
                $noteImg = $_FILES['note_image'];
                $img = new Image();
                $img->save();
                if (!file_exists(Yii::app()->params['noteImageDir'] . DIRECTORY_SEPARATOR . $img->image_id)) mkdir(Yii::app()->params['noteImageDir'] . DIRECTORY_SEPARATOR . $img->image_id);
                $new_filename = Yii::app()->params['noteImageDir'] . DIRECTORY_SEPARATOR . $img->image_id . DIRECTORY_SEPARATOR . $_FILES['note_image']['name'];
                $new_relative_filename = Yii::app()->params['relativeNoteImageDir'] . DIRECTORY_SEPARATOR . $img->image_id . DIRECTORY_SEPARATOR . $_FILES['note_image']['name'];
                move_uploaded_file($_FILES['note_image']['tmp_name'], $new_filename);
                $img->path = $new_relative_filename;
                $img->save();
                $image_id = $img->image_id;
            }
            if ($image_id > 0) {
                $note->image_id = $image_id;
                $note->save();
            }
            /* upload tags */
            if (Yii::app()->request->getPost('tags', '') != '') {
                $tags = Yii::app()->request->getPost('tags');
                $tags = @split(',', $tags);
                $current_tags = array();
                foreach ($tags as $get_tag) {
                    $get_tag = trim($get_tag);
                    if ($get_tag) {
                        $tag = Tags::model()->findByAttributes(array(
                            'tag' => $get_tag
                        ));
                        if (!$tag) {
                            $tag = new Tags();
                            $tag->tag = $get_tag;
                            $tag->save();
                        }
                        $note_tag = new NoteTag();
                        $note_tag->note_id = $note->note_id;
                        $note_tag->tag_id = $tag->tag_id;
                        $note_tag->save();
                        $current_tags[$get_tag] = 1;
                    }
                }
                $all_tags = NoteTag::model()->findAllByAttributes(array('note_id' => $note->note_id));
                foreach ($all_tags as $all_tag) {
                    //if (!$current_tags[]) {
                    //	
                    //}
                }
            }

            /* note categories */
            $categories = Yii::app()->request->getPost('categories', array());
            if (sizeof($categories) > 0) {
                foreach ($categories as $category_id) {
                    $nc = NoteCategory::model()->findByAttributes(
                        array(
                            'note_id' => $note->note_id,
                            'category_id' => $category_id,
                        ));
                    if (!$nc) {
                        $insert_nc = new NoteCategory();
                        $insert_nc->note_id = $note->note_id;
                        $insert_nc->category_id = $category_id;
                        $insert_nc->save();
                    }
                }
            }
        }
        header('Content-type: application/json');
        echo CJSON::encode(array(
            'success' => 1,
            'redirect' => '/recentJournals',
            'IMAGE' => $noteImg
        ));
        Yii::app()->end();

    }

    public function _runAEJournalDaily($get_date = NULL,$publication_date,$publication_time) {
        $user_id = Yii::app()->user->Id;
        $draft_status = NoteStatus::model()->findByAttributes(array('name' => 'Published'));
        if (!$get_date) {
            $get_date = MyStuff::get_sql_date('curdate()');
        } else {
            $get_date = substr($get_date, 0, 10);
        }

        $ajd = AeJournalDaily::model()->findByAttributes(array(
            'user_id' => $user_id,
            'date_created' => $get_date,
        ));

        if (!$ajd) {
            $ajd = new AeJournalDaily();
            $ajd->user_id = $user_id;
            $ajd->date_created = $get_date;
            $ajd->save();
        };
        $myPublishDate = date('Y-m-d', strtotime($get_date));
        /* group_concat will truncate long passages of data
        $sql = "select group_concat(stripped_content, ' ') total_content
				from note
				where user_id=$user_id
				and status_id='$draft_status->status_id'
					and publish_date is not null
				  and publish_date>='$myPublishDate'
				  and publish_date<date_add('$myPublishDate', interval 1 day)
				  and is_active=1
				group by user_id";
		*/
        $sql = "select stripped_content as total_content 
				from note
				where user_id=$user_id
				and status_id='$draft_status->status_id'
					and publish_date is not null
				  and publish_date>='$myPublishDate'
				  and publish_date<date_add('$myPublishDate', interval 1 day)
				  and is_active=1";
        $entries = Yii::app()->db->createCommand($sql)->queryAll();
		
        $totalContent = '';
        if ($entries) {
        	foreach ($entries as $entry ) {$totalContent = $totalContent.' '.$entry['total_content'];}
            $ae_response_id = SiteController::_createAEResponse($totalContent,$publication_date,$publication_time);
            $ajd->ae_response_id = $ae_response_id;
            //todo add by daniel
            $ajd->is_active = 1;
            $ajd->save();

        } else {
            $ajd->delete();
        }
    }
    
    public function runAEJournalDaily($get_date = NULL)
    {

        $user_id = Yii::app()->user->Id;
        //$draft_status = NoteStatus::model()->findByAttributes(array('name' => 'Published'));
        if (!$get_date) {
            $get_date = MyStuff::get_sql_date('curdate()');
        } else {
            $get_date = substr($get_date, 0, 10);
        }
        $publication_date = Yii::app()->request->getPost('publish_date', '');
        $publication_time = Yii::app()->request->getPost('publish_time', '');
        $this->_runAEJournalDaily($get_date,$publication_date,$publication_time);
		/*
        $ajd = AeJournalDaily::model()->findByAttributes(array(
            'user_id' => $user_id,
            'date_created' => $get_date,
        ));

        if (!$ajd) {
            $ajd = new AeJournalDaily();
            $ajd->user_id = $user_id;
            $ajd->date_created = $get_date;
        };
        $myPublishDate = date('Y-m-d', strtotime($get_date));

        $sql = "select group_concat(stripped_content, ' ') total_content
				from note
				where user_id=$user_id
				and status_id='$draft_status->status_id'
					and publish_date is not null
				  and publish_date>='$myPublishDate'
				  and publish_date<date_add('$myPublishDate', interval 1 day)
				  and is_active=1
				group by user_id";
        $entries = Yii::app()->db->createCommand($sql)->queryRow();

        if ($entries) {
            $ae_response_id = $this->createAEResponse($entries['total_content']);
            $ajd->ae_response_id = $ae_response_id;
            //todo add by daniel
            $ajd->is_active = 1;
            $ajd->save();

        } else {
            $ajd->delete();
        }
		*/
    }

    public function actionCreateQuestion()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }

        header('Content-type: application/json');

        if (!Yii::app()->user->Id > 0) {
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'success' => 0,
                'error' => 'Invalid user_id',
            ));
            Yii::app()->end();
        }
        $user_id = Yii::app()->user->Id;

        if (!isset($_POST['content'])) {
            echo CJSON::encode(array(
                'success' => 0,
                'error' => 'Please specify a question',
            ));
            Yii::app()->end();
        }

        $question_type_id = Yii::app()->request->getPost('question_type_id', '');
        if (!$question_type_id) {
            echo CJSON::encode(array(
                'success' => -5,
                'error' => 'Unidentified question type encountered',
            ));
            Yii::app()->end();
        }

        $questionType = QuestionType::model()->findByPk($question_type_id);

        if (!$questionType) {
            echo CJSON::encode(array(
                'success' => -10,
                'error' => 'Unidentified question type encountered',
            ));
            Yii::app()->end();
        }

        $question_category_id = Yii::app()->request->getPost('question_category_id', array());
        if (!$question_category_id) {
            echo CJSON::encode(array(
                'success' => -15,
                'error' => 'Please select a category for this question',
            ));
            Yii::app()->end();
        }
        $questionCategory = QuestionCategory::model()->findByPk($question_category_id);
        if (!$questionCategory) {
            echo CJSON::encode(array(
                'success' => -25,
                'error' => 'The specified category could not be found; please select another',
            ));
            Yii::app()->end();
        }

        $question = new Question();
        $question->user_id = Yii::app()->user->Id;
        $question->content = Yii::app()->request->getPost('content', '');
        $question->question_type_id = $question_type_id;
        $question->question_category_id = $question_category_id;
        $questionStatus = QuestionStatus::model()->findByAttributes(array('name' => $this->defaultQuestionStatus));
        if ($questionStatus) {
            $question->question_status_id = $questionStatus->question_status_id;
        }

        // This will cause errors... use CDbExpression
        //$question->date_created=(new DateTime())->format('Y-m-d');
        $question->date_created = new CDbExpression('NOW()');

        $question->is_active = 1;
        $inserted = $question->save();

        if ($inserted) {
            if ($choice1 = Yii::app()->request->getPost('choice_1', '')) {
                $qc = new QuestionChoice();
                $qc->content = $choice1;
                $qc->choice_order = 1;
                $qc->is_active = 1;
                $qc->question_id = $question->question_id;
                $qc->save();
            }
            if ($choice2 = Yii::app()->request->getPost('choice_2', '')) {
                $qc = new QuestionChoice();
                $qc->content = $choice2;
                $qc->choice_order = 2;
                $qc->is_active = 1;
                $qc->question_id = $question->question_id;
                $qc->save();
            }
            if ($choice3 = Yii::app()->request->getPost('choice_3', '')) {
                $qc = new QuestionChoice();
                $qc->content = $choice3;
                $qc->choice_order = 3;
                $qc->is_active = 1;
                $qc->question_id = $question->question_id;
                $qc->save();
            }
            if ($choice4 = Yii::app()->request->getPost('choice_4', '')) {
                $qc = new QuestionChoice();
                $qc->content = $choice4;
                $qc->choice_order = 4;
                $qc->is_active = 1;
                $qc->question_id = $question->question_id;
                $qc->save();
            }
        }

        $question = Question::model()->findByPk($question->question_id);

        /* Calendar event */
        $event_definition = EventDefinition::model()->findByAttributes(array(
            'parameter' => 'QA: Asked:'
        ));

        if ($event_definition) {
            $cal = new CalendarEvent();
            $cal->user_id = $user_id;
            $cal->start_date = $question->date_created;
            $cal->all_day = 0;
            $cal->save();

            /* Event value */
            $event_value = new EventValue();
            $event_value->calendar_event_id = $cal->calendar_event_id;
            $event_value->value = $question->content;
            $event_value->event_definition_id = $event_definition->event_definition_id;
            $event_value->save();

            /* Event Question */
            $event_question = new EventQuestion();
            $event_question->question_id = $question->question_id;
            $event_question->event_value_id = $event_value->event_value_id;
            $event_question->type = 'asked';
            $event_question->save();
        }

        $myQuestion = Question::unroll($question);
        echo CJSON::encode(array(
            'success' => 1,
            'question' => array('question' => $myQuestion, 'myAnswer' => null, 'answers' => array())
        ));
        Yii::app()->end();
    }

    public function actionUpdateAnswer()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        $answer_id = (int)Yii::app()->request->getPost('answer_id', 0);
        $is_published = (int)Yii::app()->request->getPost('is_published', 1);
        $user_answer = Yii::app()->request->getPost('user_answer', '');
        $answer = Answer::model()->findbyPk($answer_id);
        if ($user_answer) {
            $answer->user_answer = $user_answer;
            $is_private = Yii::app()->request->getPost('is_private', '');
            if ($is_private) {
                $answer->is_private = 1;
            }
        }

        if (!$is_published) {
            $answer->is_published = 0;
        }
    }

    public function actionAnswerQuestion()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        $question = Question::model()->findByAttributes(array('question_id' => Yii::app()->request->getPost('question_id')));
        $question_type_id = (int)Yii::app()->request->getPost('question_type_id', '');
        $is_published = (int)Yii::app()->request->getPost('is_published', 1);
        $user_answer = Yii::app()->request->getPost('user_answer', '');
        $user_id = Yii::app()->user->Id;
        $answer_id = (int)Yii::app()->request->getPost('answer_id', 0);

        if ($answer_id > 0) {
            $answer = Answer::model()->findbyPk($answer_id);
        } else {
            $answer = new Answer();
            $answer->user_id = $user_id;
            $answer->question_id = $question->question_id;
        }

        if (!$question_type_id) {
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'success' => -5,
                'error' => 'Unknown question type',
            ));
            Yii::app()->end();
        }

        $question_type = QuestionType::model()->findbyPk($question_type_id);

        if (!$question_type) {
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'success' => -10,
                'error' => 'Unknown question type',
            ));
            Yii::app()->end();
        }

        if (strcmp($question_type->name, 'Multiple Choice') == 0) {
            $question_choice_id = Yii::app()->request->getPost('question_choice_id', '');
            if (!$question_choice_id) {
                echo CJSON::encode(array(
                    'success' => -20,
                    'error' => 'A multiple choice option is required',
                ));
                Yii::app()->end();
            }
            if ($answer->question_choice_id != (int)$question_choice_id) {
                $answer->question_choice_id = (int)$question_choice_id;
            }
        } else if (strcmp($question_type->name, 'Quantitative') == 0) {
            $qty = Yii::app()->request->getPost('quantitative_value', 0);
            if (strcmp($qty, '') == 0) {
                echo CJSON::encode(array(
                    'success' => -25,
                    'error' => 'A quantity is required',
                ));
                Yii::app()->end();
            }

            $answer->quantitative_value = (int)$qty;

        } else {
            if (!$user_answer) {
                header('Content-type: application/json');
                echo CJSON::encode(array(
                    'success' => -15,
                    'error' => 'A comment is required',
                ));
                Yii::app()->end();
            }
        }

        if ($user_answer) {
            $answer->user_answer = $user_answer;
            $is_private = Yii::app()->request->getPost('is_private', '');
            if ($is_private) {
                $answer->is_private = 1;
            }
        }

        if (!$is_published) {
            $answer->is_published = 0;
        }

        if ($answer->save()) {
            $answeredQuestion = Question::unroll($question);
            $newQuestion = null;
            $category_count = Yii::app()->request->getPost('category_count', 0);
            if ($category_count < 2) {
                $newQuestion = $this->getRandomQuestionByCategory(array('question_category_id' => $question->question_category_id));
            } else {
                $categories = $this->getQuestionCategories();
                while (!$newQuestion) {
                    $randInt = rand(0, count($categories) - 1);
                    $newQuestion = $this->getRandomQuestionByCategory($categories{$randInt});
                }
            }

            $answers = array();
            $myAnswer = null;

            foreach ($question->answers as $ans) {
                $a = array(
                    'answer_id' => $ans->answer_id,
                    'user_answer' => $ans->is_private ? null : $ans->user_answer,
                    'date_created' => $ans->date_created,
                    'question_choice_id' => $ans->question_choice_id,
                    'quantitative_value' => $ans->quantitative_value
                );
                array_push($answers, $a);
                if ($ans->user_id == $user_id) $myAnswer = $a;
            }

            $answerResponse = array(
                'question' => $answeredQuestion,
                'answers' => $answers,
                'myAnswer' => $myAnswer
            );

            /* Calendar event */
            $event_definition = EventDefinition::model()->findByAttributes(array(
                'parameter' => 'QA: Answered:'
            ));

            if ($event_definition) {
                $cal = new CalendarEvent();
                $cal->user_id = $user_id;
                $cal->start_date = new CDbExpression('NOW()');
                $cal->all_day = 0;
                $cal->save();

                /* Event value */
                $event_value = new EventValue();
                $event_value->calendar_event_id = $cal->calendar_event_id;
                $event_value->value = $question->content;
                $event_value->event_definition_id = $event_definition->event_definition_id;
                $event_value->save();

                /* Event Question */
                $event_question = new EventQuestion();
                $event_question->question_id = $question->question_id;
                $event_question->event_value_id = $event_value->event_value_id;
                $event_question->type = 'answered';
                $event_question->save();
            }

            header('Content-type: application/json');
            echo CJSON::encode(array(
                'answer_id' => $answer->answer_id,
                'response' => $answerResponse,
                'newQuestion' => $newQuestion,
                'success' => 1,
                'redirect' => '/arq',
            ));
            Yii::app()->end();
        } else {
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'success' => 0,
                'error' => $answer->getErrors(),
            ));
            Yii::app()->end();
        }
    }

    public function actionUpdateUserSettings()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }

        $user_id = Yii::app()->user->Id;
        $user = User::model()->findByAttributes(array('user_id' => $user_id));

        $username = Yii::app()->request->getPost('username', '');
        if ($user->username != $username) {
            $user_exists = User::model()->findByAttributes(array('username' => $username));
            if (!$user_exists) {
                $user->username = $username;
            } else {
                header('Content-type: application/json');
                echo CJSON::encode(array(
                    'success' => 0,
                    'error' => 'Username exists',
                ));
                Yii::app()->end();
            }
        }

        $email = Yii::app()->request->getPost('email', '');
        if ($user->email != $email) {
            $email_exists = User::model()->findByAttributes(array('email' => $email));
            if (!$email_exists) {
                $user->email = $email;
            } else {
                header('Content-type: application/json');
                echo CJSON::encode(array(
                    'success' => 0,
                    'error' => 'Email address exists',
                ));
                Yii::app()->end();
            }
        }

        $facebook_url = Yii::app()->request->getPost('facebook_url', '');
        if ($facebook_url != $user->facebook_url) {
            $user->facebook_url = $facebook_url;
        }
        $twitter_url = Yii::app()->request->getPost('twitter_url', '');
        if ($twitter_url != $user->twitter_url) {
            $user->twitter_url = $twitter_url;
        }
        $linkedin_url = Yii::app()->request->getPost('linkedin_url', '');
        if ($linkedin_url != $user->linkedin_url) {
            $user->linkedin_url = $linkedin_url;
        }
        $gplus_url = Yii::app()->request->getPost('gplus_url', '');
        if ($gplus_url != $user->gplus_url) {
            $user->gplus_url = $gplus_url;
        }

        $active = Yii::app()->request->getPost('is_active', '');
        if ($active != $user->is_active) {
            $user->is_active = $active;
        }
        // What do we do if user deactivates?  Log them out and say bye?  Need front-end code to verify

        $secure_browsing = Yii::app()->request->getPost('is_active', '');

        $text_msg_login_notifications = Yii::app()->request->getPost('text_notifications', '');
        if ($text_msg_login_notifications != $user->text_msg_login_notifications) {
            $user->text_msg_login_notifications = $text_msg_login_notifications;
        }

        //Yii::app()->end();

        /* upload user image */
        $image_id = 0;
        if ($_FILES and $_FILES['user_image'] and $_FILES['user_image']['size'] > 0) {
            $format = explode('.', $_FILES['user_image']['name']);
            $_FILES['user_image']['name'] = strtotime("now") . "." . $format[1];
            $img = new Image();
            $img->save();
            mkdir(Yii::app()->params['userImageDir'] . DIRECTORY_SEPARATOR . $img->image_id);
            $new_filename = Yii::app()->params['userImageDir'] . DIRECTORY_SEPARATOR . $img->image_id . DIRECTORY_SEPARATOR . $_FILES['user_image']['name'];
            $new_relative_filename = Yii::app()->params['relativeUserImageDir'] . DIRECTORY_SEPARATOR . $img->image_id . DIRECTORY_SEPARATOR . $_FILES['user_image']['name'];
            move_uploaded_file($_FILES['user_image']['tmp_name'], $new_filename);
            $img->path = $new_relative_filename;
            $img->save();
            $image_id = $img->image_id;
            $user->image_id = $image_id;
        }

        $saved = $user->save();

        if ($saved) {
            echo CJSON::encode(array(
                'success' => 1,
                'redirect' => '/settings'
            ));
        } else {
            echo CJSON::encode(array(
                'success' => 1,
                'error' => $user->getErrors()
            ));
        }

        // Let's do this at the end
        $password = Yii::app()->request->getPost('password', '');
        if ($password != '') {
            $user->setPassword($password);
        }

        Yii::app()->end();
    }

    public function actionUpdateUserImage()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        $userid = Yii::app()->user->Id;
        if (!$userid) {
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'success' => -5,
                'error' => 'Unknown User',
            ));
            Yii::app()->end();
        }

        $user = User::model()->findByPk($userid);
        if (!$user) {
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'success' => -5,
                'error' => 'Unknown User',
            ));
            Yii::app()->end();
        }

        MyStuff::Log('DIR= ' . Yii::app()->params['userImageDir']);
        /* upload user image */
        $image_id = 0;
        if ($_FILES and $_FILES['user_image'] and $_FILES['user_image']['size'] > 0) {
            $img = new Image();
            $img->save();
            mkdir(Yii::app()->params['userImageDir'] . DIRECTORY_SEPARATOR . $img->image_id);
            $new_filename = Yii::app()->params['userImageDir'] . DIRECTORY_SEPARATOR . $img->image_id . DIRECTORY_SEPARATOR . $_FILES['user_image']['name'];
            $new_relative_filename = Yii::app()->params['relativeUserImageDir'] . DIRECTORY_SEPARATOR . $img->image_id . DIRECTORY_SEPARATOR . $_FILES['user_image']['name'];
            move_uploaded_file($_FILES['user_image']['tmp_name'], $new_filename);
            $img->path = $new_relative_filename;
            $img->save();
            $image_id = $img->image_id;
            MyStuff::Log("IMAGE ID " . $img->image_id);
            $user->image_id = $image_id;
            $user->update();
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'success' => 1,
                'path' => $img->path
            ));
            Yii::app()->end();

        } else {
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'success' => -10,
                'error' => 'No image specified',
            ));
            Yii::app()->end();
        }

        $saved = $user->update();
    }

    /*
	 * Create a calendar event
	 */
    public function actionCreateCalendarEvent()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        header('Content-type: application/json');
        $user_id = Yii::app()->user->Id;
        if (!$user_id) {
            echo CJSON::encode(array(
                'success' => -999,
                'msg' => 'Unknown User'
            ));
            Yii::app()->end();
        }

        $cal = new CalendarEvent();
        $cal->user_id = $user_id;
        $start = Yii::app()->request->getPost('start', '');
	
        $end = Yii::app()->request->getPost('end', '');
        $all_day = Yii::app()->request->getPost('all_day', '');
 
        if ($start) {
            $start = DateTime::createFromFormat('Y-m-d\TH:i:s.uZ', $start);
	    $start = $start->format('Y-m-d H:i:s');
	    $startArr = explode(' ',$start);
	    
	    if($startArr[1] == "00:00:00") {
	    	$start = $startArr[0].' '.date('h:i:s',time());
	    }
            $cal->start_date = $start;
	    
	    
	    //$cal->start_date = $start;
        }
        if ($end) {
            $end = DateTime::createFromFormat('Y-m-d\TH:i:s.uZ', $end);
            $end = $end->format('Y-m-d H:i:s');
            $cal->end_date = $end;
        }

        $cal->all_day = $all_day;
        $cal->date_created = new CDbExpression('NOW()');
	
        $cal->save();
        $cal_id = $cal->calendar_event_id;

        /*
		 * This will tell us if we are ending and event that was started, such
		 * as vacation starting, job starting, etc.
		 */
        $capping_event = Yii::app()->request->getPost('capping_event', '');

        $defns = Yii::app()->request->getPost('definitions', array());
        /*
		 * No capping event => create event value and calendar event
		 */
        if (!$capping_event) {
            foreach ($defns as $defn) {
                $event = new EventValue();
                $event->calendar_event_id = $cal_id;
                $event->value = $defn{'value'};
                $event->event_definition_id = $defn{'definition_id'};
                $event->save();
                #MyStuff::Log('EVENT');
                #MyStuff::Log($event);

            }
        } else {
            /*
			 * Capping event => get the capping event, create its calendar event and value
			 * and update the capped event
			 */
            foreach ($defns as $defn) {
                if (array_key_exists('capped_value', $defn)) {
                    $capped_event = EventValue::model()->with('calendarEvent')->findbyPk($defn{'capped_value'});
                    if (!$capped_event) {
                        echo CJSON::encode(array(
                            'success' => -5,
                            'msg' => 'No starting events to end were found'
                        ));
                        Yii::app()->end();
                    }
                    $capping_event = new EventValue();
                    $capping_event->calendar_event_id = $cal_id;
                    $capping_event->value = "That began on " . $this->formatCappingDate($capped_event->calendarEvent->start_date);
                    $capping_event->event_definition_id = $defn{'definition_id'};
                    $capping_event->save();
                    $capped_event->capped_event_value_id = $capping_event->event_value_id;
                    $capped_event->save();
                }
            }
        }
//var_dump($cal->date_created);exit;
        echo CJSON::encode(array(
            'success' => 1,
            'calendar_event_id' => $cal->calendar_event_id,
            'date_created' => $cal->date_created
        ));
        Yii::app()->end();
    }

    /* For DnD new start/end date and times */
    public function actionUpdateCalendarEvent()
    {

    }

    /*
	 * For deleting a calendar event
	 */
    public function actionDeleteCalendarEvent()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        header('Content-type: application/json');
        $calendar_event_id = Yii::app()->request->getPost('calendar_event', '');
        if (!$calendar_event_id) {
            echo CJSON::encode(array(
                'success' => 0,
                'error' => 'Invalid calendar_event_id',
            ));
            Yii::app()->end();
        }
        $condition = array('calendar_event_id' => $calendar_event_id);
        /*
		$cal = CalendarEvent::model()->findByAttributes(array('calendar_event_id'=>$calendar_event_id));
		
		if (!$cal) {
			header('Content-type: application/json');
			echo CJSON::encode(array(
					'success'=>0,
					'error'=>'Invalid calendar_event_id',
			));
			Yii::app()->end();
		}
		*/
        /*
		 * The corresponding event value(s)
		 */
        try {
            EventValue::model()->deleteAllByAttributes($condition);
            CalendarEvent::model()->deleteAllByAttributes($condition);
            echo CJSON::encode(array(
                'success' => 1,
            ));
            Yii::app()->end();
        } catch (Exception $e) {

            echo CJSON::encode(array(
                'success' => -1,
                'msg' => 'Failed to delete selected calendar event'
            ));
            Yii::app()->end();
        }
    }

    /*
	 * Perhaps deprecate
	 */
    public function actionAttachCalendarToDate()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        $get_cal_id = Yii::app()->request->getPost('calendar_event_id', '');
        if (!$get_cal_id) {
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'success' => 0,
                'error' => 'Invalid calendar_event_id',
            ));
            Yii::app()->end();
        }
        $cal = CalendarEvent::model()->findByAttributes(array('calendar_event_id' => $get_cal_id));
        if (!$cal) {
            header('Content-type: application/json');
            echo CJSON::encode(array(
                'success' => 0,
                'error' => 'Invalid calendar_event_id',
            ));
            Yii::app()->end();
        }
        $event_date = Yii::app()->request->getPost('calendar_date', '');
        // What format will this be in?
        // Do some translation to date format here
        $cal->event_date = $event_date;
        $cal->description = Yii::app()->request->getPost('description', '');
        $cal->quantity = (int)Yii::app()->request->getPost('quantity', 0);
        $cal->save();
        header('Content-type: application/json');
        echo CJSON::encode(array(
            'success' => 1,
            'calendar_event_id' => $cal->calendar_event_id,
            'event_name' => $cal->event_name,
            'event_date' => $cal->event_date,
        ));
        Yii::app()->end();
    }

    private function getMyTaggedJournals()
    {
        $user_id = Yii::app()->user->id;
        if (!$user_id) return array();

        $note_status = NoteStatus::model()->findByAttributes(array('name' => 'Published'));
        $condition = 'user_id = ' . $user_id . ' and status_id=' . $note_status->status_id;

        $tagHash = array();
        $notes = Note::model()->with('tags')->findAll(array(
            'condition' => $condition,
            'order' => 'publish_date DESC'
        ));

        foreach ($notes as $note) {
            foreach ($note->tags as $tag) {
                if (!array_key_exists($tag->tag, $tagHash)) {
                    $tagHash[$tag->tag] = 1;
                } else {
                    $tagHash[$tag->tag]++;
                }
            }
        }
        return $tagHash;
    }

    private function getMyJournalsByTag($tag, $offset, $limit)
    {
        $user_id = Yii::app()->user->id;
        if (!$user_id) return array();
        if (!$tag) return array();
        if (!$offset) $offset = 0;
        if (!$limit) $limit = $this->RECENTPOSTCOUNT;

        $note_status = NoteStatus::model()->findByAttributes(array('name' => 'Published'));
        $condition = 'user_id = ' . $user_id . ' and status_id=' . $note_status->status_id;
        /*
		$count = Note::model()->with(array(
			'tags'=>array(
				'condition'=>"tag='".$tag."'"
		)
		))->count(array(
			'condition'=>$condition
		)
		);
		*/
        $notes = Note::model()->with(array(
            'tags' => array(
                'condition' => "tag='" . $tag . "'"
            )
        ))->findAll(array(
                'condition' => $condition,
                'order' => 'publish_date DESC'
                /*,
			'limit'=>$limit,
			'offset'=>$offset*/
            )
        );
        /*
		 * I can't get limit and offset to work when using a MANY_MANY relation
		 * so I am currently applying that rule post query
		 */
        $paredNotes = array();
        $start = $offset;
        $end = $offset + $limit;
        $counter = 0;
        foreach ($notes as $note) {
            if ($counter >= $start && $counter < $end) {
                array_push($paredNotes, $note);
            }
            $counter++;
        }
        $results = array('limit' => $limit, 'count' => count($notes), 'nData' => count($paredNotes), 'offset' => $offset, 'data' => array());
        $results['data'] = $this->unrollNotes($paredNotes);
        return $results;
    }

    private function getMyJournalsByDate($dateObj, $offset, $limit)
    {
        if (!$offset) $offset = 0;
        if (!$limit) $limit = $this->RECENTPOSTCOUNT;
        $user_id = Yii::app()->user->id;
        if (!$user_id) return array();

        if (!$dateObj['year']) $year = date('Y');
        else $year = $dateObj['year'];
        if (!$dateObj['month']) $month = date('m');
        else $month = $dateObj['month'];

        $note_status = NoteStatus::model()->findByAttributes(array('name' => 'Published'));
        $condition = 'user_id = ' . $user_id . ' and status_id=' . $note_status->status_id;

        if ($dateObj['day']) {
            $condition = $condition . " and date(publish_date) = date('" . $year . "-" . $month . "-" . $dateObj['day'] . "') ";
        } else {
            $condition = $condition . " and extract(year from publish_date)='" . $year . "'";
            $condition = $condition . " and extract(month from publish_date)='" . $month . "'";
        }

        $count = Note::model()->count($condition);

        $criteria = new CDbCriteria(
            array(
                'condition' => $condition,
                'limit' => $limit,
                'offset' => $offset,
                'order' => 'publish_date DESC, publish_time DESC'
            )
        );
        $notes = Note::model()->findAll($criteria);
        $results = array('count' => $count, 'nData' => count($notes), 'offset' => $offset, 'data' => array());
        $results['data'] = $this->unrollNotes($notes);
        return $results;
    }

    private function journalPager($offset, $limit)
    {
        if (!$offset) $offset = 0;
        if (!$limit) $limit = $this->RECENTPOSTCOUNT;
        $user_id = Yii::app()->user->id;
        if (!$user_id) return array();

        $note_status = NoteStatus::model()->findByAttributes(array('name' => 'Published'));
        $condition = 'user_id=' . $user_id . ' and status_id=' . $note_status->status_id;
        $count = Note::model()->findAll($condition);//countByAttributes(array('user_id'=>$user_id));
        $criteria = new CDbCriteria(
            array(
                'condition' => 'user_id=' . $user_id . ' and status_id=' . $note_status->status_id,
                'limit' => $limit,
                'offset' => $offset,
                'order' => 'publish_date desc'
            )
        );
        $notes = Note::model()->findAll($criteria);

        $results = array('count' => count($count), 'nData' => count($notes), 'offset' => $offset, 'data' => array());
        $results['data'] = $this->unrollNotes($notes);
        return $results;
	
    }

    private function unrollNotes($notes)
    {
        $data = array();
        foreach ($notes as $note) {
            $visibility = 'Unknown';
            if ($note->noteVisibility) {
                $visibility = $note->noteVisibility->name;
            }
            $entry = array(
                'id' => $note->note_id,
                'title' => $note->title,
                'content' => $note->content,
                'visibility' => $visibility,
                'date_created' => $note->date_created,
                'publish_date' => $note->publish_date,
                'publish_time' => $note->publish_time,
                'status' => $note->noteStatus->name
            );
            array_push($data, $entry);
        }
        return $data;
    }

    private function getJournalDates()
    {
        $user_id = Yii::app()->user->id;
        if (!$user_id) {
            return array();
        }

        $note_status = NoteStatus::model()->findByAttributes(array('name' => 'Published'));
        $condition = 'user_id = ' . $user_id . ' and status_id=' . $note_status->status_id;

        $dateHash = array();
        $dates = Note::model()->findAll(array(
            'condition' => $condition,
            'order' => 'publish_date DESC'
        ));

        foreach ($dates as $key => $value) {
            $time = strtotime($value->publish_date);
            $year = date('Y', $time);
            $month = date('m', $time);
            $day = date('d', $time);
            if (!array_key_exists($year, $dateHash)) {
                $dateHash[$year] = array();
                $dateHash[$year][$month] = array();
                $dateHash[$year][$month][$day] = 1;
            } else {
                if (!array_key_exists($month, $dateHash[$year])) {
                    $dateHash[$year][$month] = array();
                    //array_push($dateHash[$year][$month],$day);
                    $dateHash[$year][$month][$day] = 1;
                } else {
                    /*
					if (!in_array($day,$dateHash[$year][$month])) {
						array_push($dateHash[$year][$month],$day);
					}
					*/
                    if (!array_key_exists($day, $dateHash[$year][$month])) {
                        $dateHash[$year][$month][$day] = 1;
                    } else {
                        $dateHash[$year][$month][$day] = $dateHash[$year][$month][$day] + 1;
                    }
                }
            }
        }
        
        return $dateHash;
    }

    public function _createAEResponse($content='',$publication_date,$publication_time) {
    	$content = urlencode($content);
    	$post_data = array('content' => $content);
    	$_start = round(microtime(true) * 1000);
    	$raw_response = MyStuff::curl_request(Yii::app()->params['analysis_engine_url'], $post_data);
    	$_end = round(microtime(true) * 1000);
    	$_diff = $_end  - $_start;
    	MyStuff::Log("ANALYSIS ".$_end.' '.$_start.' '.$_diff);
    	
    	$start = 0;
    	$ae_data = array();
    	$matches = array();
    	$parse_start = round(microtime(true) * 1000);
    	foreach (explode("\n", $raw_response) as $line) {
    		$line = trim($line);
    		if ($line == "{") {
    			$start = 1;
    			continue;
    		}
    		if ($line == "}") {
    			$start = 0;
    			break;
    		}
    		if ($start) {
    			$parts = explode(": ", $line);
    			preg_match('/"(.*)"/', $parts[0], $matches);
    			$key = $matches[1];
    			$value = '';
    			if (preg_match('/"(.*)"/', $parts[1], $matches)) {
    				$value = $matches[1];
    			} else {
    				$value = $parts[1];
    			}
    			$value = preg_replace('/,$/', '', $value);
    			$ae_data[$key] = $value;
    		}
    	}
    	$parse_end = round(microtime(true) * 1000);
    	$ae_write_start = round(microtime(true) * 1000);
    	$ae_response = new AeResponse();
    	$ae_response->words = (int)$ae_data['words'];
    	$ae_response->sentences = (int)$ae_data['sentences'];
    	$ae_response->hits = (int)$ae_data['hits'];
    	$ae_response->response_ts = $ae_data['timestamp'];
    	$ae_response->json_response = $raw_response;
    	$ae_response->user_id = Yii::app()->user->id;
    	$ae_response->source = 'blog';
    	/* The analysis should correspond to the publication date */
    	$ae_response->date_created = date('Y-m-d H:i:s', strtotime($publication_date . ' ' . $publication_time));
    	$ae_save_start = round(microtime(true) * 1000);
    	$ae_response->save();
    	$ae_save_end = round(microtime(true) * 1000);
    	$category_start = round(microtime(true) * 1000);
    	if ($ae_response) {
    		$ae_categories = Category::model()->findAllByAttributes(array('category_type' => 'mood'));
    		$valueStr = "";
    		foreach ($ae_categories as $category) {
    			if (isset($ae_data[$category->description])) {
    				$value = (float)$ae_data[$category->description];
    				$valueStr = $valueStr."($ae_response->ae_response_id,$category->category_id,$value),";
    			}
    			
    		}
    		$valueStr = preg_replace('/,$/', '', $valueStr);
    		$sql = "insert into ae_response_category(ae_response_id,category_id,value) values $valueStr";//($ae_response->ae_response_id,$category->category_id,$value)";
    		Yii::app()->db->createCommand($sql)->execute();
    		
    		$user_id = Yii::app()->user->Id;
    		$valueStr = "";
    		for ($i = 1; $i <= 10; $i++) {
    			$get_word = 'topWords' . $i;
    			$get_count = 'topWordsCnt' . $i;
    			if ($ae_data[$get_word] && $ae_data[$get_count]) {
    				$count = intval($ae_data[$get_count]);
    				$insert_value = preg_replace("/'/","''",$ae_data[$get_word]);
    				$valueStr = $valueStr."($user_id,$ae_response->ae_response_id,$i,'$insert_value',$count),";
    			}
    		}
    		$valueStr = preg_replace('/,$/', '', $valueStr);
    		$sql = "insert into top_words(user_id,ae_response_id,ae_rank,ae_value,count) values $valueStr";
    		
    		Yii::app()->db->createCommand($sql)->execute();
    		$valueStr = "";
    		for ($i = 1; $i <= 10; $i++) {
    			$get_word = 'topPeople' . $i;
    			$get_count = 'topPeopleCnt' . $i;
    			if ($ae_data[$get_word] && $ae_data[$get_count]) {
    				$count = intval($ae_data[$get_count]);
    				$insert_value = preg_replace("/'/","''",$ae_data[$get_word]);
    				$valueStr = $valueStr."($user_id,$ae_response->ae_response_id,$i,'$insert_value',$count),";
    			}
    		}
    		$valueStr = preg_replace('/,$/', '', $valueStr);
    		$sql = "insert into top_people(user_id,ae_response_id,ae_rank,ae_value,count) values $valueStr";
    		Yii::app()->db->createCommand($sql)->execute();
    		
    		$category_end = round(microtime(true) * 1000);
    		$diff = $parse_end - $parse_start;
    		MyStuff::Log("PARSE ".$parse_end.' '.$parse_start.' '.$diff);
    		$diff = $ae_save_end - $ae_save_start;
    		MyStuff::Log("AE SAVE ".$ae_save_end.' '.$ae_save_start.' '.$diff);
    		$diff = $category_end - $category_start;
    		MyStuff::Log("CATEGORY SAVE ".$category_end.' '.$category_start.' '.$diff);
    		return $ae_response->ae_response_id;
    	}    	
    }
    
    private function createAEResponse($content = '')
    {
        if ($content == '') {
            $content = Yii::app()->request->getPost('stripped_content', '');
        }

        $publication_date = Yii::app()->request->getPost('publish_date', '');
        $publication_time = Yii::app()->request->getPost('publish_time', '');
        return $this->_createAEResponse($content,$publication_date,$publication_time);
    }

    private function getAEResponse($ae_response_id)
    {
        $return = array();
        $sql = "select user_id, words, sentences, hits, date_created
from ae_response
where ae_response_id = $ae_response_id";
        $obj = Yii::app()->db->createCommand($sql)->queryRow();
        $return['ae_response_id'] = $ae_response_id;
        $return['user_id'] = $obj['user_id'];
        $return['words'] = $obj['words'];
        $return['sentences'] = $obj['sentences'];
        $return['hits'] = $obj['hits'];
        $return['date_created'] = $obj['date_created'];

        $sql = "select c.description category,
  arc.value category_value
from ae_response_category arc
join category c on arc.category_id = c.category_id
where arc.ae_response_id = $ae_response_id";
        foreach (Yii::app()->db->createCommand($sql)->queryAll() as $obj) {
            $return[$obj['category']] = $obj['category_value'];
        }
        $sql = "select tw.ae_rank,
  tw.ae_value,
  tw.count,
  tw.score
from top_words tw
where tw.ae_response_id = $ae_response_id";
        foreach (Yii::app()->db->createCommand($sql)->queryAll() as $obj) {
            $i = $obj['ae_rank'];
            $return['topWords' . $i] = $obj['ae_value'];
            $return['topWordsCnt' . $i] = $obj['count'];
            $return['topWordsScore' . $i] = $obj['score'];
        }
        $sql = "select tp.ae_rank,
  tp.ae_value,
  tp.count,
  tp.score
from top_people tp
where tp.ae_response_id = $ae_response_id";
        foreach (Yii::app()->db->createCommand($sql)->queryAll() as $obj) {
            $i = $obj['ae_rank'];
            $return['topPeople' . $i] = $obj['ae_value'];
            $return['topPeopleCnt' . $i] = $obj['count'];
            $return['topPeopleScore' . $i] = $obj['score'];
        }
        return $return;
    }

    private function getDailyAE($user_id, $get_date = NULL)
    {
        $ajd = AeJournalDaily::model()->findAllByAttributes(array('user_id' => $user_id));
        if (!$get_date) {
            $get_date = "curdate()";
        }
        $sql = "select ae_response_id
from ae_journal_daily
where user_id = $user_id
  and date_created = $get_date";
        $ajd = Yii::app()->db->createCommand($sql)->queryRow();
        if ($ajd and $ajd['ae_response_id'] > 0) {
            return $this->getAEResponse($ajd['ae_response_id']);
        }
    }

    public function actionTest()
    {
        $this->runAEJournalDaily('2014-09-24');
        // $ajd->ae_journal_daily_id;

        // Re-run active journal entries for the day

        //MyStuff::log($this->getDailyAE($user_id, '2014-09-23'));

        //$this->getRandomQuestion();
        // for form testing
        /*
		if (Yii::app()->request->getPost('submit', '')) {
    		//MyStuff::log($upl);
    		//$upl->upload_file=CUploadedFile::getInstance($model,'upload_file');
			//$upl->upload_file->saveAs("C:\myProject\images\".$model->upload_file->name)
    	}
    	*/

        /*
    	$note = Note::model()->findByAttributes(array(
    			'note_id'=> 3
    	));
    	*/

        //header('Content-type: application/json');
        //Yii::app()->end();
        //MyStuff::log("sending: ".$note->content);
        //MyStuff::log("sending: Jeff loves small animals and seems to like his eggs scrambled with toothpaste and green beans. Oh how he thanks God for the $3 it costs him.");

        //$this->actionCreateJournal();
        //$this->createAEResponse();
        //$raw_response = MyStuff::curl_request(Yii::app()->params['analysis_engine_url'], array('content'=>'some data'));
        #	$this->mean_score_per_day(20);
        #$this->layout = 'arqLayout2';
        $this->render('test');
    }

    public function actionTestLoadAer()
    {
        #MyStuff::Log('here?');
        $test1 = '{
	"timestamp": 1399060885,
	"date": "5/2/2014",
	"time": "13:1:25",
	"words": 74,
	"sentences": 9,
	"negative": 0.38,
	"positive": 0.63,
	"thinking": 0.67,
	"feeling": 0.33,
	"fantasy": 0.45,
	"reality": 0.55,
	"passive": 0.25,
	"proactive": 0.75,
	"disconnected": 0.58,
	"connected": 0.42,
	"happy": 1.00,
	"anxious": 0.00,
	"sad": 0.00,
	"angry": 0.00,
	"love": 0.00,
	"sex": 0.00,
	"family": 0.00,
	"ambition": 0.00,
	"leisure": 0.10,
	"religion": 0.00
	"health": 0.60,
	"work": 0.30,
	"money": 0.00,
	"school": 0.00,
	"home": 0.00,
	"death": 0.00,
	"self": 0.00,
	"food": 0.00,
	"fitness": 0.00,
	"topWords1": "i",
	"topWords2": "to",
	"topWords3": "and",
	"topWords4": "my",
	"topWords5": "is",
	"topWords6": "or",
	"topWords7": "ll",
	"topWords8": "have",
	"topWords9": "like",
	"topWords10": "really",
	"topPeople1": "Alice",
	"topPeople2": "Bob",
	"topPeople3": "Bill",
	"topPeople4": "Jack",
	"topPeople5": "Jill",
	"topPeople6": "Muffy",
	"topPeople7": "Buffy",
	"topPeople8": "Tim",
	"topPeople9": "Al",
	"topPeople10": "Jimbo",
	"topWordsCnt1": 4,
	"topWordsCnt2": 4,
	"topWordsCnt3": 2,
	"topWordsCnt4": 2,
	"topWordsCnt5": 2,
	"topWordsCnt6": 2,
	"topWordsCnt7": 2,
	"topWordsCnt8": 2,
	"topWordsCnt9": 2,
	"topWordsCnt10": 1,
	"topPeopleCnt1": 12,
	"topPeopleCnt2": 23,
	"topPeopleCnt3": 14,
	"topPeopleCnt4": 38,
	"topPeopleCnt5": 35,
	"topPeopleCnt6": 33,
	"topPeopleCnt7": 30,
	"topPeopleCnt8": 41,
	"topPeopleCnt9": 12,
	"topPeopleCnt10": 9
}';
        $test2 = '{
	"timestamp": 1399060906,
	"date": "5/2/2014",
	"time": "13:1:46",
	"words": 164,
	"sentences": 12,
	"negative": 0.25,
	"positive": 0.75,
	"thinking": 0.68,
	"feeling": 0.32,
	"fantasy": 0.48,
	"reality": 0.52,
	"passive": 0.34,
	"proactive": 0.66,
	"disconnected": 0.45,
	"connected": 0.55,
	"happy": 1.00,
	"anxious": 0.00,
	"sad": 0.00,
	"angry": 0.00,
	"love": 0.04,
	"sex": 0.04,
	"family": 0.12,
	"ambition": 0.00,
	"leisure": 0.08,
	"religion": 0.04
	"health": 0.27,
	"work": 0.35,
	"money": 0.04,
	"school": 0.00,
	"home": 0.04,
	"death": 0.00,
	"self": 0.00,
	"food": 0.00,
	"fitness": 0.00,
	"topWords1": "to",
	"topWords2": "and",
	"topWords3": "me",
	"topWords4": "it",
	"topWords5": "i",
	"topWords6": "the",
	"topWords7": "for",
	"topWords8": "a",
	"topWords9": "was",
	"topWords10": "of",
	"topPeople1": "Alice",
	"topPeople2": "Bob",
	"topPeople3": "Bill",
	"topPeople4": "Jack",
	"topPeople5": "Jill",
	"topPeople6": "Muffy",
	"topPeople7": "Buffy",
	"topPeople8": "Tim",
	"topPeople9": "Al",
	"topPeople10": "Jimbo",
	"topWordsCnt1": 9,
	"topWordsCnt2": 6,
	"topWordsCnt3": 6,
	"topWordsCnt4": 6,
	"topWordsCnt5": 5,
	"topWordsCnt6": 5,
	"topWordsCnt7": 4,
	"topWordsCnt8": 4,
	"topWordsCnt9": 4,
	"topWordsCnt10": 4,
	"topPeopleCnt1": 43,
	"topPeopleCnt2": 30,
	"topPeopleCnt3": 8,
	"topPeopleCnt4": 3,
	"topPeopleCnt5": 40,
	"topPeopleCnt6": 19,
	"topPeopleCnt7": 23,
	"topPeopleCnt8": 20,
	"topPeopleCnt9": 26,
	"topPeopleCnt10": 39
}';
        $test3 = '{
	"timestamp": 1399060917,
	"date": "5/2/2014",
	"time": "13:1:57",
	"words": 299,
	"sentences": 17,
	"negative": 0.50,
	"positive": 0.50,
	"thinking": 0.71,
	"feeling": 0.29,
	"fantasy": 0.40,
	"reality": 0.60,
	"passive": 0.22,
	"proactive": 0.78,
	"disconnected": 0.52,
	"connected": 0.48,
	"happy": 0.75,
	"anxious": 0.08,
	"sad": 0.00,
	"angry": 0.17,
	"love": 0.02,
	"sex": 0.02,
	"family": 0.10,
	"ambition": 0.00,
	"leisure": 0.10,
	"religion": 0.10
	"health": 0.33,
	"work": 0.21,
	"money": 0.00,
	"school": 0.00,
	"home": 0.11,
	"death": 0.02,
	"self": 0.00,
	"food": 0.00,
	"fitness": 0.00,
	"topWords1": "i",
	"topWords2": "the",
	"topWords3": "that",
	"topWords4": "to",
	"topWords5": "and",
	"topWords6": "with",
	"topWords7": "a",
	"topWords8": "of",
	"topWords9": "my",
	"topWords10": "will",
	"topPeople1": "Alice",
	"topPeople2": "Bob",
	"topPeople3": "Bill",
	"topPeople4": "Jack",
	"topPeople5": "Jill",
	"topPeople6": "Muffy",
	"topPeople7": "Buffy",
	"topPeople8": "Tim",
	"topPeople9": "Al",
	"topPeople10": "Jimbo",
	"topWordsCnt1": 29,
	"topWordsCnt2": 13,
	"topWordsCnt3": 12,
	"topWordsCnt4": 10,
	"topWordsCnt5": 8,
	"topWordsCnt6": 8,
	"topWordsCnt7": 7,
	"topWordsCnt8": 5,
	"topWordsCnt9": 5,
	"topWordsCnt10": 5,
	"topPeopleCnt1": 25,
	"topPeopleCnt2": 49,
	"topPeopleCnt3": 26,
	"topPeopleCnt4": 44,
	"topPeopleCnt5": 16,
	"topPeopleCnt6": 36,
	"topPeopleCnt7": 34,
	"topPeopleCnt8": 8,
	"topPeopleCnt9": 46,
	"topPeopleCnt10": 12
}';
        $test4 = '{
	"timestamp": 1399060927,
	"date": "5/2/2014",
	"time": "13:2:7",
	"words": 218,
	"sentences": 13,
	"negative": 0.44,
	"positive": 0.56,
	"thinking": 0.56,
	"feeling": 0.44,
	"fantasy": 0.47,
	"reality": 0.53,
	"passive": 0.26,
	"proactive": 0.74,
	"disconnected": 0.45,
	"connected": 0.55,
	"happy": 0.80,
	"anxious": 0.10,
	"sad": 0.00,
	"angry": 0.10,
	"love": 0.04,
	"sex": 0.04,
	"family": 0.14,
	"ambition": 0.00,
	"leisure": 0.11,
	"religion": 0.00
	"health": 0.57,
	"work": 0.07,
	"money": 0.00,
	"school": 0.00,
	"home": 0.04,
	"death": 0.00,
	"self": 0.00,
	"food": 0.00,
	"fitness": 0.00,
	"topWords1": "a",
	"topWords2": "i",
	"topWords3": "and",
	"topWords4": "the",
	"topWords5": "s",
	"topWords6": "in",
	"topWords7": "you",
	"topWords8": "have",
	"topWords9": "of",
	"topWords10": "this",
	"topPeople1": "Alice",
	"topPeople2": "Bob",
	"topPeople3": "Bill",
	"topPeople4": "Jack",
	"topPeople5": "Jill",
	"topPeople6": "Muffy",
	"topPeople7": "Buffy",
	"topPeople8": "Tim",
	"topPeople9": "Al",
	"topPeople10": "Jimbo",
	"topWordsCnt1": 10,
	"topWordsCnt2": 9,
	"topWordsCnt3": 8,
	"topWordsCnt4": 6,
	"topWordsCnt5": 5,
	"topWordsCnt6": 5,
	"topWordsCnt7": 4,
	"topWordsCnt8": 4,
	"topWordsCnt9": 4,
	"topWordsCnt10": 4,
	"topPeopleCnt1": 14,
	"topPeopleCnt2": 18,
	"topPeopleCnt3": 20,
	"topPeopleCnt4": 3,
	"topPeopleCnt5": 19,
	"topPeopleCnt6": 33,
	"topPeopleCnt7": 24,
	"topPeopleCnt8": 20,
	"topPeopleCnt9": 22,
	"topPeopleCnt10": 43
}';
        $test5 = '{
	"timestamp": 1399060938,
	"date": "5/2/2014",
	"time": "13:2:18",
	"words": 182,
	"sentences": 44,
	"negative": 0.19,
	"positive": 0.81,
	"thinking": 0.63,
	"feeling": 0.37,
	"fantasy": 0.39,
	"reality": 0.61,
	"passive": 0.28,
	"proactive": 0.72,
	"disconnected": 0.48,
	"connected": 0.52,
	"happy": 0.90,
	"anxious": 0.00,
	"sad": 0.00,
	"angry": 0.10,
	"love": 0.06,
	"sex": 0.06,
	"family": 0.06,
	"ambition": 0.00,
	"leisure": 0.06,
	"religion": 0.06
	"health": 0.18,
	"work": 0.39,
	"money": 0.06,
	"school": 0.00,
	"home": 0.06,
	"death": 0.00,
	"self": 0.00,
	"food": 0.00,
	"fitness": 0.00,
	"topWords1": "i",
	"topWords2": "the",
	"topWords3": "things",
	"topWords4": "to",
	"topWords5": "s",
	"topWords6": "my",
	"topWords7": "for",
	"topWords8": "tag",
	"topWords9": "rebecca",
	"topWords10": "looking",
	"topPeople1": "Alice",
	"topPeople2": "Bob",
	"topPeople3": "Bill",
	"topPeople4": "Jack",
	"topPeople5": "Jill",
	"topPeople6": "Muffy",
	"topPeople7": "Buffy",
	"topPeople8": "Tim",
	"topPeople9": "Al",
	"topPeople10": "Jimbo",
	"topWordsCnt1": 8,
	"topWordsCnt2": 4,
	"topWordsCnt3": 3,
	"topWordsCnt4": 3,
	"topWordsCnt5": 3,
	"topWordsCnt6": 3,
	"topWordsCnt7": 3,
	"topWordsCnt8": 2,
	"topWordsCnt9": 2,
	"topWordsCnt10": 2,
	"topPeopleCnt1": 19,
	"topPeopleCnt2": 2,
	"topPeopleCnt3": 12,
	"topPeopleCnt4": 12,
	"topPeopleCnt5": 30,
	"topPeopleCnt6": 47,
	"topPeopleCnt7": 47,
	"topPeopleCnt8": 48,
	"topPeopleCnt9": 31,
	"topPeopleCnt10": 34
}';
        $test6 = '{
	"timestamp": 1399060945,
	"date": "5/2/2014",
	"time": "13:2:25",
	"words": 617,
	"sentences": 43,
	"negative": 0.37,
	"positive": 0.63,
	"thinking": 0.60,
	"feeling": 0.40,
	"fantasy": 0.47,
	"reality": 0.53,
	"passive": 0.36,
	"proactive": 0.64,
	"disconnected": 0.38,
	"connected": 0.62,
	"happy": 0.89,
	"anxious": 0.07,
	"sad": 0.00,
	"angry": 0.04,
	"love": 0.06,
	"sex": 0.06,
	"family": 0.09,
	"ambition": 0.00,
	"leisure": 0.12,
	"religion": 0.05
	"health": 0.28,
	"work": 0.22,
	"money": 0.02,
	"school": 0.00,
	"home": 0.09,
	"death": 0.00,
	"self": 0.00,
	"food": 0.00,
	"fitness": 0.00,
	"topWords1": "i",
	"topWords2": "and",
	"topWords3": "the",
	"topWords4": "we",
	"topWords5": "to",
	"topWords6": "a",
	"topWords7": "he",
	"topWords8": "that",
	"topWords9": "in",
	"topWords10": "so",
	"topPeople1": "Alice",
	"topPeople2": "Bob",
	"topPeople3": "Bill",
	"topPeople4": "Jack",
	"topPeople5": "Jill",
	"topPeople6": "Muffy",
	"topPeople7": "Buffy",
	"topPeople8": "Tim",
	"topPeople9": "Al",
	"topPeople10": "Jimbo",
	"topWordsCnt1": 30,
	"topWordsCnt2": 24,
	"topWordsCnt3": 24,
	"topWordsCnt4": 18,
	"topWordsCnt5": 16,
	"topWordsCnt6": 13,
	"topWordsCnt7": 11,
	"topWordsCnt8": 10,
	"topWordsCnt9": 9,
	"topWordsCnt10": 8,
	"topPeopleCnt1": 42,
	"topPeopleCnt2": 27,
	"topPeopleCnt3": 4,
	"topPeopleCnt4": 38,
	"topPeopleCnt5": 26,
	"topPeopleCnt6": 41,
	"topPeopleCnt7": 18,
	"topPeopleCnt8": 44,
	"topPeopleCnt9": 4,
	"topPeopleCnt10": 2
}';
        $test7 = '{
	"timestamp": 1399060953,
	"date": "5/2/2014",
	"time": "13:2:33",
	"words": 306,
	"sentences": 41,
	"negative": 0.53,
	"positive": 0.47,
	"thinking": 0.71,
	"feeling": 0.29,
	"fantasy": 0.43,
	"reality": 0.57,
	"passive": 0.26,
	"proactive": 0.74,
	"disconnected": 0.60,
	"connected": 0.40,
	"happy": 0.58,
	"anxious": 0.25,
	"sad": 0.00,
	"angry": 0.17,
	"love": 0.02,
	"sex": 0.02,
	"family": 0.05,
	"ambition": 0.00,
	"leisure": 0.07,
	"religion": 0.00
	"health": 0.40,
	"work": 0.26,
	"money": 0.12,
	"school": 0.00,
	"home": 0.07,
	"death": 0.00,
	"self": 0.00,
	"food": 0.00,
	"fitness": 0.00,
	"topWords1": "i",
	"topWords2": "the",
	"topWords3": "and",
	"topWords4": "my",
	"topWords5": "to",
	"topWords6": "it",
	"topWords7": "just",
	"topWords8": "is",
	"topWords9": "what",
	"topWords10": "that",
	"topPeople1": "Alice",
	"topPeople2": "Bob",
	"topPeople3": "Bill",
	"topPeople4": "Jack",
	"topPeople5": "Jill",
	"topPeople6": "Muffy",
	"topPeople7": "Buffy",
	"topPeople8": "Tim",
	"topPeople9": "Al",
	"topPeople10": "Jimbo",
	"topWordsCnt1": 22,
	"topWordsCnt2": 12,
	"topWordsCnt3": 10,
	"topWordsCnt4": 8,
	"topWordsCnt5": 7,
	"topWordsCnt6": 7,
	"topWordsCnt7": 6,
	"topWordsCnt8": 6,
	"topWordsCnt9": 5,
	"topWordsCnt10": 5,
	"topPeopleCnt1": 3,
	"topPeopleCnt2": 36,
	"topPeopleCnt3": 13,
	"topPeopleCnt4": 9,
	"topPeopleCnt5": 39,
	"topPeopleCnt6": 5,
	"topPeopleCnt7": 42,
	"topPeopleCnt8": 34,
	"topPeopleCnt9": 10,
	"topPeopleCnt10": 8
}';
        $test8 = '{
	"timestamp": 1399060959,
	"date": "5/2/2014",
	"time": "13:2:39",
	"words": 571,
	"sentences": 68,
	"negative": 0.55,
	"positive": 0.45,
	"thinking": 0.64,
	"feeling": 0.36,
	"fantasy": 0.41,
	"reality": 0.59,
	"passive": 0.23,
	"proactive": 0.77,
	"disconnected": 0.55,
	"connected": 0.45,
	"happy": 0.68,
	"anxious": 0.07,
	"sad": 0.02,
	"angry": 0.22,
	"love": 0.04,
	"sex": 0.04,
	"family": 0.04,
	"ambition": 0.00,
	"leisure": 0.14,
	"religion": 0.05
	"health": 0.39,
	"work": 0.25,
	"money": 0.04,
	"school": 0.00,
	"home": 0.02,
	"death": 0.00,
	"self": 0.00,
	"food": 0.00,
	"fitness": 0.00,
	"topWords1": "i",
	"topWords2": "to",
	"topWords3": "and",
	"topWords4": "is",
	"topWords5": "the",
	"topWords6": "my",
	"topWords7": "so",
	"topWords8": "in",
	"topWords9": "a",
	"topWords10": "that",
	"topPeople1": "Alice",
	"topPeople2": "Bob",
	"topPeople3": "Bill",
	"topPeople4": "Jack",
	"topPeople5": "Jill",
	"topPeople6": "Muffy",
	"topPeople7": "Buffy",
	"topPeople8": "Tim",
	"topPeople9": "Al",
	"topPeople10": "Jimbo",
	"topWordsCnt1": 32,
	"topWordsCnt2": 19,
	"topWordsCnt3": 15,
	"topWordsCnt4": 13,
	"topWordsCnt5": 12,
	"topWordsCnt6": 11,
	"topWordsCnt7": 10,
	"topWordsCnt8": 9,
	"topWordsCnt9": 9,
	"topWordsCnt10": 8,
	"topPeopleCnt1": 11,
	"topPeopleCnt2": 43,
	"topPeopleCnt3": 19,
	"topPeopleCnt4": 4,
	"topPeopleCnt5": 44,
	"topPeopleCnt6": 16,
	"topPeopleCnt7": 21,
	"topPeopleCnt8": 24,
	"topPeopleCnt9": 4,
	"topPeopleCnt10": 46
}';
        $test9 = '{
	"timestamp": 1399060964,
	"date": "5/2/2014",
	"time": "13:2:44",
	"words": 73,
	"sentences": 13,
	"negative": 0.58,
	"positive": 0.42,
	"thinking": 0.79,
	"feeling": 0.21,
	"fantasy": 0.33,
	"reality": 0.67,
	"passive": 0.18,
	"proactive": 0.82,
	"disconnected": 0.68,
	"connected": 0.32,
	"happy": 1.00,
	"anxious": 0.00,
	"sad": 0.00,
	"angry": 0.00,
	"love": 0.05,
	"sex": 0.05,
	"family": 0.00,
	"ambition": 0.00,
	"leisure": 0.14,
	"religion": 0.00
	"health": 0.41,
	"work": 0.23,
	"money": 0.05,
	"school": 0.00,
	"home": 0.00,
	"death": 0.09,
	"self": 0.00,
	"food": 0.00,
	"fitness": 0.00,
	"topWords1": "i",
	"topWords2": "to",
	"topWords3": "alive",
	"topWords4": "not",
	"topWords5": "have",
	"topWords6": "like",
	"topWords7": "lazy",
	"topWords8": "that",
	"topWords9": "for",
	"topWords10": "all",
	"topPeople1": "Alice",
	"topPeople2": "Bob",
	"topPeople3": "Bill",
	"topPeople4": "Jack",
	"topPeople5": "Jill",
	"topPeople6": "Muffy",
	"topPeople7": "Buffy",
	"topPeople8": "Tim",
	"topPeople9": "Al",
	"topPeople10": "Jimbo",
	"topWordsCnt1": 6,
	"topWordsCnt2": 3,
	"topWordsCnt3": 2,
	"topWordsCnt4": 2,
	"topWordsCnt5": 2,
	"topWordsCnt6": 2,
	"topWordsCnt7": 2,
	"topWordsCnt8": 2,
	"topWordsCnt9": 1,
	"topWordsCnt10": 1,
	"topPeopleCnt1": 2,
	"topPeopleCnt2": 22,
	"topPeopleCnt3": 36,
	"topPeopleCnt4": 24,
	"topPeopleCnt5": 25,
	"topPeopleCnt6": 26,
	"topPeopleCnt7": 36,
	"topPeopleCnt8": 34,
	"topPeopleCnt9": 18,
	"topPeopleCnt10": 4
}';
        $test10 = '{
	"timestamp": 1399060970,
	"date": "5/2/2014",
	"time": "13:2:50",
	"words": 242,
	"sentences": 18,
	"negative": 0.50,
	"positive": 0.50,
	"thinking": 0.51,
	"feeling": 0.49,
	"fantasy": 0.47,
	"reality": 0.53,
	"passive": 0.29,
	"proactive": 0.71,
	"disconnected": 0.53,
	"connected": 0.47,
	"happy": 0.68,
	"anxious": 0.00,
	"sad": 0.16,
	"angry": 0.16,
	"love": 0.07,
	"sex": 0.07,
	"family": 0.03,
	"ambition": 0.00,
	"leisure": 0.03,
	"religion": 0.00
	"health": 0.50,
	"work": 0.20,
	"money": 0.07,
	"school": 0.00,
	"home": 0.03,
	"death": 0.00,
	"self": 0.00,
	"food": 0.00,
	"fitness": 0.00,
	"topWords1": "i",
	"topWords2": "the",
	"topWords3": "to",
	"topWords4": "s",
	"topWords5": "that",
	"topWords6": "he",
	"topWords7": "but",
	"topWords8": "have",
	"topWords9": "jillian",
	"topWords10": "guys",
	"topPeople1": "Alice",
	"topPeople2": "Bob",
	"topPeople3": "Bill",
	"topPeople4": "Jack",
	"topPeople5": "Jill",
	"topPeople6": "Muffy",
	"topPeople7": "Buffy",
	"topPeople8": "Tim",
	"topPeople9": "Al",
	"topPeople10": "Jimbo",
	"topWordsCnt1": 10,
	"topWordsCnt2": 10,
	"topWordsCnt3": 7,
	"topWordsCnt4": 6,
	"topWordsCnt5": 6,
	"topWordsCnt6": 6,
	"topWordsCnt7": 4,
	"topWordsCnt8": 4,
	"topWordsCnt9": 4,
	"topWordsCnt10": 4,
	"topPeopleCnt1": 22,
	"topPeopleCnt2": 23,
	"topPeopleCnt3": 23,
	"topPeopleCnt4": 20,
	"topPeopleCnt5": 8,
	"topPeopleCnt6": 2,
	"topPeopleCnt7": 28,
	"topPeopleCnt8": 23,
	"topPeopleCnt9": 32,
	"topPeopleCnt10": 21
}';
        $this->createAEResponse($test1);
        $this->createAEResponse($test2);
        $this->createAEResponse($test3);
        $this->createAEResponse($test4);
        $this->createAEResponse($test5);
        $this->createAEResponse($test6);
        $this->createAEResponse($test7);
        $this->createAEResponse($test8);
        $this->createAEResponse($test9);
        $this->createAEResponse($test10);
        //$this->createAEResponse();

        $this->layout = 'arqLayout2';
        $this->render('test');
    }

    private function getMyQuestions()
    {
        $user_id = Yii::app()->user->id;
        if (!$user_id) return null;
        $model = Question::model()->findAll('t.user_id=:_uid and t.is_active=1', array(':_uid' => $user_id));

        $myQuestions = array();
        foreach ($model as $m) {
            $question = Question::unroll($m);
            $answers = array();
            $myAnswer = null;
            foreach ($m->answers as $answer) {
                $a = array(
                    'answer_id' => $answer->answer_id,
                    'user_answer' => $answer->is_private ? null : $answer->user_answer,
                    'date_created' => $answer->date_created,
                    'question_choice_id' => $answer->question_choice_id,
                    'quantitative_value' => $answer->quantitative_value,
                );
                array_push($answers, $a);
                if ($answer->user_id == $user_id) $myAnswer = $a;
            }
            array_push($myQuestions, array(
                'question' => $question,
                'answers' => $answers,
                'myAnswer' => $myAnswer
            ));

        }

        return $myQuestions;
    }

    private function encodeAEContent($content)
    {
        if (!$content) return '';
        //$return_content = urlencode($content);
        $contentArr = preg_split('/\s+/', $content);
        $return_content = '';
        foreach ($contentArr as $word) {
            if (preg_match('/^\+/', $word)) {
                foreach ($this->AEEncodings as $key => $value) {
                    $word = str_ireplace($key, $value, $word);
                }
            }
            $return_content = $return_content . ' ' . $word;
        }
        return urlencode($return_content);
    }

    private function decodeAEResponse($content)
    {
        if (!$content) return '';
        //$return_content = urldecode($content);
        $return_content = $content;
        foreach ($this->AEEncodings as $key => $value) {
            $return_content = str_ireplace($value, $key, $return_content);
        }
        return $return_content;
    }

    private function getMyAnsweredQuestions()
    {
        $user_id = Yii::app()->user->id;
        if (!$user_id) return null;
        $model = Question::model()->with(array(
            'answers' => array('condition' => "answers.user_id=" . $user_id . " and answers.is_active=1")))->findAll();

        $answeredQuestions = array();
        foreach ($model as $answeredQuestion) {
            $question = Question::unroll($answeredQuestion);
            $answers = array();
            $myAnswer = null;
            foreach ($answeredQuestion->answers as $answer) {
                $a = array(
                    'answer_id' => $answer->answer_id,
                    'user_answer' => $answer->is_private ? null : $answer->user_answer,
                    'date_created' => $answer->date_created,
                    'question_choice_id' => $answer->question_choice_id,
                    'quantitative_value' => $answer->quantitative_value,
                );
                array_push($answers, $a);
                if ($answer->user_id == $user_id) $myAnswer = $a;
            }
            array_push($answeredQuestions, array(
                'question' => $question,
                'answers' => $answers,
                'myAnswer' => $myAnswer
            ));

        }
        return $answeredQuestions;
    }

    private function getQuestionFlags()
    {
        $flags = QuestionFlagType::model()->findAll();
        $myFlags = array();
        foreach ($flags as $flag) {
            array_push($myFlags, array('question_flag_type_id' => $flag->question_flag_type_id, 'name' => $flag->name));
        }
        return $myFlags;
    }

    private function getQuestionCategories()
    {
        $catModel = QuestionCategory::model()->findAll();
        $categories = array();
        foreach ($catModel as $c) {
            array_push($categories, array('question_category_id' => $c->question_category_id, 'name' => $c->name));
        }
        return $categories;
    }

    private function getQuestionTypes()
    {
        $model = QuestionType::model()->findAll();
        $types = array();
        foreach ($model as $c) {
            array_push($types, array('question_type_id' => $c->question_type_id, 'name' => $c->name));
        }
        return $types;
    }

    private function getQuestionStatuses()
    {
        $model = QuestionStatus::model()->findAll();
        $types = array();
        foreach ($model as $c) {
            array_push($types, array('question_status_id' => $c->question_status_id, 'name' => $c->name));
        }
        return $types;
    }

    /*
	 * Generate a random question, but not from on of the categories in the
	 * specified excluded list
	 */
    private function getRandomQuestionByIncludedCategories($excluded)
    {
        $categories = Category::model()->findAll($condition);
    }

    /*
	 * Random question from a category AND not answered by this user
	 */
    private function getRandomQuestionByCategory($category)
    {
        if (!$category) return null;
        $user_id = Yii::app()->user->id;
        $_questions = Question::model()->with(array(
            'questionStatus' => array('condition' => "name='Approved'")
        ))->findAll('t.question_category_id=:_id', array(':_id' => $category{'question_category_id'}));

        if (count($_questions) <= 0) {
            return 1;
        }
        $questions = array();
        foreach ($_questions as $question) {
            if ($question->answers) {
                $answered = 0;
                $answers = $question->answers;
                foreach ($answers as $answer) {
                    if ($answer->user_id == $user_id) {
                        $answered = 1;
                        break;
                    }
                }
                if ($answered <= 0) array_push($questions, $question);
            } else {
                array_push($questions, $question);
            }
        }

        $questionCount = count($questions);
        if ($questionCount > 0) {
            $randomInt = rand(0, count($questions) - 1);
            $question = $questions{$randomInt};
            $myQuestion = Question::unroll($question);
            return $myQuestion;
        } else return null;
    }


    private function formatCappingDate($myDate)
    {
        if (!$myDate) {
            $formattedDate = Date('D M d Y');
            return $formattedDate;
        }
        $formattedDate = date('D M d Y', strtotime($myDate));
        return $formattedDate;
    }

    private function _getDashboardData($duration=90,$to_dateStr='Y-m-d') {
    	$user_id = Yii::app()->user->id;
    	//if (!$user_id) return array();
    	
    	$to_date = date($to_dateStr);//date($to_dateStr);
        $day = 24 * 3600;
        $from_date = date('Y-m-d', strtotime($to_date) - $duration * $day);
        $_dates = $this->getLastXDays($duration,"'".$to_date."'");
        
        $responses = $this->_getDashboardResponses($duration, $to_date,$_dates,$user_id);
        
        $trackerInfo = $this->_trackerData($from_date, $to_date, $user_id);
        
        $all_dates = array_merge($trackerInfo{'trackerDates'});
       
        foreach ($responses{'avg'} as $r) {
        	if (!in_array($r{'date'}, $all_dates)) array_push($all_dates, $r{'date'});
        }
        sort($all_dates);
     	
        //Intervleave tracker data and AE response data
        $eventData = array();
        foreach ($all_dates as $eventDate) {
        	foreach ($responses{'avg'} as $r) {
        		if (strcmp($r{'date'}, $eventDate) == 0) {
        			array_push($eventData, $r);
        			break;
        		}
        	}

        }
        return array(
            'eventData' => $eventData,
            'trackerData' => $trackerInfo{'trackerData'},
            'dates'=>$_dates
        );
    }
    /*
	 * Get dashboard data - top words, top categories, etc. for the specified amount of days
	 * Mix in tracker data
	 * Then determine the set of dates that either a tracker data event occurs on or a AE
	 * response occurs, and fill in an array of AE responses with empty entries for those dates
	 * in which a tracker event occurs, but not an AE response
	 * 
	 * So if the user has AE responses on Dates d1, d2, and d3
	 * and tracker data on dates d0, d4, d5, and d6, we generate an AE response array:
	 * 
	 * array(
	 * d0=>empty array,
	 * d1=>data,
	 * d2=>data,
	 * d3=>data,
	 * d4=>empty array,
	 * d5=>empty array,
	 * d6=>empty array
	 */
    private function getDashboardData(/*$duration, $from_date, $to_date, */$dateObj,$user_id)
    {

        if (!$user_id) return array();
        /*
        $type = 'activity';
        if (array_key_exists('type',$dateObj)) {
        	$type = $dateObj['type'];
        }
        if (strcmp('activity',$type) == 0) {
        	
        } else {
	        if (!$duration) $duration = 30;
	        if (!$to_date) $to_date = date('Y-m-d');
	        if (!$from_date) {
	            $day = 24 * 3600;
	            $from_date = date('Y-m-d', strtotime($to_date) - $duration * $day);
	        }
        }
        */
        $_dates = AeResponse::getResponseDate($dateObj/*$duration, $to_date*/, $user_id);
		
        $responses = $this->getDashboardResponses($dateObj/*$duration, $to_date*/, $user_id);
        
        $trackerInfo = $this->trackerData($dateObj/*$from_date, $to_date*/, $user_id);
        
        $all_dates = array_merge($trackerInfo{'trackerDates'});
		
        foreach ($responses{'avg'} as $r) {
            if (!in_array($r{'date'}, $all_dates)) array_push($all_dates, $r{'date'});
        }
        sort($all_dates);

        //Intervleave tracker data and AE response data
        $eventData = array();
        foreach ($all_dates as $eventDate) {
            if (in_array($eventDate, $_dates)) {
                //array_push($eventData,$responses{'avg'});
                foreach ($responses{'avg'} as $r) {
                    if (strcmp($r{'date'}, $eventDate) == 0) {
                        array_push($eventData, $r);
                        break;
                    }
                }
            } else {
                array_push($eventData, array(
                    'top_categories' => array(),
                    'top_words' => array(),
                    'top_people' => array(),
                    'date' => $eventDate
                ));
            }
        }
        return array(
            'eventData' => $eventData,
            'trackerData' => $trackerInfo{'trackerData'}
        );
    }

    /**
     * Calculate power bar
     * It is a running daily average over five days of the number of words entered in the journal
     * @param unknown $user_id
     * @return multitype:
     */
    public function powerBarCalculation($user_id) {
    	if (!$user_id || $user_id<=0) return array();
    	$sum = 0;
		$words = $this->getDailyWordCount(5,$user_id);
		/*
		$words['2015-05-31']  = 10;
		$words['2015-06-01']  = 0;
		$words['2015-06-02']  = 0;
		$words['2015-06-03']  = 0;
		$words['2015-06-04']  = 200;
		MyStuff::Log("AEJournalDaily");MyStuff::Log($words);
		*/
		$percentages = $this->calcPowerBarPercentages($words);
		if (0.35 * $percentages[0]       > 7)   $sum =   7; else $sum  = 0.35 * $percentages[0];
		if (0.55 * $percentages[1] + $sum > 18)  $sum =  18; else $sum += 0.55 * $percentages[1];
		if (0.85 * $percentages[2] + $sum > 35)  $sum =  35; else $sum += 0.85 * $percentages[2];
		if (1.30 * $percentages[3] + $sum > 61)  $sum =  61; else $sum += 1.30 * $percentages[3];
		if (1.95 * $percentages[4] + $sum > 100) $sum = 100; else $sum += 1.95 * $percentages[4];
		
		return $sum;
    }
    
    /**
     * The daily percentages based on word count
     * @param unknown $words
     * @return multitype:number
     */
    private function calcPowerBarPercentages($words) {
    	$percentages = array();
    	$i = 0;
    	foreach ($words as $key => $value) {
    		$percentages[$i] = 100.0 * $value/$this->powerbarMax/$this->powerbarDays;
 			$i++;
    	}
    	
    	//MyStuff::Log("%");MyStuff::Log($percentages);
    	return $percentages;
    }
    
    /**
     * The daily word count on journal entries
     * @param unknown $duration
     * @param unknown $user_id
     * @return multitype:number NULL
     */
    private function getDailyWordCount($duration,$user_id) {
    	if (!$duration) $duration = 5;
    	$words = array();
    	$days = $this->getLastXDays($duration);
    	foreach ($days as $day) {
    		$words[$day['Date']] = 0;
    		//array_push($words,0);
    	}
    	 
    	$myCondition = "date(t.date_created)>date_sub(curdate(),interval 5 day) ".
    			"and date(t.date_created)<=curdate() and user_id=".$user_id;
    	$criteria = new CDbCriteria;
    	$criteria->condition = $myCondition;
    	//$criteria->params = array(':_user_id' => $user_id);
    	$ae_journal_daily = AeJournalDaily::model()->findAll($criteria);
    	
    	foreach ($ae_journal_daily as $entry) {
    		$ae_response = AeResponse::model()->findbyPk($entry->ae_response_id);
    		$words[$entry->date_created] = $ae_response->words;
    		//array_push($words,array('date'=>$entry->date_created,'words'=>0));
    	}
    	
    	return $words;
    }
    
    /*
     * a la http://stackoverflow.com/questions/2157282/generate-days-from-date-range?answertab=votes#tab-top
     */
    private function getLastXDays($duration=5,$atDateStr="curdate()") {
    	//if (!$duration) $duration = 5;
    	
    	$myQuery = "select a.Date
		from (select $atDateStr - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY as Date
    	from (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as a
    	cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as b
    	cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as c
		) a
		where a.Date between date_sub($atDateStr,interval :duration day) and date($atDateStr)
		order by a.Date ASC";
    	$myDates = Yii::app()->db->createCommand($myQuery)->bindVAlues(array(':duration'=>$duration-1))->queryAll();
    	
    	return $myDates;
    }
    
    private function recentActivities($from_date, $to_date)
    {
        if (!$from_date) {
            $from_date = date('Y-m-d');
        }
        if (!$to_date) {
            $day = 24 * 3600;
            $to_date = date('Y-m-d', date('Y-m-d', strtotime($from_date) - $day));
        }

        $journals = Note::recentJournals($from_date, $to_date);
        return array('journals' => $journals);
    }

    private function _trackerData($start_date,$end_date,$user_id) {
    	$tracker_dates = array();
    	$dates = array();
    	$myCurrent = $start_date;
    	while (strcmp($myCurrent, $end_date) <= 0) {
    		$dates{$myCurrent} = 1;
    		$myCurrent = date('Y-m-d', strtotime('+1 day', strtotime($myCurrent)));
    	}
    	
    	$bar = array('_no_input_', 'boolean');
    	$line = array('scale_1_to_10', 'quantity', 'weight', 'height', 'minutes', 'distance');
    	
    	//$quantifiable = array('_no_input_', 'boolean', 'scale_1_to_10', 'quantity', 'weight', 'height', 'minutes', 'distance');
    	$quantifiable = array('_no_input_', 'boolean', 'scale_1_to_10', 'quantity', 'weight', 'height', 'minutes', 'distance','time');
    	$condition = '';
    	foreach ($quantifiable as $qty) {
    		$condition = $condition . " parameter = '" . $qty . "' or";
    	}
    	
    	$condition = preg_replace('/or$/', '', $condition);
    	
    	$tracker = array('cappable_events' => array(), 'non_cappable_events' => array());
    	/* All calendar events within the specified date range */
    	$calendarEvents = CalendarEvent::model()->findAll('t.user_id=:_user_id and date(t.start_date)<=date(:end_date) and date(t.start_date)>=date(:start_date)', array(':_user_id' => $user_id, ':start_date' => $start_date, ':end_date' => $end_date));
    	foreach ($calendarEvents as $ce) {
    		/* The corresponding event values for each calendar event */
    		$eventValues = $eventValues = EventValue::model()->with('calendarEvent')->findAll('t.calendar_event_id=:_id', array(':_id' => $ce->calendar_event_id));
    	
    		foreach ($eventValues as $ev) {
    			
    			/* Event definitions that correspond to the event values and that are quantifiable */
    			$eventDefn = EventDefinition::model()->with('eventSubcategory')->find("t.event_definition_id=:_id and ( " . $condition . ")", array(':_id' => $ev->event_definition_id));
    			if ($eventDefn) {
    				$subcategory = EventSubcategory::model()->findByPk($eventDefn->event_subcategory_id);
    				if (!$subcategory->cap_event) {
    					/*
    					 * Where to hash results:
    					* if it is an event that cannot be finished, it is non-cappable
    					* if it is an event that can end, like a vacation or job, it is cappable
    					*/
    					$hash = &$tracker{'non_cappable_events'};
    					if ($subcategory->capping_subcategory_id) {
    						$hash = &$tracker{'cappable_events'};
    					}
    	
    					$cap_date = '';
    					if (!array_key_exists($subcategory->name, $hash)) {
    						$hash{$subcategory->name} = array();
    	
    						if ($subcategory->capping_subcategory_id) {
    							if ($ev->capped_event_value_id) {
    								$capping_event_value = EventValue::model()->findByAttributes(array('event_value_id' => $ev->capped_event_value_id));
    								if ($capping_event_value) {
    									$capping_calendar_event = CalendarEvent::model()->findByPk($capping_event_value->calendar_event_id);
    									if ($capping_calendar_event) {
    										$cap_date = date('Y-m-d', strtotime($capping_calendar_event->start_date));
    									}
    								}
    							}
    						}
    					}
    	
    					$event_date = date('Y-m-d', strtotime($ce->start_date));
    					if (!in_array($event_date, $tracker_dates)) array_push($tracker_dates, $event_date);
    					if (in_array($eventDefn->parameter, $bar)) {
    						$hash{$subcategory->name}{$event_date} = 1;
    					} else {
    						$hash{$subcategory->name}{$event_date} = str_replace($eventDefn->label . ' ', '', $ev->value);
    					}
    	
    					/* Cap cappable events */
    					foreach ($dates as $key => $d) {
    						if (!array_key_exists($key, $hash{$subcategory->name})) {
    							if ($cap_date) {
    								if (strcmp($key, $event_date) >= 0 && strcmp($key, $cap_date) <= 0) {
    									$hash{$subcategory->name}{$key} = 1;
    								}
    	
    							} else if ($subcategory->capping_subcategory_id) {
    								//The event is still opened up to and including, possibly, today's date
    								if (strcmp($key, $event_date) >= 0) $hash{$subcategory->name}{$key} = 1;
    	
    							}
    							//else {$hash{$subcategory->name}{$key} = 0;}
    						}
    					}
    				}
    			}
    		}
    	}
    	return array('trackerData' => $tracker, 'trackerDates' => $tracker_dates);    	
    }
    
    private function trackerData($dateObj/*$start_date, $end_date*/, $user_id)
    {
        $tracker_dates = array();
        $dates = array();
        
        $start_date = null;
        $end_date = null;
        if (array_key_exists('from_date',$dateObj)) {
        	if (array_key_exists('duration',$dateObj)) $duration = $dateObj['duration'];
        	else $duration = 30;
        	$start_date = $dateObj['from_date'];
        	$myCurrent = $start_date;
        	while (strcmp($myCurrent, $end_date) <= 0) {
        		$dates{$myCurrent} = 1;
        		$myCurrent = date('Y-m-d', strtotime('+1 day', strtotime($myCurrent)));
        	}
        } else {
        	$myCurrent = $dateObj['minDate'];
        	$end_date = date('Y-m-d');
        	while(strcmp($myCurrent, $end_date) <= 0) {
        		$dates{$myCurrent} = 1;
        		$myCurrent = date('Y-m-d', strtotime('+1 day', strtotime($myCurrent)));
        	}
        }


        $bar = array('_no_input_', 'boolean');
        $line = array('scale_1_to_10', 'quantity', 'weight', 'height', 'minutes', 'distance');

        $quantifiable = array('_no_input_', 'boolean', 'scale_1_to_10', 'quantity', 'weight', 'height', 'minutes', 'distance','time');

        $condition = '';
        foreach ($quantifiable as $qty) {
            $condition = $condition . " parameter = '" . $qty . "' or";
        }

        $condition = preg_replace('/or$/', '', $condition);

        $tracker = array('cappable_events' => array(), 'non_cappable_events' => array());
        /* All calendar events within the specified date range */
        if ($start_date) {
        	$calendarEvents = CalendarEvent::model()->findAll('t.user_id=:_user_id and date(t.start_date)<=date(:end_date) and date(t.start_date)>=date(:start_date)', array(':_user_id' => $user_id, ':start_date' => $start_date, ':end_date' => $end_date));
        } else {
	        $criteria = new CDbCriteria;
	        $criteria->condition = 't.user_id='.$user_id;
	        //$criteria->params = array(':_user_id' => $user_id);
	        $criteria->order='date(start_date) DESC';
	        $criteria->limit=30;
	        $calendarEvents = CalendarEvent::model()->findAll($criteria);
        }
        foreach ($calendarEvents as $ce) {
            /* The corresponding event values for each calendar event */
            $eventValues = $eventValues = EventValue::model()->with('calendarEvent')->findAll('t.calendar_event_id=:_id', array(':_id' => $ce->calendar_event_id));

            foreach ($eventValues as $ev) {
                /* Event definitions that correspond to the event values and that are quantifiable */
                $eventDefn = EventDefinition::model()->with('eventSubcategory')->find("t.event_definition_id=:_id and ( " . $condition . ")", array(':_id' => $ev->event_definition_id));
                if ($eventDefn) {
                    $subcategory = EventSubcategory::model()->findByPk($eventDefn->event_subcategory_id);
                    if (!$subcategory->cap_event) {
                        /* 
						 * Where to hash results:
						 * if it is an event that cannot be finished, it is non-cappable
						 * if it is an event that can end, like a vacation or job, it is cappable
						 */
                        $hash = &$tracker{'non_cappable_events'};
                        if ($subcategory->capping_subcategory_id) {
                            $hash = &$tracker{'cappable_events'};
                        }

                        $cap_date = '';
                        if (!array_key_exists($subcategory->name, $hash)) {
                            $hash{$subcategory->name} = array();

                            if ($subcategory->capping_subcategory_id) {
                                if ($ev->capped_event_value_id) {
                                    $capping_event_value = EventValue::model()->findByAttributes(array('event_value_id' => $ev->capped_event_value_id));
                                    if ($capping_event_value) {
                                        $capping_calendar_event = CalendarEvent::model()->findByPk($capping_event_value->calendar_event_id);
                                        if ($capping_calendar_event) {
                                            $cap_date = date('Y-m-d', strtotime($capping_calendar_event->start_date));
                                        }
                                    }
                                }
                            }
                        }

                        $event_date = date('Y-m-d', strtotime($ce->start_date));
                        if (!in_array($event_date, $tracker_dates)) array_push($tracker_dates, $event_date);
                        if (in_array($eventDefn->parameter, $bar)) {
                            $hash{$subcategory->name}{$event_date} = 1;
                        } else {
                            $hash{$subcategory->name}{$event_date} = str_replace($eventDefn->label . ' ', '', $ev->value);
                        }

                        /* Cap cappable events */
                        foreach ($dates as $key => $d) {
                            if (!array_key_exists($key, $hash{$subcategory->name})) {
                                if ($cap_date) {
                                    if (strcmp($key, $event_date) >= 0 && strcmp($key, $cap_date) <= 0) {
                                        $hash{$subcategory->name}{$key} = 1;
                                    }

                                } else if ($subcategory->capping_subcategory_id) {
                                    //The event is still opened up to and including, possibly, today's date
                                    if (strcmp($key, $event_date) >= 0) $hash{$subcategory->name}{$key} = 1;

                                }
                                //else {$hash{$subcategory->name}{$key} = 0;}
                            }
                        }
                    }
                }
            }
        }
        return array('trackerData' => $tracker, 'trackerDates' => $tracker_dates);
    }

    private function calendarActivities($start_date, $end_date, $user_id)
    {

        #MyStuff::Log('CALENAER ACTIVITIES '.$start_date.' '.$end_date);
        $myEvents = array();
        $calendarEvents = CalendarEvent::model()->findAll(array('order' => 't.start_date DESC', 'condition' => 't.user_id=:_user_id and date(t.start_date)<=date(:end_date) and date(t.start_date)>=date(:start_date)', 'params' => array(':_user_id' => $user_id, ':start_date' => $start_date, ':end_date' => $end_date)));
        //var_dump($calendarEvents);exit;
		foreach ($calendarEvents as $ce) {
            $eventValues = $eventValues = EventValue::model()->with('calendarEvent', 'eventNote', 'eventQuestion')->findAll('t.calendar_event_id=:_id', array(':_id' => $ce->calendar_event_id));
            $description = array();
	    
            foreach ($eventValues as $ev) {
                $eventDefn = EventDefinition::model()->with('eventSubcategory')->find('t.event_definition_id=:_id', array(':_id' => $ev->event_definition_id));
                $subcategory = EventSubcategory::model()->findByPk($eventDefn->event_subcategory_id);
                if (isset($subcategory)) {
                    $title = $subcategory->name;
                    $d = array(
                        'id' => $ev->event_value_id,
                        'value' => $ev->value,
                        'type' => $eventDefn->parameter
                    );
                    if ($ev->eventNote) {
                        $d['note_id'] = $ev->eventNote->note_id;
                		$d['fb_image_ids'] = $ev->eventNote->Note->fb_image_ids;
                		if ($ev->eventNote->Note->fb_image_ids) {
	                		$imageIDs = explode(',',$ev->eventNote->Note->fb_image_ids);
	                		if (count($imageIDs)>0) {
	                			
	                			$pathArr = array();
	                			foreach ($imageIDs as $imageID) {
	                				$image = Image::model()->findByPk($imageID);
	                				array_push($pathArr,$image->path);
	                			}
	                			$d['images'] = $pathArr;
	                		}
                		} 
                		if ($ev->eventNote->Note->fb_video_ids) {
                			$video = Video::model()->findByPk($ev->eventNote->Note->fb_video_ids);
                			$d['video'] = $video->path;
                			$d['video_h'] = $video->height;
                			$d['video_w'] = $video->width;
                		}
                		$d['fb_video_ids'] = $ev->eventNote->Note->fb_video_ids;
                    } else if ($ev->eventQuestion) {
                        $d['question_id'] = $ev->eventQuestion->question_id;
                        $d['question_type'] = $ev->eventQuestion->type;
                    }
                    array_push($description, $d);
                }
            }
            if (isset($subcategory)) {
                array_push($myEvents, array(
                    'date_created' => $ce->date_created,
                    'calendar_event' => $ce->calendar_event_id,
                    'subcategory' => $subcategory->name,
                    'title' => $title,
                    'start' => $ce->start_date,
                    'end' => $ce->end_date,
                    'allDay' => intval($ce->all_day),
                    'description' => $description,
                    'editable' => false
                ));
            }
	    
        }
	
        return $myEvents;
    }

    private function _getDashboardResponses($duration,$from_date,$_dates,$user_id) {
    	if (!$user_id) return null;
    	$avg = $this->_mean_score_per_day($duration, $from_date);
    	$avgResponses = array();
    	$targetArr = $_dates;
    	if (count($avg)<count($_dates)) {
    		$targetArr = array();
    		$index = 0;
    		foreach ($avg as $key => $avgValue) {
    			$targetArr[$index] = $key;
    			$index++;
    		}
    	}
    	$sliderCount = count($targetArr);
    	$increments = 0;
    	
    	if ($sliderCount > 0) $increments = intVal(1000 / $sliderCount);
    	/* Sort dates in ascending order */
    	$myDates = array();
    	foreach ($targetArr as $key => $dateValue) {
    		$myDates[$key] = $dateValue;
    	}
    	array_multisort($myDates, SORT_ASC, $targetArr);
    	/* And then generate response hash */
    	$index = 0;
    	foreach ($targetArr as $entry) {
    		$date = $entry['Date'];
    		$avgResponses[/*$increments**/
    		($index)] = $avg[$date];
    		$avgResponses[/*$increments**/
    		($index)]{'date'} = $date;
    		$index++;
    	}
    	$responses = array('avg' => $avgResponses, 'responseCount' => $sliderCount);
    	
    	return $responses;
    }

    private function getDashboardResponses($dateObj/*$duration, $from_date*/, $user_id)
    {
        if (!$user_id) return null;

        $_dates = AeResponse::getResponseDate($dateObj/*$duration, $from_date*/, $user_id);
		
        /*
		$_dates = array();
		//while( strcmp($myCurrent,$end_date) <= 0) {
		$myCurrent = $from_date;//date('Y-m-d');
		for ($i = 1;$i<=$duration;$i++) {
			array_push($_dates,$myCurrent);
			$myCurrent = date('Y-m-d',strtotime('-1 day',strtotime($myCurrent) ) );
		}
		*/
        $avg = $this->mean_score_per_day($dateObj/*$duration, $from_date*/);
		
        $avgResponses = array();
        $targetArr = $_dates;
        if (count($avg)<count($_dates)) {
        	$targetArr = array();
        	$index = 0;
        	foreach ($avg as $key => $avgValue) {
        		$targetArr[$index] = $key;
        		$index++;
        	}
        }
        $sliderCount = count($targetArr);
        $increments = 0;
        
        if ($sliderCount > 0) $increments = intVal(1000 / $sliderCount);
        /* Sort dates in ascending order */
        $myDates = array();
        foreach ($targetArr as $key => $dateValue) {   	
            $myDates[$key] = $dateValue;
        }

        array_multisort($myDates, SORT_ASC, $targetArr);
        /* And then generate response hash */
        $index = 0;
		
        foreach ($targetArr as $date) {
            $avgResponses[/*$increments**/
            ($index)] = $avg[$date];
            $avgResponses[/*$increments**/
            ($index)]{'date'} = $date;
            $index++;
        }

        $responses = array('avg' => $avgResponses, 'responseCount' => $sliderCount);
 
        return $responses;
    }

    private function getRandomQuestion()
    {
        // This is a much more optimized version of 'order by rand()'
        $min = Yii::app()->db->createCommand('select min(question_id) min_id from question')->queryRow();
        $max = Yii::app()->db->createCommand('select max(question_id) max_id from question')->queryRow();
        $question_id = rand($min['min_id'], $max['max_id']);
        $user_id = Yii::app()->user->id;

        $sql = "select q.question_id
		from question q
		left join answer a on q.question_id = a.question_id
		  and a.user_id <> $user_id
		where q.question_id >= $question_id
		  and a.answer_id is null
		limit 1;";
        $get_question = Yii::app()->db->createCommand($sql)->queryRow();
        if (!$get_question) {
            $sql = "select q.question_id
			from question q
			left join answer a on q.question_id = a.question_id
			and a.user_id <> $user_id
			where q.question_id <= $question_id
			and a.answer_id is null
			limit 1;";
            $get_question = Yii::app()->db->createCommand($sql)->queryRow();
            if (!$get_question) {
                return false;
            }
        }
        $question = Question::model()->findByAttributes(array('question_id' => $question_id));
        //$question = Question::model()->findByAttributes(array('question_id'=>$get_question['question_id']));
        return $question;

    }

    /**
     * A stub for creating milesonte models for the calendar UI
     */
    private function getMilestoneEvents()
    {
        $milestones = array(
            'Born' => array(array('label' => 'Born In', 'name' => 'born_where', 'type' => 'interrogative')),
            'Death' => array(array('label' => 'Who Died', 'name' => 'died_who', 'type' => 'interrogative')),
            'Graduated' => array(array('label' => 'Graduated From', 'name' => 'graduated_from', 'type' => 'interrogative'))
        );

        return $milestones;
    }

    /**
     * This might eventually go into a model somewhere, or become a model object itself
     */
    private function getCalendarEventData()
    {
        $events = array(
            'Sleep' => array(
                'Time Woke Up' => array(array('label' => 'Woke Up At', 'name' => 'time_woke_up', 'type' => 'time')),
                'Time To Bed' => array(array('label' => 'Went To Bed At', 'name' => 'time_to_bed', 'type' => 'time')),
                'Slept' => array(array('label' => 'Slept For', 'name' => 'slept', 'type' => 'qty', 'unit' => array('min', 'hrs'))),
                'Napped' => array(array('label' => 'Napped For', 'name' => 'napped', 'type' => 'qty', 'unit' => array('min', 'hrs')))
            ),
            'Productivity' => array(
                'Studied' => array(array('label' => 'Studied For', 'name' => 'studied', 'type' => 'qty', 'unit' => array('min', 'hrs'))),
                'Worked' => array(array('label' => 'Worked For', 'name' => 'worked', 'type' => 'qty', 'unit' => array('min', 'hrs'))),
                'Read' => array(array('label' => 'Read For', 'name' => 'read', 'type' => 'qty', 'unit' => array('min', 'hrs'))),
                'Pages Read' => array(array('label' => 'Read', 'name' => 'read_pages', 'type' => 'qty', 'unit' => array('pages'))),
                'Pages Wrote' => array(array('label' => 'Wrote', 'name' => 'wrote_pages', 'type' => 'qty', 'unit' => array('pages'))),
            ),/*
			'Fitness'=>array(),
			'Diet'=>array(),*/
            'Relationship' => array(
                'Fight' => array(
                    0 => array('label' => 'Had a Fight With', 'name' => 'fight', 'type' => 'interrogative')
                ),
                'Date' => array(
                    array('label' => 'Went On a Date With', 'name' => 'date_with', 'type' => 'interrogative'),
                    array('label' => 'We Went To', 'name' => 'date_where', 'type' => 'interrogative')
                ),
                'Broke Up' => array(
                    array('label' => 'Broke Up?', 'name' => 'broke_up', 'type' => 'radio', 'values' => array('yes', 'no')),
                    array('label' => 'Broke Up With', 'name' => 'broke_up_with', 'type' => 'interrogative')

                )
            ),
            'Mood' => array(
                'Mood' => array(array('label' => 'Mood', 'name' => 'mood', 'type' => 'range')),
                'Happiness' => array(array('label' => 'Happiness', 'name' => 'happiness', 'type' => 'range')),
                'Sadness' => array(array('label' => 'Sadness', 'name' => 'sadness', 'type' => 'range')),
                'Stress' => array(array('label' => 'Stress', 'name' => 'sress', 'type' => 'range')),
                'Anger' => array(array('label' => 'Anger', 'name' => 'anger', 'type' => 'range'))
            )/*,
			'Health'=>array(),
			'Lifestyle'=>array()*/
        );
        return $events;
    }

    private function mean_score($days = 30)
    {
        return array(
            'top_categories' => $this->mean_categories($days),
            'top_people' => $this->mean_top_people($days),
            'top_words' => $this->mean_top_words($days),
        );
    }

    private function mean_categories($days)
    {
        $user_id = Yii::app()->user->id;
        $sql = "select c.description category, c.display_name, c.category_type, avg(arc.value) value
from ae_response ar
join ae_response_category arc on ar.ae_response_id = arc.ae_response_id
join category c on arc.category_id = c.category_id
where ar.user_id = $user_id
  and ar.is_active = 1
  and ar.date_created >= date_sub(curdate(), interval $days day)
group by c.description, c.display_name, c.category_type
order by value desc";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    private function mean_top_people($days)
    {
        $user_id = Yii::app()->user->id;
        $sql = "select tp.ae_value person, avg(tp.ae_rank) avg_rank
from ae_response ar
join top_people tp on ar.ae_response_id = tp.ae_response_id
where ar.user_id = $user_id
  and ar.is_active = 1
  and ar.date_created >= date_sub(curdate(), interval $days day)
group by tp.ae_value
order by avg_rank desc";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    private function mean_top_words($days)
    {
        $user_id = Yii::app()->user->id;
        $sql = "select tw.ae_value person, avg(tw.ae_rank) avg_rank
from ae_response ar
join top_words tw on ar.ae_response_id = tw.ae_response_id
where ar.user_id = $user_id
  and ar.is_active = 1
  and ar.date_created >= date_sub(curdate(), interval $days day)
group by tw.ae_value
order by avg_rank desc";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    private function get_mean_score_per_day($dateObj) {
    	if (array_key_exists('from_date',$dateObj) && array_key_exists('days_back',$dateObj)) {
    		return $this->mean_score_per_day($dateObj['days_back'],$dateObj['from_date']);
    	} else {
    		return $this->mean_score_per_day_of_activity($dateObj);
    	}
    }

    private function _mean_score_per_day($days_back=0,$from_date) {
    	$sql = "select report_date
					from date_dim
					where report_date<=date('" . $from_date . "')
	  				and report_date>=date_sub(date('" . $from_date . "'), interval $days_back day)
    		  				order by report_date desc";
    	
    	foreach (Yii::app()->db->createCommand($sql)->queryAll() as $day) {
    	
    	#MyStuff::Log($day);
    		$return[$day['report_date']] = array(
    		'top_categories' => array(),
    		'top_people' => array(),
    		'top_words' => array(),
    		);
    	}
    	
    	foreach ($this->mean_categories_per_day($days_back, $from_date) as $data) {
	    	if ($data['category']) {
	    		$return[$data['date']]['top_categories'][$data['category']] = $data['value'];
	    	}
    	}
    	
    	foreach ($this->mean_top_people_per_day($days_back, $from_date) as $data) {
    		if ($data['person']) {
    			$return[$data['date']]['top_people'][$data['person']] = $data['person_count'];
    		}
    	}
    	
    	foreach ($this->mean_top_words_per_day($days_back, $from_date) as $data) {
    		if ($data['word']) {
    			$return[$data['date']]['top_words'][$data['word']] = $data['word_count'];
    		}
    	}    
    	return $return;
    }
    
    private function mean_score_per_day($dateObj/*$days_back = 0, $from_date*/)
    {
		$from_date = null;
		$days_back = null;
		if (array_key_exists('from_date',$dateObj)) {
			if (array_key_exists('duration',$dateObj)) $days_back = $dateObj['duration'];
			else $days_back = 30;
			$from_date = $dateObj['from_date'];
		}
		
        $return = array();
        
        if (!$from_date) {
        	$return = $this->mean_score_per_day_of_activity($dateObj);
        } else {
	        $sql = "select report_date
					from date_dim
					where report_date<=date('" . $from_date . "')
	  				and report_date>=date_sub(date('" . $from_date . "'), interval $days_back day)
					order by report_date desc";
	 
	        foreach (Yii::app()->db->createCommand($sql)->queryAll() as $day) {
	
	            #MyStuff::Log($day);
	            $return[$day['report_date']] = array(
	                'top_categories' => array(),
	                'top_people' => array(),
	                'top_words' => array(),
	            );
	        }
	
	        foreach ($this->mean_categories_per_day($days_back, $from_date) as $data) {
	            if ($data['category']) {
	                $return[$data['date']]['top_categories'][$data['category']] = $data['value'];
	            }
	        }
	
	        foreach ($this->mean_top_people_per_day($days_back, $from_date) as $data) {
	            if ($data['person']) {
	                $return[$data['date']]['top_people'][$data['person']] = $data['person_count'];
	            }
	        }
	
	        foreach ($this->mean_top_words_per_day($days_back, $from_date) as $data) {
	            if ($data['word']) {
	                $return[$data['date']]['top_words'][$data['word']] = $data['word_count'];
	            }
	        }
        }
		
        return $return;

    }

    private function mean_score_per_day_of_activity($dateObj) {
    	$from_date = date('Y-m-d');
    	$minDate = $dateObj['minDate'];
    	$return = array();
    	$categories = $this->mean_categories_per_day_of_activity($from_date,$minDate);
    	$topwords = $this->mean_top_words_per_day_of_activity($from_date,$minDate);
    	$toppeople = $this->mean_top_people_per_day_of_activity($from_date,$minDate);
    	
    	foreach ($categories as $data) {
    		if ($data['category']) {    			
    			if (!array_key_exists($data['date'],$return) ) {
    				$return[$data['date']] = array(
    						'top_categories' => array(),
    						'top_people' => array(),
    						'top_words' => array(),
    				);
    			}
    			$return[$data['date']]['top_categories'][$data['category']] = $data['value'];
    			
    		}
    	}
    	
    	foreach ($topwords as $data) {
    		if ($data['word']) {
    			if (!array_key_exists($data['date'],$return) ) {
    				$return[$data['date']] = array(
    						'top_categories' => array(),
    						'top_people' => array(),
    						'top_words' => array(),
    				);
    			}
    			$return[$data['date']]['top_words'][$data['word']] = $data['word_count'];
    		}
    	}
    	
    	foreach ($toppeople as $data) {
    		if ($data['person']) {
    			if (!array_key_exists($data['date'],$return) ) {
    				$return[$data['date']] = array(
    						'top_categories' => array(),
    						'top_people' => array(),
    						'top_words' => array(),
    				);
    			}
    			$return[$data['date']]['top_people'][$data['person']] = $data['person_count'];
    		}
    	}    	
    	return $return;
    }
    
    private function mean_categories_per_day_of_activity($from_date,$minDate) {
    	$user_id = Yii::app()->user->id;
    	$sql = "select dd.report_date date,
		res.category,res.display_name,res.category_type,res.value
		from date_dim dd
		left join  (
		select date(ar.date_created) date_created ,
		c.description category,
		c.display_name,
		c.category_type,
		avg(arc.value) value
		from ae_journal_daily ar
		join ae_response_category arc on ar.ae_response_id = arc.ae_response_id
		join category c on arc.category_id = c.category_id
		where ar.user_id = $user_id
		and ar.date_created<=date('$from_date') and ar.date_created>=date('".$minDate."')
		and ar.is_active = 1
		group by date_created,c.description,c.display_name,c.category_type
		order by date_created,value desc
		) res on dd.report_date = res.date_created
		where dd.report_date<=date('$from_date') and dd.report_date>=date('".$minDate."')
		order by dd.report_date desc,res.value desc";
    	return Yii::app()->db->createCommand($sql)->queryAll();
    }
    
    private function mean_categories_per_day($days_back, $from_date)
    {
        $user_id = Yii::app()->user->id;
        $sql = "select dd.report_date date,
  				res.category,
  				res.display_name,
  				res.category_type,
  				res.value
				from date_dim dd
				left join (
  				select date(ar.date_created) date_created,
			    c.description category,
			    c.display_name,
			    c.category_type,
			    avg(arc.value) value
			  from ae_journal_daily ar
			  join ae_response_category arc on ar.ae_response_id = arc.ae_response_id
			  join category c on arc.category_id = c.category_id
			  where ar.user_id = $user_id
			    and ar.is_active = 1
			    and ar.date_created >= date_sub(date('" . $from_date . "'), interval $days_back day)
			  group by date_created, c.description, c.display_name, c.category_type
			  order by date_created, value desc) res on dd.report_date = res.date_created
			where dd.report_date <= date('" . $from_date . "')
			  and dd.report_date >= date_sub(curdate(), interval $days_back day)
			order by dd.report_date desc, res.value desc";

        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    private function mean_top_people_per_day_of_activity($from_date,$minDate) {
    	$user_id = Yii::app()->user->id;
    	$sql = "select dd.report_date date,
    	res.person,
    	sum(res.person_count) as person_count
    	from date_dim dd
    	left join (
    	select date(ar.date_created) date_created, tp.ae_value person, sum(tp.count) person_count
    	from ae_journal_daily ar
    	join top_people tp on ar.ae_response_id = tp.ae_response_id
    	where ar.user_id = $user_id
    	and ar.is_active = 1
    	and ar.date_created<=date('$from_date') and ar.date_created>=date('$minDate')
    	and tp.ae_value <> 'NA'
    	group by date_created , tp.ae_value
    	order by date_created desc, person_count desc) res on dd.report_date = res.date_created
    	where dd.report_date<=date('$from_date') and dd.report_date>=date('$minDate')
    	group by dd.report_date,res.person
    	order by date desc, res.person_count desc";
    	return Yii::app()->db->createCommand($sql)->queryAll();
    }
    
    private function mean_top_people_per_day($days_back, $from_date)
    {
        $user_id = Yii::app()->user->id;
        $sql = "select dd.report_date date,
				  res.person,
				  sum(res.person_count) as person_count
				from date_dim dd
				left join (
				  select date(ar.date_created) date_created, tp.ae_value person, sum(tp.count) person_count
				  from ae_journal_daily ar
				  join top_people tp on ar.ae_response_id = tp.ae_response_id
				  where ar.user_id = $user_id
				    and ar.is_active = 1
				    and ar.date_created >= date_sub(date('" . $from_date . "'), interval $days_back day)
				    and tp.ae_value <> 'NA'
				  group by date_created , tp.ae_value
				  order by date_created desc, person_count desc) res on dd.report_date = res.date_created
				where dd.report_date <= date('" . $from_date . "')
				  and dd.report_date >= date_sub(date('" . $from_date . "'), interval $days_back day)
				  group by dd.report_date,res.person
				order by date desc, res.person_count desc";

        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    private function mean_top_words_per_day_of_activity($from_date,$minDate) {
    	$user_id = Yii::app()->user->id;
    	$sql = "select dd.report_date date,
		res.word,
		sum(res.word_count) as word_count
		from date_dim dd
		left join (
		select date(ar.date_created) date_created, tw.ae_value word, sum(tw.count) word_count
		from ae_journal_daily ar
		join top_words tw on ar.ae_response_id = tw.ae_response_id
		where ar.user_id = $user_id
		and ar.is_active = 1
		and ar.date_created<=date('$from_date') and ar.date_created>=date('$minDate')
		and tw.ae_value <> 'NA'
		group by date_created , tw.ae_value
		order by date_created desc, word_count desc) res on dd.report_date = res.date_created
		where dd.report_date<=date('$from_date') and dd.report_date>=date('$minDate')
		group by dd.report_date,res.word
		order by date desc, res.word_count desc";
    	return Yii::app()->db->createCommand($sql)->queryAll();
    }
    
    private function mean_top_words_per_day($days_back, $from_date)
    {
        $user_id = Yii::app()->user->id;
        $sql = "select dd.report_date date,
				  res.word,
				  sum(res.word_count) as word_count
				from date_dim dd
				left join (
				  select date(ar.date_created) date_created, tw.ae_value word, sum(tw.count) word_count
				  from ae_journal_daily ar
				  join top_words tw on ar.ae_response_id = tw.ae_response_id
				  where ar.user_id = $user_id
				    and ar.is_active = 1
				    and ar.date_created >= date_sub(date('" . $from_date . "'), interval $days_back day)
				    and tw.ae_value <> 'NA'
				  group by date_created , tw.ae_value
				  order by date_created desc, word_count desc) res on dd.report_date = res.date_created
				where dd.report_date <= date('" . $from_date . "')
				  and dd.report_date >= date_sub(date('" . $from_date . "'), interval $days_back day)
				  group by dd.report_date,res.word
				order by date desc, res.word_count desc";

        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    private function getEarliestNoteDate() {
    	$user_id = Yii::app()->user->id;
    	$sql = "select min(date_created) as minDate
				from ae_journal_daily
				where user_id=$user_id";
 		$rows = Yii::app()->db->createCommand($sql)->queryAll();
 		if (!is_null($rows) && count($rows)>0) {
 			$result = $rows[0];
	 		if (is_null($result) || is_null($result['minDate'])) {
	 			return new CDbExpression('NOW()');
	 		} else return $result['minDate'];
 		} else {
 			return new CDbExpression('NOW()');
 		}
    	
    	
    }
    private function deleteAE_Response($ae_response_id, $note)
    {
        if (!$ae_response_id) return;
        $ae_response = AeResponse::model()->findbyPk($ae_response_id);
        if (!$ae_response) return;

        CategoryScore::model()->deleteAllByAttributes(array(
            'ae_response_id' => $ae_response->ae_response_id
        ));
        TopWords::model()->deleteAllByAttributes(array(
            'ae_response_id' => $ae_response->ae_response_id
        ));
        TopPeople::model()->deleteAllByAttributes(array(
            'ae_response_id' => $ae_response->ae_response_id
        ));

        if ($note) {
            $note->ae_response_id = NULL;
            $note->save();
        }
        $ae_response->delete();

    }

    private function getFirstDayOfNextMonth()
    {
        $myD = date_create();
        $month = $myD->format('m') + 1;
        $from_date = date('Y-m-d', strtotime($myD->setDate($myD->format('Y'), $month, 1)->format('Y-m-d')));
        return $from_date;
    }

    public function actionDeleteQuestion()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        header('Content-type: application/json');
        $question_id = Yii::app()->request->getPost('question_id', -1);

        if (!$question_id || $question_id < 0) {
            echo CJSON::encode(array(
                'success' => -1,
                'msg' => 'Entry not found'
            ));
            Yii::app()->end();
        }

        $question = Question::model()->findByPk($question_id);
        if (!$question) {
            echo CJSON::encode(array(
                'success' => -1,
                'msg' => 'Entry not found'
            ));
            Yii::app()->end();
        }

        $question->is_active = 0;
        if ($question->save()) {
            echo CJSON::encode(array(
                'success' => 1,
                'id' => $question->question_id,
            ));
        } else {
            echo CJSON::encode(array(
                'success' => -1,
                'msg' => 'Update not saved',
            ));
        }
        Yii::app()->end();
    }

    public function actionDeleteAnswer()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        header('Content-type: application/json');
        $answer_id = Yii::app()->request->getPost('answer_id', -1);

        if (!$answer_id || $answer_id < 0) {
            echo CJSON::encode(array(
                'success' => -1,
                'msg' => 'Entry not found'
            ));
            Yii::app()->end();
        }

        $answer = Answer::model()->findByPk($answer_id);
        if (!$answer) {
            echo CJSON::encode(array(
                'success' => -1,
                'msg' => 'Entry not found'
            ));
            Yii::app()->end();
        }

        $answer->is_active = 0;
        if ($answer->save()) {
            echo CJSON::encode(array(
                'success' => 1,
                'id' => $answer->answer_id,
            ));
        } else {
            echo CJSON::encode(array(
                'success' => -1,
                'msg' => 'Update not saved',
            ));
        }
        Yii::app()->end();
    }

    public function actionFlagAnswer()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        $answer_id = (int)Yii::app()->request->getPost('answer_id', 0);
        $user_id = Yii::app()->user->Id;
        if ($answer = Answer::model()->findbyPk($answer_id)) {
            $flag = new AnswerFlag();
            $flag->answer_id = $answer->answer_id;
            $flag->user_id = $user_id;
            $flag->date_created = new CDbExpression('NOW()');
            if ($flag->save()) {
                echo CJSON::encode(array(
                    'success' => 1,
                    'id' => $answer_id,
                ));
                Yii::app()->end();
            } else {
                echo CJSON::encode(array(
                    'success' => -1,
                    'msg' => 'Entry not saved'
                ));
                Yii::app()->end();
            }
        } else {
            echo CJSON::encode(array(
                'success' => -1,
                'msg' => 'Entry not found'
            ));
            Yii::app()->end();
        }
    }

    public function actionLikeAnswer()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }
        $answer_id = (int)Yii::app()->request->getPost('answer_id', 0);
        $user_id = Yii::app()->user->Id;
        if ($answer = Answer::model()->findbyPk($answer_id)) {
            $like = new AnswerLike();
            $like->answer_id = $answer->answer_id;
            $like->user_id = $user_id;
            $like->date_created = new CDbExpression('NOW()');
            if ($like->save()) {
                echo CJSON::encode(array(
                    'success' => 1,
                    'id' => $answer_id,
                ));
                Yii::app()->end();
            } else {
                echo CJSON::encode(array(
                    'success' => -1,
                    'msg' => 'Entry not saved'
                ));
                Yii::app()->end();
            }
        } else {
            echo CJSON::encode(array(
                'success' => -1,
                'msg' => 'Entry not found'
            ));
            Yii::app()->end();
        }
    }

    /**
     * Wrapper around YiiMailer for sending out plain-text emails
     * This wrapper does not support attachments
     * @param String $userEmail The email address to send email to
     * @param String $emailView the name of the file in /views/mail/ to use as a template
     * @param array $message defines the variables to fill out in the template
     */
    public function SendMail($userEmail, $emailView, $message)
    {
        $mail = new YiiMailer($emailView, $message);
        $mail->setTo($userEmail);
        $mail->setFrom(Yii::app()->params['adminEmail'], 'ArQNet Administrator');
        $mail->setSubject('Your Password Has Been Reset');
        if ($mail->send()) {
            Yii::app()->user->setFlash('test', 'Thank you for contacting us. We will respond to you as soon as possible.');
        } else {
            Yii::app()->user->setFlash('error', 'Error while sending email: ' . $mail->getError());
        }
    }

    /*
	 * for facebook login pwd
	 */
    private function encrypt($string, $operation, $key = '')
    {
        $key = md5($key);
        $key_length = strlen($key);
        $string = $operation == 'D' ? base64_decode($string) : substr(md5($string . $key), 0, 8) . $string;
        $string_length = strlen($string);
        $rndkey = $box = array();
        $result = '';
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($key[$i % $key_length]);
            $box[$i] = $i;
        }
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if ($operation == 'D') {
            if (substr($result, 0, 8) == substr(md5(substr($result, 8) . $key), 0, 8)) {
                return substr($result, 8);
            } else {
                return '';
            }
        } else {
            return str_replace('=', '', base64_encode($result));
        }
    }
}
