<?php
/*
require_once Yii::app()->basePath . '\Vendor\fb_v321\facebook.php';
require_once Yii::app()->basePath . '\controllers\SiteController.php';
*/
Yii::import('application.Vendor.*');
require_once('fb_v321/facebook.php');
Yii::import('application.controllers.SiteController');

class FBLoginController extends Controller
{
	public $is_bing = null;
	/* daniel
	public $config = array(
		'appId' => '764768786890174',
		'secret' => '5f8840247c7d387036102430e73339d9',
		'cookie' => true,
		'oauth ' => true,
	);
	*/
	/* arqnet */
	public $config = array(
			'appId' => '1593970450870932',
			'secret' => '27d7c56415b50d1b07230fedacd1a5a9',
			'cookie' => true,
			'oauth ' => true,
	);	
	
	/* local 
	public $config = array(
			'appId' => '1625355101065800',
			'secret' => 'a93408d548afc749d21d39f8355d7261',
			'cookie' => true,
			'oauth ' => true,
	);
	*/
	public function actionIndex()
	{
//		$this->layout = 'arqLayout1';
		$this->render('index');

	}
	public function actionTest() {MyStuff::Log('test');}
	/**
	 * //绑定账户 标签
	 */

	public function actionBandingStatus()
	{
		Yii::app()->session['binding_status'] = 1;
		$this->redirect("/FBLogin/connect/");
		exit;
	}

	/**
	 * //只为导入 标签
	 */

	public function actionJustImport()
	{
		Yii::app()->session['for_import'] = 1;
		$this->redirect("/FBLogin/connect/");
		exit;
	}
	

	/**
	 *
	 * 该方法分为绑定fb用户,登录注册fb用户
	 * */
	public function actionConnect()
	{
		MyStuff::Log("ACTION CONNECT");
		$binding = Yii::app()->session['binding_status'];
		if (@$_GET['error_reason']) {
			die("<script type='text/javascript'>top.location.href = 'http://www.facebook.com';</script>");
		}

		$facebook = new Facebook($this->config);
		$login_url = $facebook->getLoginUrl(array('scope' => 'user_videos,user_photos,read_stream,user_birthday'));
		$user_id = $facebook->getUser();

		if ($user_id) {
			try {
				$me = $facebook->api('/me');
				$since = strtotime(date('Y-m-d')) - 30*24*3600;
				/*
                MyStuff::Log("FB ME ");MyStuff::Log($me);
               	MyStuff::Log("SINCE= ".date('Y-m-d',1364849754).' '.$since);
                MyStuff::Log("BINDING ".$binding);
                MyStuff::Log("POSTS? ".$me['id']);
              	MyStuff::Log($facebook->api('/'.$me['id'].'/posts?since='.$since));
                MyStuff::Log("AFTER POSts");
                */
				//如果单纯绑定，则只需要判断是否已绑定，与登录注册无关
				if ($binding == 1 && $me) {

					$is_bound = BindingAccount::model()->findByAttributes(array(
						'third_party_id' => $me['id']
					));

					if ($is_bound) {
						$this->redirect("/settings?error=2");
						exit;
					} else {
						//开始绑定
						$banding_account = new BindingAccount();
						$banding_account->arq_id = Yii::app()->user->Id;
						$banding_account->third_party_id = $me['id'];
						$banding_account->third_party = 'facebook';
						$banding_account->third_part_account = $me['link'];
						$banding_account->date_created = date("y-m-d");

						$banding_account->save();
						//绑定成功 改变session
						if ($banding_account) {
							Yii::app()->session['binding_status'] = 0;
						}
//						//获取当前数据库中最新的时间节点
						$sql = "SELECT max(date_modified) FROM note WHERE user_id=".Yii::app()->user->id." AND fb_message_id is not null";
						
						$get_note = Yii::app()->db->createCommand($sql)->queryRow();
						//todo 暂时测试用
						//$get_note["max(date_modified)"] = null;
						
						$fql = "SELECT post_id,message,updated_time,created_time,attachment FROM stream WHERE source_id = me() AND is_hidden = 0 ";
						if($get_note["max(date_modified)"]) {
							//如果存在， 则获取比当前时间大的数据
							$latest_time = strtotime($get_note["max(date_modified)"]);
							
							$fql.= " AND updated_time > ".$latest_time." ORDER BY created_time DESC LIMIT 1000000";
						} else {
							$fql.= " ORDER BY created_time DESC LIMIT 1000000";
						}
						
						
						
						$param = array('method' => 'fql.query',
								'query' => $fql
								);
						
						$statuse = $facebook->api($param);
						
						// 去除垃圾数据
						$new_statuse = array();
						foreach($statuse as $key=>$statuse_list){
							//空数据
							if(empty($statuse_list['message']) && !isset($statuse_list['attachment']['media'])) {
								unset($statuse[$key]);
							}
							
						}
						
						$statuse = array_values($statuse);
						
						Yii::app()->session['your_statuse'] = $statuse;
						
						//print_r(Yii::app()->session['your_statuse']);exit;
						$this->redirect("/calendar?progress=1");
						exit;


					}
				}


				//settings点击sync 同步facebook信息
				if (Yii::app()->session['for_import'] == 1) {
				
					Yii::app()->session['for_import'] = 0;
					//判断当前facebook账户是否为 arq当前绑定的账户
					$is_bound = BindingAccount::model()->findAllByAttributes(array(
						'third_party_id' => $me['id']
					));

					if ($is_bound && $me) {
						
						//获取当前数据库中最新的时间节点
						$sql = "SELECT max(date_modified) FROM note WHERE user_id=".Yii::app()->user->id." AND fb_message_id is not null";
						
						$get_note = Yii::app()->db->createCommand($sql)->queryRow();
						//todo 暂时测试用
						//$get_note["max(date_modified)"] = null;
						
						$fql = "SELECT post_id,message,updated_time,created_time,attachment FROM stream WHERE source_id = me() AND is_hidden = 0 ";
						if($get_note["max(date_modified)"]) {
							//如果存在， 则获取比当前时间大的数据
							$latest_time = strtotime($get_note["max(date_modified)"]);
							
							$fql.= " AND updated_time > ".$latest_time." ORDER BY created_time DESC LIMIT 1000000";
						} else {
							$fql.= " ORDER BY created_time DESC LIMIT 1000000";
						}
						
					
						
						$param = array('method' => 'fql.query',
								'query' => $fql
								);
						$statuse = $facebook->api($param);
						
						// 去除垃圾数据
						$new_statuse = array();
						foreach($statuse as $key=>$statuse_list){
						//空数据
							if(empty($statuse_list['message']) && !isset($statuse_list['attachment']['media'])) {
								unset($statuse[$key]);
							}						
						}
										
						$statuse = array_values($statuse);
									
						Yii::app()->session['your_statuse'] = $statuse;
						
						//$this->getUserMessage($timePeriod = null);
						$this->redirect("/calendar?progress=2");
					} else {
						$this->redirect("/settings?error=1");
					}

				}
				
				//被绑定账户自动更新
				if(Yii::app()->session['auto_update'] == 1){
					
					Yii::app()->session['auto_update'] = 0;
					$is_bound = BindingAccount::model()->findByAttributes(array(
						'third_party_id' => $me['id']
					));
					if($is_bound) {
						$this->getUserMessage($timePeriod = null);
					}
					$this->redirect("/dashboard");
					
				}
				
				//facebook登录或注册
				$username_exists = User::model()->findByAttributes(array(
					'register_from' => 'fb_' . $me['id']
				));
				//do register action
				if ($me && !$username_exists) {

					$userRegisterArray = array();
					$userRegisterArray['first_name'] = isset($me['first_name']) ? $me['first_name'] : null;

					$userRegisterArray['last_name'] = isset($me['last_name']) ? $me['last_name'] : null;
					$userRegisterArray['gender'] = isset($me['gender']) ? $me['gender'] : null;

					if (isset($me['birthday'])) {
						$get_dob = explode('/', $me['birthday']);
						$userRegisterArray['year'] = $get_dob[2];
						$userRegisterArray['month'] = $get_dob[1];
						$userRegisterArray['day'] = $get_dob[0];
						$userRegisterArray['birthday'] = $me['birthday'];
					}else {
						$userRegisterArray['birthday'] = "1976/01/01";
					}
					$userRegisterArray['email'] = isset($me['email']) ? $me['email'] : null;
					$userRegisterArray['facebook_url'] = $me['link'];
					$userRegisterArray['register_from'] = 'fb_' . $me['id'];
					//facebook更新头像时，arq同步更新
					$img = new Image();
					$img->path = $this->getUserImage();
					if (!empty($img->path)) {
						$img->save();
						$image_id = $img->image_id;
						if ($image_id > 0) {
							$userRegisterArray['image_id'] = $image_id;
							$userRegisterArray['image_path'] = $img->path;
						}
					}


					//渲染注册页面
					$this->layout = 'arqLayout1';
					$this->setPageTitle('Register');
					$ethnicity = Ethnicity::model()->findAll();
					$now = Date("Y");
					//70岁封顶 10岁开始
					for ($i = $now - 70; $i <= $now - 10; $i++) {
						$years[] = $i;
					}
					$userRegisterArray['from'] = "facebook";
					$this->render('/fblogin/register', array('ethnicity' => $ethnicity,
						'years' => $years,
						'userRegisterArray' => $userRegisterArray));


					//do login action
				} else if ($username_exists) {

					$auth = new LoginForm();
					$pwd = $this->encrypt($username_exists['encrypt_pwd'], 'D', 'danielcome');
					$auth->attributes = array(
						'username' => $username_exists['username'],
						'password' => $pwd,
						'rememberMe' => true,
					);


					if ($auth->validate() && $auth->login()) {
					
						//todo 为老用户获取link
						$fb_url = User::model()->findByAttributes(array('username' => 'FB_' . $me['id']));
						if (empty($fb_url['facebook_url'])) {
							User::model()->updateByPk($fb_url['user_id'], array('facebook_url' => $me['link']));
						}


						//登陆成功后，先判断上次登录时间是否已过一个月
						$lastLoginDate = User::model()->findByAttributes(
							array('user_id' => Yii::app()->user->id)
						);
						//获取上次登录时间
						$lastLoginStamp = strtotime($lastLoginDate['login_date']);

						$nowStamp = strtotime("now");
						//更新登录时间
						$currentLoginDate = new CDbExpression('NOW()');
						User::model()->updateByPk(Yii::app()->user->id, array('login_date' => $currentLoginDate));
						$diff = $nowStamp - $lastLoginStamp;
						MyStuff::Log("LASt lOGIN STUFF ".$nowStamp.' '.$lastLoginStamp.' '.$diff);
						//超过一个月没有登录 弹出导入选择框
						if (empty($lastLoginStamp) || ($nowStamp - $lastLoginStamp) >= 2592000) {
							$this->redirect("/calendar?progress=1");
						}

						//$timePeriod = strtotime("-1 month");
						//$this->getUserMessage($timePeriod);
						$this->redirect("/calendar");
						exit;
					} else {

						$this->redirect("/index.php");
						exit;
					}


				}
			} catch (FacebookApiException $e) {
				$user = null;
				die("<script type='text/javascript'>top.location.href = '{$login_url}';</script>");
			}
		} else {


			die("<script type='text/javascript'>top.location.href = '{$login_url}';</script>");
		}
	}

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

	public function getUserMessage($timePeriod = null)
	{
		$facebook = new Facebook($this->config);

		
		//获取当前数据库中最新的时间节点
		$sql = "SELECT max(date_modified) FROM note WHERE user_id=".Yii::app()->user->id." AND fb_message_id is not null";
		
		$get_note = Yii::app()->db->createCommand($sql)->queryRow();
		//todo 暂时测试用
		$get_note["max(date_modified)"] = null;
		//var_dump($get_note["max(date_modified)"]);exit;					
		$fql = "SELECT post_id,message,updated_time,created_time,attachment FROM stream WHERE source_id = me() AND is_hidden = 0 ";
		if($get_note["max(date_modified)"]) {
			//如果存在， 则获取比当前时间大的数据
			$latest_time = strtotime($get_note["max(date_modified)"]);
			$fql.= " AND updated_time > ".$latest_time." ORDER BY created_time DESC LIMIT 1000000";
		} else {
			$fql.= " ORDER BY created_time DESC LIMIT 1000000";
		}
		$param = array('method' => 'fql.query',
				'query' => $fql
				);
		$statuse = $facebook->api($param);
		//var_dump($statuse);exit;				
		// 去除垃圾数据
		$new_statuse = array();
		foreach($statuse as $key=>$statuse_list){
			//空数据
			if(empty($statuse_list['message']) && !isset($statuse_list['attachment']['media'])) {
				unset($statuse[$key]);
			}						
		}
												
		$statuse = array_values($statuse);
		
		
		if ($statuse) {
			foreach ($statuse as $blogList) {
				$newBlog = explode('_', $blogList['post_id']);
				$blogList['post_id'] = $newBlog[1];

				//判断该博客是否已经入库
				$is_inserted = Note::model()->findByAttributes(array(
					'fb_message_id' => $blogList['post_id'],
					'user_id' => Yii::app()->user->Id
				));
				//todo 暂时解决 应对非死不可老版本的表情
				$blogList['message']=preg_replace("/👫 😺😼🚼💨/isU","",$blogList['message']);
				//如果 facebook的数据在库里存在，则表示该条数据为更新数据
				if($is_inserted) {
					//先删除相应表内数据
					$de_res = Note::model()->deleteAllByAttributes(array('fb_message_id' => $blogList['post_id']));
						if($de_res){
							//删除成功后，将facebook的数据重新插入.
							$is_inserted = null;
						}
				}
				/*
				if ($is_inserted['fb_video_ids'] != null && $blogList['attachment']['fb_object_type'] == "video") {
					$newPath = $blogList['attachment']['media'][0]['video']['source_url'];

					$update_res = Image::model()->updateByPk($is_inserted['fb_video_ids'], array('path' => $newPath));

				}*/
				$facebookNote = $blogList['message'];
				//只入库不存在的博客
				if (!$is_inserted) {
				
					$note = new Note();
					//存储facebook中post的视频或者图片
					
					if (isset($blogList['attachment']['media']) && !empty($blogList['attachment']['media']) && isset($blogList['attachment']['fb_object_type'])) {
						//如果为图片
						if ($blogList['attachment']['fb_object_type'] == "photo") {
							$currentPostImage = $blogList['attachment']['media'];
							$postImageID = array();
							foreach ($currentPostImage as $postImages) {
								$img = new Image();
								if (isset($postImages['photo']['images'][1])) {
									$img->path = $postImages['photo']['images'][1]['src'];
									$img->save();
									$postImageID[] = $img->image_id;
								}


							}
							$note->fb_image_ids = implode(",", $postImageID);

						}
						//如果为视频
						if ($blogList['attachment']['fb_object_type'] == "video") {

							//视频暂时不做循环
							$postFBVideo = $blogList['attachment']['media'][0];


							$img = new Image();
							$img->path = $postFBVideo['video']['source_url'];
							$img->save();
							$note->fb_video_ids = $img->image_id;


						}
						
						//如果为链接
						if($blogList['attachment']['fb_object_type'] == "link") {
							$blogList['message'] = $blogList['message'].$blogList['attachment']['media'][0]['href'];
						}
					}

					if(empty($blogList['message'])) {
						if(isset($blogList['attachment']['fb_object_type'])) {
							$facebookNote = "just media";
						}

					}
					$note->user_id = Yii::app()->user->Id;
					$note->title = substr($facebookNote, 0, 20);
					$note->content = $this->addcontentlink($facebookNote);
					$note->date_created = date('Y-m-d H:i:s', $blogList['created_time']);
					$note->date_modified = date('Y-m-d H:i:s', $blogList['updated_time']);
					$note->fb_message_id = $blogList['post_id'];
					$note->publish_date = date("y-m-d");


					if (!empty($blogList['message']) || isset($blogList['attachment']['media']['photo']['images']) || isset($blogList['attachment']['media']['video']['source_url'])) {

						$note->save();

					}
				}
			}
		}
	}

	function addcontentlink($content){
			$content = @ereg_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '<a href="\1" target=_blank rel=nofollow>\1</a>', $content);
			if(strpos($content, "http") === FALSE ){
				$content = @ereg_replace('(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '<a href="http://\1" target=_blank rel=nofollow >\1</a>', $content);
			}else{
				$content = @ereg_replace('([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '\1<a href="http://\2" target=_blank rel=nofollow>\2</a>', $content);
			}
			return $content;
		}
	public function getUserImage()
	{
		$facebook = new Facebook($this->config);
		$aResponse = $facebook->api('/me', array(
			'fields' => 'picture',
			'type' => 'large'
		));
		return $aResponse["picture"]["data"]["url"];

	}

	function sub_str($str, $charset = "utf-8"){
	$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	preg_match_all($re[$charset], $str, $match);
	return join("",$match[0]);
	}
	
	private function addLink($post,$note) {
		if (isset($post['link'])) {
			$link = $this->addContentLink($post['link']);
			$note->content = $note->content.'<br>'.$link;
		}		
	}
	
	private function getImages($facebook,$note,$post_id) {
		$post = $facebook->api('/'.$post_id.'/attachments');
		$image_ids = array();
		if (isset($post['data'])) {
			if (isset($post['data'][0])) {
				if (isset($post['data'][0]['subattachments'])) {
					$arr = $post['data'][0]['subattachments']['data'];
					foreach ($arr as $image) {
						$image_id = $this->addImage($image['media']['image']);
						array_push($image_ids,$image_id);
					}
				} else {
					$image_id = $this->addImage($post['data'][0]['media']['image']);
					array_push($image_ids,$image_id);
				}
			}
		}
		return $image_ids;
	}
	
	private function getVideo($facebook,$note,$post) {
		$video = new Video();
		$video->path = $post['source'];
		$video_post = $facebook->api('/'.$post['id'].'/attachments');
		MyStuff::Log("POST");MyStuff::Log($video_post);
		if (isset($video_post['data'])) {
			if (isset($video_post['data'][0])) {
				if (isset($video_post['data'][0]['media']) && isset($video_post['data'][0]['media']['image'])) {
					$video_data = $video_post['data'][0]['media']['image'];
					//MyStuff::Log("VIDEO DATA ");MyStuff::Log($video_data);
					$video->width = $video_data['width'];
					$video->height = $video_data['height'];
				}
			}
		}
		MyStuff::Log("VIDEO");MyStuff::Log($video);
		$video->save();
		$note->fb_video_ids = $video->video_id;	
	}
	
	private function addImage($image) {
		if (isset($image['src'])) {
			$img = new Image();
			$img->path = $image['src'];
			$img->save();
			return $img->image_id;
		}
	}
	
	private function processFacebookEntry($facebook,$post,$note) {
		
		if ($post['type'] == 'video') {
			// Get video image
			if (isset($post['source'])) {
				$this->getVideo($facebook,$note,$post);
			}
			// Add link to content
			$this->addLink($post,$note);
		} else if ($post['type'] == 'photo') {
			// Loop through attachments and get images
			if ( isset($post['id']) ) {
				$image_ids = $this->getImages($facebook,$note,$post['id']);
				$note->fb_image_ids = implode(",",$image_ids);
			}
		} else if ($post['type'] == 'link') {
			//Add link to content
			$this->addLink($post,$note);
		} 
		
	}
	
	public function  actionProgressBar()
	{
		if (Yii::app()->request->isAjaxRequest) { 
			$ae_hash = array();
			$note_status = NoteStatus::model()->findByAttributes(array('name' => 'Published'));
			$note_visibility = NoteVisibility::model()->findByAttributes(array('name' => 'Public'));
			/* Calendar event */
			$event_definition = EventDefinition::model()->findByAttributes(array(
					'parameter' => 'FB Note:'
			));
			$facebook = new Facebook($this->config);
			$user_id = $facebook->getUser();
			if (!$user_id) {
				echo CJSON::encode(array(
						'success' => 0,
						'msg' => "User cannot be verified",
				));
				exit;				
			}
			$since = strtotime(date('Y-m-d')) - 30*24*3600;//May use to test larger facebook accounts
			$me = $facebook->api('/me');
			$query = Yii::app()->request->getPost('query','');
			$queryStr = '/posts';
			if ($query) {
				$queryStr =$queryStr.'?'.$query;
			}
			
			$posts = $facebook->api('/'.$me['id'].$queryStr);//?since='.$since);
			
			if (empty($posts) ) {
				echo CJSON::encode(array(
						'success' => 0,
						'msg' => "You have not posts to import",
				));
				exit;				
			}
			
			$data = $posts['data'];
			foreach ($data as $datum) {
				if (isset($datum['created_time'])) {
					$fb_message_ids = explode("_", $datum['id']);
					$fb_message_id = $fb_message_ids[1];
					$is_inserted = Note::model()->findByAttributes(array(
							'fb_message_id' => $fb_message_id,
							'user_id' => Yii::app()->user->Id
					));
					if ($is_inserted) {
						
					} else {
						if (!$is_inserted) {
							
							$note = new Note();
							$note->user_id = Yii::app()->user->Id;
							$created_date = date('Y-m-d', strtotime($datum['created_time']));
							$facebookNote = "";
							if (isset($datum['message'])) 
							{
								$facebookNote = $datum['message'];
								if (!isset($ae_hash[$created_date])) {
									$ae_hash[$created_date] =array('date'=>$created_date,'time'=>date('h:m:s',strtotime($datum['created_time'])));
								}
							}
							$note->title = substr($facebookNote, 0, 20);
							$note->content = $this->addcontentlink($facebookNote);
							$note->stripped_content = $facebookNote;
							
							$note->date_created = date('Y-m-d H:i:s', strtotime($datum['created_time']));
							$note->date_modified = date('Y-m-d H:i:s', strtotime($datum['updated_time']));
							$note->fb_message_id = $fb_message_id;
							$note->publish_date = date("y-m-d",strtotime($datum['created_time']));
							$note->status_id = $note_status->status_id;
							$note->is_active = 1;
							$note->visibility_id = $note_visibility->visibility_id;
							$this->processFacebookEntry($facebook,$datum,$note);
							
							$note->save();
							//Bin the dates of content so we know which days to run the analyzer on
							$note->refresh();
							$cal = new CalendarEvent();
							$cal->user_id = Yii::app()->user->Id;
							$cal->start_date = $note->date_created;
							$cal->all_day = 0;
							$cal->save();
							
							/* Event value */
							
							$event_value = new EventValue();
							$event_value->calendar_event_id = $cal->calendar_event_id;
							$event_value->value = substr($facebookNote, 0, 15) . '...';
							$event_value->event_definition_id = $event_definition->event_definition_id;
							$event_value->save();
								
							$event_note = new EventNote();
							$event_note->note_id = $note->note_id;
							$event_note->event_value_id = $event_value->event_value_id;
							$event_note->save();
							
						} //Insertion check
						
					}
				}
			}
			foreach ($ae_hash as $myDate) {
				MyStuff::Log($myDate['date'].' '.$myDate['time']);
				SiteController::_runAEJournalDaily($myDate['date'],$myDate['date'],$myDate['time']);
			}
			MyStuff::Log("END AN QUERY ".$query);
			header('Content-type: application/json');

			if (isset($posts['paging']) && isset($posts['paging']['next'])) {
				echo CJSON::encode(array(
						'success' => 1,
						'nextKey' => 1,
						'start' => 5,
						'finish' => false,
						'next'=>parse_url($posts['paging']['next'])
				));				
			} else {
				echo CJSON::encode(array(
						'success' => 1,
						'nextKey' => 1,
						'start' => 100,
						'finish' => true
				));		
			}
		

		Yii::app()->end();
		exit;
						
			$statuse = Yii::app()->session['your_statuse'];
			
			if (empty($statuse)) {
				echo CJSON::encode(array(
					'success' => 0,
					'msg' => "you do not have the update！",
				));
				exit;
			}

			$key = Yii::app()->request->getPost('key', '');
			
			$fb_message_id = explode("_", $statuse[$key]['post_id']);
			//判断是否存在并入库操作
			$is_inserted = Note::model()->findByAttributes(array(
				'fb_message_id' => $fb_message_id[1],
				'user_id' => Yii::app()->user->Id
			));
			
			//todo 暂时解决 应对非死不可老版本的表情
			$statuse[$key]['message']=preg_replace("/👫 😺😼🚼💨/isU","",$statuse[$key]['message']);
			
			//如果 facebook的数据在库里存在，则表示该条数据为更新数据
			if($is_inserted) {
				//先删除相应表内数据
				$de_res = Note::model()->deleteAllByAttributes(array('fb_message_id' => $fb_message_id[1]));
				if($de_res){
					//删除成功后，将facebook的数据重新插入.
					$is_inserted = null;
				}
			}
			
					
			if (!$is_inserted) {
				$note = new Note();
				$note->user_id = Yii::app()->user->Id;
				//存储facebook中post的视频或者图片
				if (isset($statuse[$key]['attachment']['media']) && !empty($statuse[$key]['attachment']['media']) && isset($statuse[$key]['attachment']['fb_object_type'])) {

					//如果为图片
					if ($statuse[$key]['attachment']['fb_object_type'] == "photo") {
						$currentPostImage = $statuse[$key]['attachment']['media'];
						$postImageID = array();
						foreach ($currentPostImage as $postImages) {
							$img = new Image();
							if (isset($postImages['photo']['images'][1])) {

								$img->path = $postImages['photo']['images'][1]['src'];
								$img->save();
								$postImageID[] = $img->image_id;
							}


						}
						$note->fb_image_ids = implode(",", $postImageID);


					}
					//如果为视频
					if ($statuse[$key]['attachment']['fb_object_type'] == "video") {

						//视频暂时不做循环 取第一个
						$postFBVideo = $statuse[$key]['attachment']['media'][0];


						$img = new Image();
						$img->path = $postFBVideo['video']['source_url'];
						$img->save();
						$note->fb_video_ids = $img->image_id;


					}
					//如果为分享的链接
									
					if($statuse[$key]['attachment']['fb_object_type'] == "link") {
						$statuse[$key]['message'] = $statuse[$key]['message'].$statuse[$key]['attachment']['media'][0]['href'];
					}
				}
				$note->user_id = Yii::app()->user->Id;
				
				
				$facebookNote = $statuse[$key]['message'];
				//如果没有message 判断是否存在照片
				if(empty($statuse[$key]['message'])) {
					if(isset($statuse[$key]['attachment']['fb_object_type'])) {
						$facebookNote = "just media";
					} 
					
				}
				
				$note->title = substr($facebookNote, 0, 20);
				$note->content = $this->addcontentlink($facebookNote);
				$note->date_created = date('Y-m-d H:i:s', $statuse[$key]['created_time']);
				$note->date_modified = date('Y-m-d H:i:s', $statuse[$key]['updated_time']);
				$note->fb_message_id = $fb_message_id[1];
				$note->publish_date = date("y-m-d");
				if(!empty($statuse[$key]['message']) || isset($statuse[$key]['attachment']['fb_object_type'])) {
					
					$note->save();
				}
				
				

			}
		}
		
		$finish = false;
		if ($key >= count($statuse) - 1) {
			//销毁session
			unset(Yii::app()->session['your_statuse']);
			$finish = true;
			$start = count($statuse) - 1;
			
			
	
		} else {
			$key++;
			$start = $key;
		}

		header('Content-type: application/json');
		echo CJSON::encode(array(
			'success' => 1,
			'nextKey' => $key,
			'start' => $start,
			'finish' => $finish,
		));
		Yii::app()->end();


		$this->render('progress-bar');
	}
}
