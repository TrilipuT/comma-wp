<?php

class Section extends BaseModel {
	public static $real_domain_id;

	const PATH_IMAGE = '/graphics/section/image/',
		PATH_SHARE_IMAGE= '/graphics/section/share_img/';

	public $image_delete = 0,
		$image,
		$children = array(),
		$transfer_type = true,
		$filesRow;
	public static $SectionUrl;
	private static $subDomainList = array(
		0 => 'main',
		1 => 'made-in-ukraine',
		2 => 'sziget',
		3 => 'test',
	);

	public static function getSubDomainList() {
		return self::$subDomainList;
	}

	public static function getSubDomain($domain_id){
		return isset(self::$subDomainList[$domain_id]) ? self::$subDomainList[$domain_id] : self::$subDomainList[0];
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Section the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return $this->tablePrefix() . 'sections';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code_name', 'required'),
			array('menu_top, menu_main, order_num, active', 'numerical', 'integerOnly' => true),
			array('code_name, image_filename', 'length', 'max' => 50),
			array('controller', 'length', 'max' => 45),
			//array('link', 'length', 'max'=>100), 
			array('share_image, image_delete, image, filesRow', 'default'),

			array('id, share_image, domain_id, color_class, gallery_id, home, code_name, controller, image_filename,
					menu_top, menu_main, order_num, added_time, edited_time, active, guest_of', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {

		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'transfer' => array(self::HAS_ONE,
								'SectionTransfer',
								'parent_id',
								'condition' => 'transfer.language_id = :lang_id',
								'params' => array(':lang_id' => Language::getActiveLanguageId()))
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'parent_id' => 'родитель',
			'home' => 'главная',
			'code_name' => 'кодовое имя',
			'menu_name' => 'название в меню',
			'name' => 'название (тайтл)',
			'controller' => 'Controller (обработчик раздела)',
			'image_filename' => 'Image Filename',
			'ico_filename' => 'Ico Filename',
			'menu_top' => 'верхнее меню',
			'menu_main' => 'нижнее меню',
			'with_banner' => 'с баннером',
			'with_socials' => 'с блоком соц.сетей',
			'order_num' => 'Порядковый номер',
			'added_time' => 'Added Time',
			'edited_time' => 'Edited Time',
			'active' => 'Активный',
			'color_class' => 'клас цвета (соц сети,иконки)',

		);
	}

	protected function beforeSave() {

		parent::beforeSave();

		if ($this->image_delete == 1 && $this->image_filename != '') {
			$this->fileDelete(self::PATH_IMAGE . $this->image_filename);
			$this->image_filename = '';
		}

		return true;
	}

	protected function afterSave() {

		parent::afterSave();

		//удаление маркера новой записи
		if ($this->isNewRecord) {
			unset($this->isNewRecord);
		}

		if ($_FILES[__CLASS__] != null) {

			$doc = CUploadedFile::getInstance($this, 'image');
			if ($doc) {
				//$type 	= $doc->getType();
				//$doc->getSize();

				Yii::import('application.components.Image');

				$tmp_image = $doc->getTempName();

				$Image = new Image();

				$this->image_filename = basename($Image->load($tmp_image)
					->crop(array(367, 214))
					->save($_SERVER['DOCUMENT_ROOT'] . self::PATH_IMAGE . $this->id));

				unset($_FILES[__CLASS__]);
				$this->update(array('image_filename'));
			}
		}

		if (count($this->filesRow) > 0) {
			Files::updateDataByModel(__CLASS__, $this->filesRow, $this->id);
		}

		return true;
	}

	protected function beforeDelete() {
		parent::beforeDelete();

		return true;
	}

	protected function afterDelete() {

		parent::afterDelete();

		if ($this->image_filename != '') {
			$this->fileDelete(self::PATH_IMAGE . $this->image_filename);
			$this->image_filename = '';
		}

		return true;
	}

	public function getSectionUrl() {
		if (self::$SectionUrl == '') {
			if (Yii::app()->language != 'ru') {
				self::$SectionUrl = ''; //'/' . Yii::app()->language
			} else {
				self::$SectionUrl = '';
			}
		}

		return self::$SectionUrl;
	}

	public function getUrl() {
		return self::getSectionUrl() . '/' . $this->code_name . '/';
	}

	public function getItemUrl() {
		return self::getSectionUrl() . '/' . $this->code_name . '/'; //.'.html'
	}

	public function hasController() {
		if (empty($this->controller) || !file_exists('protected/controllers/frontend/' . $this->controller)) {
			return false;
		}

		return true;
	}

	public function onlyDomain($domain_id = 0) {
		$domain_id = intval($domain_id);

		if (!isset(self::$subDomainList[$domain_id])) {
			$domain_id = 0;
		}

		$this->getDbCriteria()->mergeWith(array(
			'condition' => $this->tableAlias . '.domain_id = :domain_id',
			'params' => array(':domain_id' => $domain_id)
		));

		return $this;
	}

	public static function getDomainId() {
		$items = explode('.', $_SERVER['HTTP_HOST']);

		return array_search($items[0], self::$subDomainList);
	}

	public function checkCurrentDomain() {
		$domain_id = self::getDomainId();
		$Section = null;

		if ($this->domain_id != $domain_id) {
			$Section = Section::model()->published()->withCodeName($this->code_name)->onlyDomain($domain_id)->find();
		}

		return $Section;
	}
}
