<?php

class BaseController extends CController {
	public $menu = array();
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

	public $activeRubric = 0;
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
	public $icon_class;
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs = array();

	public $main_page = false;

	// флеш-нотис пользователю
	public function setNotice($message) {

		return Yii::app()->user->setFlash('notice', $message);
	}

	// флеш-ошибка пользователю
	public function setError($message) {

		return Yii::app()->user->setFlash('error', $message);
	}

	public function createUrl($route, $params = array(), $ampersand = '&') {

		$url = parent::createUrl($route, $params, $ampersand);

		return $url;
	}
}