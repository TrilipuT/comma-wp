<?php

/**
 * This is the model class for table "comma_members".
 *
 * The followings are the available columns in table 'comma_members':
 * @property integer $id
 * @property string $code_name
 * @property string $image_filename
 * @property integer $order_num
 * @property string $changefreq
 * @property string $priority
 * @property integer $created_by
 * @property integer $modified_by
 * @property integer $blocked
 * @property string $added_time
 * @property string $edited_time
 * @property integer $active
 */
class Members extends BaseModel {
	public static $SectionUrl;
	const PATH_IMAGE_SRC = '/graphics/members/img/src/',
		PATH_ICON_SRC = '/graphics/members/icon/src/',
		IMAGE_490x300 = '/graphics/members/490x300/',
		IMAGE_400x500 = '/graphics/members/400x500/';
	public $icon_delete = 0,
		$icon,
		$image_delete = 0,
		$image;
	public $transfer_type = true;

	public function thumbnailsRules() {
		return array(
			'image' => array(
				'490x300' => array('method' => 'crop', 'canPinch' => true, 'path' => self::IMAGE_490x300)
			),
			'icon' => array(
				'400x500' => array('method' => 'crop', 'canPinch' => true, 'path' => self::IMAGE_400x500)
			)
		);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return parent::tablePrefix() . 'members';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_num, created_by, modified_by, blocked, active', 'numerical', 'integerOnly' => true),
			array('code_name', 'length', 'max' => 255),
			array('image_filename', 'length', 'max' => 50),
			array('changefreq', 'length', 'max' => 10),
			array('priority', 'length', 'max' => 3),
			array('image, image_delete, icon, icon_delete', 'default'),
			array('id, code_name, image_filename, icon_filename, order_num, changefreq, priority,
			created_by, modified_by, blocked, added_time, edited_time, active,
			link_fb, link_vk, link_lk, likes_fb, likes_vk, big_image_filename', 'safe'),
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
								__CLASS__ . 'Transfer',
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
			'code_name' => 'Code Name',
			'image_filename' => 'фото',
			'order_num' => 'порядковый номер',
			'changefreq' => 'changefreq (sitemap)',
			'priority' => 'priority (sitemap)',
			'created_by' => 'кто создал',
			'modified_by' => 'кто последний редактировал',
			'blocked' => 'Blocked',
			'added_time' => 'Added Time',
			'edited_time' => 'Edited Time',
			'active' => 'Active',
			'photographer' => 'Фотограф',
			'text_youtube' => 'ID видео youtube (если больше одного - разделить иж ";" - точкой с запятой)'
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Authors the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public static function getSectionUrl() {

		if (self::$SectionUrl == '') {
			self::$SectionUrl .= '/' . Base::findControllerAlias('C_members') . '/show';
		}

		return self::$SectionUrl;
	}

	public function getItemUrl() {
		$section = self::getSectionUrl();

		return $section . '/' . $this->code_name . '/';
	}

	public function orderByOrderNum() {

		$this->getDbCriteria()->mergeWith(array(
			'order' => $this->tableAlias . '.order_num DESC'
		));

		return $this;
	}

	protected function afterSave() {
		parent::afterSave();

		if ($_FILES[__CLASS__]['tmp_name']['image'] != null) {
			$filename = $this->id . '_' . substr(md5(time()), 0, 8);

			$images_to_crop = $this->thumbnailsRules();
			$min_width = 400;

			if (isset($this->image_filename) && !empty($images_to_crop['image'])) {
				foreach ($images_to_crop['image'] as $item) {
					$this->fileDelete($item['path'] . $this->image_filename);
				}

				$this->fileDelete(self::PATH_IMAGE_SRC . $this->image_filename);
			}

			$doc = CUploadedFile::getInstance($this, 'image');
			if ($doc) {

				Yii::import('application.components.Image');

				$tmp_image = $doc->getTempName();

				$Image = new Image(true);
				$Image->load($tmp_image);

				if ($Image->getWidth() < $min_width) {
					$this->addError('thumbnail', 'Картинка слишком маленькая. Загрузите, пожалуйста, картинку большего разрешения');

					return false;
				}

				$Image->save($_SERVER['DOCUMENT_ROOT'] . self::PATH_IMAGE_SRC . $filename);

				$this->image_filename = basename(
					$Image->crop(array(490, 300))
						->save($_SERVER['DOCUMENT_ROOT'] . self::IMAGE_490x300 . $filename)
				);

				$this->addUploadedFile($this->image_filename);

				$_FILES[__CLASS__]['tmp_name']['image'] = null;
				$this->update(array('image_filename'));
			}
		}

		if ($_FILES[__CLASS__]['tmp_name']['icon'] != null) {
			$filename = $this->id . '_' . substr(md5(time()), 0, 8);

			$images_to_crop = $this->thumbnailsRules();
			$min_width = 400;

			if (isset($this->icon_filename) && !empty($images_to_crop['icon'])) {
				foreach ($images_to_crop['icon'] as $item) {
					$this->fileDelete($item['path'] . $this->icon_filename);
				}

				$this->fileDelete(self::PATH_ICON_SRC . $this->icon_filename);
			}

			$doc = CUploadedFile::getInstance($this, 'icon');
			if ($doc) {
				Yii::import('application.components.Image');

				$tmp_image = $doc->getTempName();

				$Image = new Image(true);
				$Image->load($tmp_image);

				if ($Image->getWidth() < $min_width) {
					$this->addError('thumbnail', 'Картинка слишком маленькая. Загрузите, пожалуйста, картинку большего разрешения');

					return false;
				}

				$Image->save($_SERVER['DOCUMENT_ROOT'] . self::PATH_ICON_SRC . $filename);
				$this->icon_filename = basename($Image->crop(array(400, 500))->save($_SERVER['DOCUMENT_ROOT'] . self::IMAGE_400x500 . $filename));

				$this->addUploadedFile($this->icon_filename);

				$_FILES[__CLASS__]['tmp_name']['icon'] = null;
				$this->update(array('icon_filename'));
			}
		}
		//-----------------------------------------------------
		if (isset($_POST['Thumbnail']) && ($this->image_filename != null || $this->icon_filename != null)) {
			foreach ($_POST['Thumbnail'] as $type => $item) {
				if (count($item) > 0) {
					foreach ($item as $resolution => $rules) {
						// Ищем исходник иконки
						$img = null;
						$src = null;

						if ($type == 'image') {
							$src = self::PATH_IMAGE_SRC . $this->image_filename;
							$img = $this->image_filename;
						} else if ($type == 'icon') {
							$src = self::PATH_ICON_SRC . $this->icon_filename;
							$img = $this->icon_filename;
						}

						if ($img != null && file_exists($_SERVER['DOCUMENT_ROOT'] . $src)) {

							Yii::import('application.components.Image');
							$Image = new Image(true);
							$Image->load($_SERVER['DOCUMENT_ROOT'] . $src);

							// Ищем область выделения
							$selection = $_POST['Thumbnail'][$type][$resolution]['selection'];

							if ($selection == '0;0;805;537') {
								$selection = '';
							} else if (empty($selection) && isset($rules['selection'])) {
								$selection = $_POST['Thumbnail'][$rules['selection']]['selection'];
							}

							// Область есть, значит пережимали или иконку или ее родителя
							if (!empty($selection)) {

								$selection = explode(';', $selection);
								if (count($selection) != 4) {
									$selection = null;
								}

								$Image->select($selection);
							} else {
								continue;
							}

							// Определяем конечный размер иконки
							list($width, $height) = explode('x', $resolution);
							if (empty($height)) {
								$size = array('w', intval($width));
							} else if (empty($width)) {
								$size = array('h', intval($height));
							} else {
								$size = array(intval($width), intval($height));
							}

							$thumbnailsRules = $this->thumbnailsRules();

							// Определяем метод сжатия
							if ($thumbnailsRules[$type][$resolution]['method'] == 'scale') {
								$Image->scale($size);
							} else {
								$Image->crop($size);
							}

							// Сохранение происходит только если
							// есть исходник и
							// есть область выделения

							$filename = explode('.', $img);
							$Image->save($_SERVER['DOCUMENT_ROOT'] . $thumbnailsRules[$type][$resolution]['path'] . $filename[0]);
						}
						// end if image isset
					} // end foreach
				}
			} // end foreach
		}

		return true;
	}

	public static function getItems($page = 1, $limit = 6) {
		// общее
		$criteria = new CDbCriteria;
		$criteria->select = 't.*';
		$criteria->condition = 't.active = 1 AND image_filename != ""';

		$offset = (($page - 1) * $limit);

		$criteria->limit = $limit;
		$criteria->offset = $offset;

		$items = self::model()->with('transfer:nameNoEmpty')->orderByOrderNum()->findAll($criteria);

		return $items;
	}

	public function updateLikes() {
		$count_votes = MembersLikes::getCount($this->id);
		$this->order_num = $count_votes['count'];
		$this->update(array('order_num'));

		return $count_votes;
	}
}
