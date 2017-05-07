<?php

class BaseModel extends CActiveRecord {
	public $share_image, $shareimage_delete, $gif_delete, $image_delete;
	const STATUS_ACTIVE = 1;
	const STATUS_DRAFT = 2;
	const STATUS_INACTIVE = 0;
	const STATUS_ACTIVE_TEXT = 'Включен';
	const STATUS_DRAFT_TEXT = 'Черновик';
	const STATUS_INACTIVE_TEXT = 'Выключен';
	private $_statusList = array();
	private $_changefreqList = array();
	//для кропера в админке
	protected $uploadedFile = null;

	public function addUploadedFile($file) {
		$this->uploadedFile = $file;
	}

	public function getUploadedFile() {
		return $this->uploadedFile;
	}

	public static function colorArray() {

		return array('socials_orange' => 'orange',
					 'socials_red' => 'red',
					 'socials_gray' => 'gray',
					 'socials_purple' => 'purple',
					 'socials_blue' => 'blue'
		);
	}

	// маркер для оприделение есть ли у модели, таблица с переводом
	public $transfer_type = false;

	public function init() {

		parent::init();

		if (isset($this->active)) {
			if ($this->isNewRecord) {
				$this->active = 1;
			}
		}

	}

	public function withTransferCodeName($codeName, $lang = 0) {

		if ($lang == 0) {
			$lang = Language::getActiveLanguageId();
		}

		$this->getDbCriteria()->mergeWith(array(
			'with' => array(
				'transfer' => array(
					'joinType' => 'LEFT JOIN',
					'condition' => 'transfer.code_name = :code_name
        							AND transfer.language_id = :lang_id',
					'params' => array(':code_name' => $codeName,
									  ':lang_id' => $lang)
				),
			)
		));

		return $this;
	}

	public function withTransfer($lang = 0) {

		if ($lang == 0) {
			$lang = Language::getActiveLanguageId();
		}

		$this->getDbCriteria()->mergeWith(array(
			'with' => array(
				'transfer' => array(
					'joinType' => 'LEFT JOIN',
					'condition' => 'transfer.language_id = :lang_id',
					'params' => array(':lang_id' => $lang)
				),
			)
		));

		return $this;
	}

	public function getChangefreqList() {
		if (!empty($this->_changefreqList)) {
			return $this->_changefreqList;
		}

		$this->_changefreqList = array(
			'always' => 'always',
			'hourly' => 'hourly',
			'daily' => 'daily',
			'weekly' => 'weekly',
			'monthly' => 'monthly',
			'yearly' => 'yearly',
			'never' => 'never',

		);

		return $this->_changefreqList;
	}

	protected function beforeValidate() {
		parent::beforeValidate();

		//если у модели есть такие свойства
		if (isset($this->added_time) && isset($this->edited_time)) {

			if ($this->isNewRecord) {
				$this->added_time = date('Y-m-d H:i:s');
			}
			$this->edited_time = date('Y-m-d H:i:s');
		}

		if (isset($this->code_name)) {
			$this->code_name = Base::rus2translit($this->code_name);
		}

		return true;
	}

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function limit($num = 1) {

		$this->getDbCriteria()->mergeWith(array(
			'limit' => $num
		));

		return $this;
	}

	protected static function tablePrefix() {

		return 'comma_';
	}

	public function findByParent($parent_id, $lang_id = 1) {

		$result = $this->find(array('condition' => 'parent_id = :parent_id
														AND language_id = :language_id',

									'params' => array(':parent_id' => $parent_id,
													  ':language_id' => $lang_id)));

		return $result;
	}

	public function getStatusList() {
		if (!empty($this->_statusList)) {
			return $this->_statusList;
		}

		$this->_statusList = array(
			self::STATUS_INACTIVE => self::STATUS_INACTIVE_TEXT,
			self::STATUS_DRAFT => self::STATUS_DRAFT_TEXT,
			self::STATUS_ACTIVE => self::STATUS_ACTIVE_TEXT,
		);

		return $this->_statusList;
	}

	/*
	* не работает для таблиц с переводом
	*/
	public function getNameByPk($id, $name = 'name') {

		$result = Yii::app()->db->createCommand()
			->select('id, ' . $name)
			->from($this->tableName())
			->where('id = ' . $id)
			->queryRow();

		return $result[$name];
	}

	/*
	*  для таблиц с переводом
	*/
	public function getTransferRowByPk($id, $name = 'name') {

		$className = get_class($this);
		$class = new $className;
		$class = $class->findByPk($id);

		return $class->transfer->{$name};
	}

	public function getDate($monthType = '') {

		$time = strtotime($this->datetime);

		if ($monthType == 'name') {
			$date = date('j', $time) . ' ' . Yii::t('app', 'm' . date('m', $time));
		} else {
			$date = date('j.m', $time);
		}

		if (date('Y', $time) < date('Y')) {
			$date .= ' ' . date('Y', $time);
		}

		return $date;
	}

	public function scopes() {

		return array(
			'published' => array(
				'condition' => $this->tableAlias . '.active = 1',
			),
			'textNoEmpty' => array(
				'condition' => $this->tableAlias . '.text != "" '
			),
			'descNoEmpty' => array(
				'condition' => $this->tableAlias . '.description != "" '
			),
			'nameNoEmpty' => array(
				'condition' => $this->tableAlias . '.name != "" '
			),
			'abstractNoEmpty' => array(
				'condition' => $this->tableAlias . '.abstract != "" '
			),
			'home' => array(
				'condition' => $this->tableAlias . '.home = 1 '
			),
			'topMenu' => array(
				'condition' => $this->tableAlias . '.menu_top = 1 '
			),
			'mainMenu' => array(
				'condition' => $this->tableAlias . '.menu_main = 1 '
			),
			'orderByIdDesc' => array(
				'condition' => $this->tableAlias . '.id DESC '
			),
			'actual' => array(
				'condition' => $this->tableAlias . '.datetime <= NOW() '
			),
		);
	}

	public function withCodeName($codeName) {

		$this->getDbCriteria()->mergeWith(array(
			'condition' => $this->tableAlias . '.code_name = :code_name',
			'params' => array(':code_name' => $codeName)
		));

		return $this;
	}

	public function withCategory($id) {

		$this->getDbCriteria()->mergeWith(array(
			'condition' => $this->tableAlias . '.category_id = :category_id',
			'params' => array(':category_id' => $id)
		));

		return $this;
	}

	public function orderByDateDesc() {

		$this->getDbCriteria()->mergeWith(array(
			'order' => $this->tableAlias . '.datetime DESC, ' . $this->tableAlias . '.order_num'
		));

		return $this;
	}

	public function orderByIdDesc() {

		$this->getDbCriteria()->mergeWith(array(
			'order' => $this->tableAlias . '.id DESC '
		));

		return $this;
	}

	public function orderByName() {

		$this->getDbCriteria()->mergeWith(array(
			'order' => $this->tableAlias . '.name'
		));

		return $this;
	}

	public function orderByNameDesc() {

		$this->getDbCriteria()->mergeWith(array(
			'order' => $this->tableAlias . '.name DESC'
		));

		return $this;
	}

	public function from($offset = 0) {

		$this->getDbCriteria()->mergeWith(array(
			'offset' => $offset
		));

		return $this;
	}

	public function only($limit = 1) {

		$this->getDbCriteria()->mergeWith(array(
			'limit' => $limit
		));

		return $this;
	}

	public function page($page = 1, $perPage = 10) {

		$this->getDbCriteria()->mergeWith(array(
			'offset' => (($page - 1) * $perPage), // + 1
			'limit' => $perPage
		));

		return $this;
	}

	public function orderByDate() {

		$this->getDbCriteria()->mergeWith(array(
			'order' => $this->tableAlias . '.date DESC'
		));

		return $this;
	}

	public function orderByOrderNum() {

		$this->getDbCriteria()->mergeWith(array(
			'order' => $this->tableAlias . '.order_num'
		));

		return $this;
	}

	public function withLanguage($language_id = '', $tableAlias = '') {

		if ($tableAlias == '') {
			$tableAlias = $this->tableAlias;
		}

		if ($language_id == '') {
			$language_id = Language::getActiveId();
		}

		$this->getDbCriteria()->mergeWith(array(
			'condition' => $tableAlias . '.language_id = :language_id',
			'params' => array(':language_id' => $language_id)
		));

		return $this;
	}

	protected function fileDelete($filename) {

		$file = $_SERVER['DOCUMENT_ROOT'] . $filename;

		if (is_file($file) && file_exists($file)) {
			@chmod($file, 0777);
			unlink($file);
		}

		return true;
	}

	protected function beforeDelete() {

		parent::beforeDelete();

		if ($this->transfer_type) {

			$transfer = get_class($this) . 'Transfer';
			$transfer::model()->deleteAll('parent_id = :parent_id', array(':parent_id' => $this->id));
		}

		$images_to_crop = $this->thumbnailsRules();

		if(!empty($this->image_filename) && !empty($images_to_crop['image'])){
			$this->fileDelete(static::PATH_IMAGE_SRC.$this->image_filename);

			foreach($images_to_crop['image'] as $item){
				$this->fileDelete($item['path'] . $this->image_filename);
			}

			$this->image_filename = '';
		}

		if(!empty($this->image_filename) && !empty($images_to_crop['icon'])){
			$this->fileDelete(static::PATH_ICON_SRC.$this->icon_filename);

			foreach($images_to_crop['icon'] as $item){
				$this->fileDelete($item['path'] . $this->icon_filename);
			}

			$this->icon_filename = '';
		}

		return true;
	}

	public function thumbnailsRules() {
		return array();
	}

	protected function beforeSave() {

		parent::beforeSave();

		// для тех у кого есть маркер блокировки и свойство кто редактирует (обычно если есть одно то и 2е должно быть)
		if (isset($this->blocked) && isset($this->modified_by)) {
			//присваиваем пользователя
			$this->modified_by = Yii::app()->user->getId();
		}

		//гифка на превью
		if ($this->gif_delete == 1 && $this->gif_filename != '') {
			$this->fileDelete($this::PATH_IMAGE_GIF . $this->gif_filename);
			$this->gif_filename = '';
		}

		//картинка для шаринга
		if ($this->shareimage_delete == 1 && $this->share_image != '') {
			$this->fileDelete($this::PATH_SHARE_IMAGE . $this->share_image);
			$this->share_image = '';
		}

		$images_to_crop = $this->thumbnailsRules();
		if(!empty($this->image_filename) && !empty($images_to_crop['image']) && $this->image_delete == 1){
			$this->fileDelete(static::PATH_IMAGE_SRC.$this->image_filename);

			foreach($images_to_crop['image'] as $item){
				$this->fileDelete($item['path'] . $this->image_filename);
			}

			$this->image_filename = '';
		}

		if(!empty($this->image_filename) && !empty($images_to_crop['icon']) && $this->icon_delete == 1){
			$this->fileDelete(static::PATH_ICON_SRC.$this->icon_filename);

			foreach($images_to_crop['icon'] as $item){
				$this->fileDelete($item['path'] . $this->icon_filename);
			}

			$this->icon_filename = '';
		}

		return true;
	}

	protected function afterSave() {

		parent::afterSave();

		//удаление маркера новой записи
		if ($this->isNewRecord && get_class($this) != 'Editors') {
			unset($this->isNewRecord);
		}

		//картинка для шаринга
		if ($_FILES[get_class($this)]['tmp_name']['shareimage'] != null) {
			$doc = CUploadedFile::getInstance($this, 'shareimage');
			if ($doc) {

				$filename = $this->getFileName();

				if ($this->share_image != '') {
					$this->fileDelete($this::PATH_SHARE_IMAGE . $this->share_image);
				}

				Yii::import('application.components.Image');

				$tmp_image = $doc->getTempName();

				$Image = new Image();
				$Image->load($tmp_image);

				if ($Image->getWidth() < 500 || $Image->getHeight() < 250) {
					$this->addError('shareimage', 'Картинка [share_image] слишком маленькая.
                                                    Загрузите, пожалуйста, картинку большего разрешения,
                                                    минимальный размер картинки 500x250, вы пытаетесь загрузить [' . $Image->getWidth() . 'x' . $Image->getHeight() . ']');

					return false;
				}

				$this->share_image = basename($Image->save($_SERVER['DOCUMENT_ROOT'] . $this::PATH_SHARE_IMAGE . $filename));

				$_FILES[get_class($this)]['tmp_name']['shareimage'] = null;
				$this->update(array('share_image'));

				return false;
			}
		}

		//гифка для превьюшки
		if ($_FILES[get_class($this)]['tmp_name']['gif'] != null) {

			$doc = CUploadedFile::getInstance($this, 'gif');
			if ($doc) {

				$filename = $this->getFileName();

				$ext = explode('.', $doc->getName());
				$ext = $ext[count($ext) - 1];

				$tmp_image = $doc->getTempName();

				if ($this->gif_filename != '') {
					$this->fileDelete($this::PATH_IMAGE_GIF . $this->gif_filename);
				}

				copy($tmp_image, $_SERVER['DOCUMENT_ROOT'] . $this::PATH_IMAGE_GIF . $filename . '.' . $ext);
				$this->gif_filename = $filename . '.' . $ext;

				$_FILES[get_class($this)]['tmp_name']['gif'] = null;
				$this->update(array('gif_filename'));

				return false;
			}
		}

		return true;
	}

	public function textOutput($text) {

		$status = preg_match_all('|###GALLERY_([0-9]+)###|', $text, $mathces);
		if ($status) {

			foreach ($mathces[0] as $key => $mathc) {
				$html = '';
				$Gallery = Gallery::model()->published()->with(array('photos:orderByOrderNum'))->findByPk($mathces[1][$key]);
				if ($Gallery) {
					$html = Yii::app()->controller->renderPartial('/layouts/_gallery', array('Gallery' => $Gallery), true);
				}

				$text = str_replace($mathc, $html, $text);
			}
		}

		$status2 = preg_match_all('|###GALLERY_SHARE_FB_([0-9]+)###|', $text, $matches2);
		if ($status2) {
			foreach ($matches2[0] as $key => $match) {
				$gallery = Gallery::model()->findByPk($matches2[1][$key]);
				$url = $gallery->getItemUrl();
				$url = 'http://comma.com.ua' . $this->getItemUrl() . '?image=' . $matches2[1][$key];
				$html = '<div class="fb-like" data-href="' . $url . '" data-layout="box_count" data-action="like" data-show-faces="false" data-share="false"></div>';
				$text = str_replace($match, $html, $text);
			}
		}

		$text = str_replace("&nbsp;", "", $text);

		return $text;
	}

	public function increaseView($liveTime = 2592000) {

		if ($this->code_name == '') {
			$code_name = $this->id;
		} else {
			$code_name = $this->code_name;
		}

		$cookiesName = get_class($this) . $code_name;

		if (Yii::app()->request->cookies[$cookiesName]->value == null) {
			$cookie = new CHttpCookie($cookiesName, 1);
			$cookie->expire = time() + $liveTime;
			Yii::app()->request->cookies[$cookiesName] = $cookie;

			$views_num = rand(1, 4);
			$connection = Yii::app()->db;
			$transaction = $connection->beginTransaction();
			try {
				$connection->createCommand('UPDATE ' . $this->tableName() . ' as t SET t.`views_num` = (t.`views_num`+' . $views_num . ') WHERE t.id = ' . $this->id)->execute();
				$transaction->commit();
			} catch (Exception $e) {
				$transaction->rollback();
			}
			$this->views_num = $this->views_num + $views_num;
		}
	}

	private function getFileName() {
		if (!$this->isNewRecord) {
			$filename = $this->id . '_' . substr(md5(time()), 0, 8);
		} else {
			$filename = substr(md5(time()), 0, 8);
		}

		return $filename;
	}


}