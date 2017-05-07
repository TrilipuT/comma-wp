<?php

class FrontEndController extends BaseController {
	public $metaDescription,
		$url,
		$metaKeywords,
		$code_name,
		$og_title = '',
		$og_desc = '',
		$og_image = '',
		$og_url = '',
		$Section = null,
		$Settings = null,
		$sectionUrl,
		$lang,
		$type; // mobile

	public $featuresEnabled;
	public $infeedl_ids;

	public $transferArray; // для переводов js

	public $_itemList; // для настроек

	public $Banners; // не помню что это

	public $topBanner;
	public $activeSection,
		$activeSubRubricsId; //для меню

	public $searchCountAll,
		$searchCountNews,
		$searchCountArticles,
		$searchCountBlogs,
		$searchCountPhotos,
		$searchCountVideos;
	public $seo_index = 0, $seo_follow = 0;
	private $domain_id = 0;
	public $background_style, $background_link;

	public function beforeRender($view = "") {

		//if(GetRealIp() == '178.216.8.22')var_dump($this->Section->id);

		if (!Yii::app()->request->isAjaxRequest) {
			//фон
			$Banners = Banners::getBannerByType(4, 0, $this->Section->id);
			//присвоение переменных для вывода
			if ($Banners) {
				if (!empty($Banners->file_banner_static) && file_exists($_SERVER['DOCUMENT_ROOT'] . Banners::PATH_IMAGE . $Banners->file_banner_static)) {

					$this->background_style = 'background-image: url(' . Banners::PATH_IMAGE . $Banners->file_banner_static . '); ';

					if (!empty($Banners->background_color) && $Banners->background_color != '#') {
						$this->background_style .= 'background-color: ' . $Banners->background_color . ';';
					}

					$this->background_link = $Banners->target_url;
				}
			} else {
				//горизонтальный
				$Banners = Banners::getBannerByType(1, 0, $this->Section->id);
				//var_dump($Banners);

				if ($Banners) {
					$this->topBanner = $Banners;
				}
			}
		}

		return true;
	}

	public function init() {

		// set the layout path
		//$this->layoutPath = Yii::getPathOfAlias('admin.views.layouts');
		// set the layout
		//$this->layout = 'main';

		//var_dump(Yii::app()->language);

		//var_dump($transferArray);
		//$detect = new Mobile_Detect;
		//$deviceType = ($detect->isMobile() ?  'phone' : 'computer');
		//$scriptVersion = $detect->getScriptVersion();
		/*
				Yii::import('application.components.Mobile_Detect');
				$detect = new Mobile_Detect;
				if($detect->isMobile()){
					Yii::app()->params->isMobile = 1;
				}
				else{
					Yii::app()->params->isMobile = 0;
				}
				if($detect->is('iOS')){
					Yii::app()->params->isIOS = 1;
				}
				else{
					Yii::app()->params->isIOS = 0;
				}*/
	}

	public function __construct($id, $module = null) {

		parent::__construct($id, $module);

		$this->domain_id = Section::getDomainId();

		$this->layout = Yii::app()->params['layout'];
		$this->pageTitle = Yii::t('common', Yii::app()->name);

		//----------------------------------------------------------------------
		if (isset($_POST['language'])) {
			$lang = $_POST['language'];
			$MultilangReturnUrl = $_POST[$lang];
			$this->redirect($MultilangReturnUrl);
		}

		// Set the application language if provided by GET, session or cookie
		if (isset($_GET['language'])) {
			Yii::app()->language = $_GET['language'];
			Yii::app()->user->setState('language', $_GET['language']);

			$cookie = new CHttpCookie('language', $_GET['language']);
			$cookie->expire = time() + (60 * 60 * 24 * 365); // (1 year)
			Yii::app()->request->cookies['language'] = $cookie;
		}
		//----------------------------------------------------------------------

		if ($this->id != 'section' && $this->id != 'site') {
			$this->code_name = Base::findControllerAlias($this->id);
		} else {
			$this->code_name = Yii::app()->request->getParam('code_name');
		}

		if ($this->code_name != null) {
			$this->Section = Section::model()->published()->onlyDomain($this->domain_id)->withCodeName($this->code_name)->find();

			if (GetRealIp() == '178.216.8.22') {
				//var_dump($this->code_name, $this->domain_id, $this->Section );
				//var_dump($this->Section); exit;
			}

			if (!$this->Section && $this->domain_id != 1) {
				$Section = Section::model()->published()->withCodeName($this->code_name)->find();
				if ($Section) {
					$domain = Section::getSubDomain($Section->domain_id);
					if ($this->domain_id != $Section->domain_id) { // && GetRealIp() != '178.216.8.22'
						$url = ($domain != 'main') ? $domain . '.' . Yii::app()->params['main-domain'] : Yii::app()->params['main-domain'];
						header("HTTP/1.1 301 Moved Permanently");
						header("Location: http://" . $url . $_SERVER['REQUEST_URI']);
						exit;
					}
				}
			}
		} else {

			$this->Section = Section::model()->published()->home()->find();
		}

		if (!empty($this->Section)) {
			$Section = $this->Section->checkCurrentDomain();

			if ($Section) {
				Yii::app()->theme = Section::getSubDomain($Section->domain_id);
				$this->Section = $Section;
			}

			if ($this->Section->transfer->page_title == '' && !empty($this->Section->transfer->name)) {
				$this->Section->transfer->page_title = strip_tags($this->Section->transfer->name);
			}

			if ($this->Section->transfer->meta_description == '' && !empty($this->Section->transfer->name)) {
				$this->Section->transfer->meta_description = strip_tags($this->Section->transfer->name);
			}

			if ($this->Section->transfer->meta_keywords == '' && !empty($this->Section->transfer->name)) {
				$this->Section->transfer->meta_keywords = strip_tags($this->Section->transfer->name);
			}

			$this->metaDescription = $this->Section->transfer->meta_description;
			$this->pageTitle = $this->Section->transfer->page_title;
			$this->metaKeywords = $this->Section->transfer->meta_keywords;

			$this->og_title = $this->Section->transfer->page_title;
			$this->og_desc = $this->Section->transfer->meta_description;

			if (!empty($this->Section->share_image) && file_exists($_SERVER['DOCUMENT_ROOT'] . Section::PATH_SHARE_IMAGE . $this->Section->share_image)) {
				$this->og_image = 'http://' . $_SERVER['HTTP_HOST'] . Section::PATH_SHARE_IMAGE . $this->Section->share_image;
			} else if (!empty($this->Section->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'] . Section::PATH_IMAGE . $this->Section->image_filename)) {
				$this->og_image = 'http://' . $_SERVER['HTTP_HOST'] . Section::PATH_IMAGE . $this->Section->image_filename;
			}

		}

		$this->icon_class = $this->Section->color_class;

		$this->featuresEnabled = array(
			"nativeAds" => 1 //$_REQUEST['native_ads']
		);

		$this->infeedl_ids = array(
			"main_placement" => "95f1fff2-d9b3-4943-b0ec-b8860c0a8145",
			"article_placement" => "14ed80cd-7048-4559-95cc-cfa6e23d8e5f"
		);

		return true;
	}

	public function createMultilanguageReturnUrl($lang = 'ru') {
		if (count($_GET) > 0) {
			$arr = $_GET;
			$arr['language'] = $lang;
		} else {
			$arr = array('language' => $lang);
		}

		return $this->createUrl('', $arr, '&amp;');
	}

	public function actionLogin() {
		$session = new CHttpSession;
		$session->open();
		$session['soc_redirect'] = '';

		$serviceName = Yii::app()->request->getQuery('service');
		$returnUrl = Yii::app()->request->getQuery('returnUrl');
		if (isset($serviceName)) {
			/** @var $eauth EAuthServiceBase */
			$eauth = Yii::app()->eauth->getIdentity($serviceName);

			if (isset($returnUrl)) {
				$eauth->setRedirectUrl($returnUrl);
				$eauth->setCancelUrl($returnUrl);
			} else {
				$eauth->setRedirectUrl(Yii::app()->user->returnUrl);
				$eauth->setCancelUrl(Yii::app()->user->returnUrl);
			}

			try {
				if ($eauth->authenticate()) {
					//var_dump($eauth->getIsAuthenticated(), $eauth->getAttributes());
					$identity = new EAuthUserIdentity($eauth);

					// successful authentication
					if ($identity->authenticate()) {
						Yii::app()->user->login($identity);

						$UsersAuth = new UsersAuth($serviceName);
						$Users = UsersAuth::isLogin();

						if ($Users && !empty($session['sziget_vote_member_id'])) {
							$MembersLikes = new MembersLikes((int)$session['sziget_vote_member_id'], (int)$Users->id);
							$MembersLikes->_set();

							$session['sziget_vote_member_id'] = null;
						}

						if (Yii::app()->request->cookies['user_not_login_comment']->value) {
							$cookie = new CHttpCookie('user_not_login_comment', 1);
							$cookie->expire = time() - 60 * 10;
							Yii::app()->request->cookies['user_not_login_comment'] = $cookie;
							//----------------------------------------------------------------
							$_array = $session['user_comment'];

							$text = $_array['user_comment_text'];
							$parent = $_array['user_comment_parent'];
							$type = $_array['user_comment_type'];
							$data_id = $_array['user_comment_data_id'];

							if ($type != null && $text != null && $data_id > 0) {
								if ($Users) {
									$GetComments = new GetComments($type);
									$GetComments->withType($data_id);
									$GetComments->setUser($Users->id);

									if ($GetComments->checkLastAdd($text)) {

										$Comment = new Comment();
										switch ($type) {
											case 'news':
												$Comment->news_id = $data_id;
												break;
											case 'article':
												$Comment->article_id = $data_id;
												break;
											case 'video':
												$Comment->video_id = $data_id;
												break;
											case 'gallery':
												$Comment->gallery_id = $data_id;
												break;
											default:
												return false;
										}

										$Comment->user_id = $Users->id;
										$Comment->comment_id = $parent;
										$Comment->datetime = date('Y-m-d H:i:s');
										$Comment->content = CHtml::encode($text);
										$Comment->active = 1;

										if ($Comment->save()) {
											Comment::recountAllComments(0, $data_id, $type);
											//-------------------------------------------
											$GetComments = new GetComments($type);
											$GetComments->withType($data_id);
											$GetComments->setActive(1);
											$commentsItems = $GetComments->getCommentsRecursive();
											//-----------------------------------------------------

											$reddirectUrl = $eauth->getRedirectUrl();
											$reddirectUrl .= "#comment_item_" . $Comment->id;
											$eauth->setRedirectUrl($reddirectUrl);
										}
									}
								} else {
									$session['user_deactive_popup'] = true;
								}
								//$session['user_deactive_popup'] = true;
								$session['user_comment'] = array();
							}
						}

						if($_SERVER['HTTP_HOST'] == 'sziget.comma.com.ua'){
							$eauth->setRedirectUrl('http://sziget.comma.com.ua/members/');
							$eauth->setCancelUrl('http://sziget.comma.com.ua/members/');
						}

						$session['soc_redirect'] = $eauth->getRedirectUrl();
						$session['sziget_vote_member_id'] = null;
						// special redirect with closing popup window
						$eauth->redirect();

						return $eauth->getRedirectUrl();
					} else {
						if($_SERVER['HTTP_HOST'] == 'sziget.comma.com.ua'){
							$eauth->setRedirectUrl('http://sziget.comma.com.ua/members/');
							$eauth->setCancelUrl('http://sziget.comma.com.ua/members/');
						}

						// close popup window and redirect to cancelUrl
						$eauth->cancel();
					}
				}

				// Something went wrong, redirect to login page
				$this->redirect(array('site/login'));
			} catch (EAuthException $e) {
				// save authentication error to session
				//Yii::app()->user->setFlash('error', 'EAuthException: ' . $e->getMessage());

				if (GetRealIp() == '178.216.8.28') {
					echo '<pre>';
					var_dump($e->getMessage(), $e->getTrace());
					echo '<pre>';
					exit;
				}

				if($_SERVER['HTTP_HOST'] == 'sziget.comma.com.ua'){
					$eauth->setRedirectUrl('http://sziget.comma.com.ua/members/');
					$eauth->setCancelUrl('http://sziget.comma.com.ua/members/');
				}

				// close popup window and redirect to cancelUrl
				$eauth->redirect($eauth->getCancelUrl());
			}
		}

		// default authorization code through login/password ..
	}

} 