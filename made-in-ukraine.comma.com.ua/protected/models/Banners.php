<?php

class Banners extends BaseModel {
	const    PATH_IMAGE = '/files/banners/img/',
		PATH_BANNER = '/files/banners/';
	public $image_delete = 0,
		$banner_delete = 0,
		$banner,
		$image,
		$update = false;
	public $news_cas,
		$sections;
	public $_getTypeList;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Event the static model class
	 */
	public static function model($className = __CLASS__) {

		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {

		return $this->tablePrefix() . 'banners';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('image_delete, image, banner_delete, banner, sections, news_cas, update', 'default'),
			array('id, banner_code, type, background_color, division, name, date_start, date_end, value, current_url, file_banner, file_banner_static,
					width, url_counter_shows, height, first, target_url, everywhere, views_num, hits_num, added_time, modified_by, created_by, edited_time, order_num, active', 'safe'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'название баннера',
			'date_start' => 'время начала',
			'date_end' => 'время окончания',
			'type' => 'тип баннера',
			'value' => 'вес баннера',
			'file_banner' => 'файл баннера',
			'file_banner_static' => 'файл статической альтернативы флешевого баннера',
			'target_url' => 'ссылка перехода',
			'everywhere' => 'везде',
			'first' => 'на главной',
			'current_url' => 'конкретная ссылка сайта для показа',
			'background_color' => 'цвет фона',
			'banner_code' => 'код вставки баннерной сети',
			'views_num' => 'количество просмотров',
			'hits_num' => 'количество переходов',
			'order_num' => 'порядковый номер',
			'added_time' => 'время добавления',
			'edited_time' => 'время редактирования',
			'active' => 'актив',
			'url_counter_shows' => 'альтернативная ссылка для счетчика просмотров'
		);
	}

	public function getTypeList() {
		if (!empty($this->_getTypeList)) {
			return $this->_getTypeList;
		}

		$this->_getTypeList = array(
			'1' => 'горизонтальный баннер вверху',
			'2' => 'вертикальный баннер справа 1',
			'3' => 'вертикальный баннер справа 2',
			'4' => 'фоновый баннер',
		);

		return $this->_getTypeList;
	}

	protected function beforeSave() {

		parent::beforeSave();

		if ($this->image_delete == 1 && $this->file_banner_static != '') {
			$this->fileDelete(self::PATH_IMAGE . $this->file_banner_static);
			$this->file_banner_static = '';
		}

		if ($this->banner_delete == 1 && $this->file_banner != '') {
			$this->fileDelete(self::PATH_BANNER . $this->file_banner);
			$this->file_banner = '';
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

			$update = array();

			$doc = CUploadedFile::getInstance($this, 'image');
			if ($doc) {
				Yii::import('application.components.Image');
				$tmp_image = $doc->getTempName();
				$Image = new Image();
				$this->file_banner_static = basename($Image->load($tmp_image)->save($_SERVER['DOCUMENT_ROOT'] . self::PATH_IMAGE . $this->id));
				array_push($update, 'file_banner_static');
			}

			$doc = CUploadedFile::getInstance($this, 'banner');
			if ($doc) {
				$doc->saveAs($_SERVER['DOCUMENT_ROOT'] . self::PATH_BANNER . $this->id . "." . $doc->getExtensionName(), true);
				$this->file_banner = $this->id . "." . $doc->getExtensionName();
				array_push($update, 'file_banner');
			}

			unset($_FILES[__CLASS__]);
			if (count($update) > 0) {
				$this->update($update);
			}
		}

		if (!$this->update) {
			BannerHasSections::model()->deleteAll(array('condition' => 'banner_id = :banner_id',
														'params' => array(':banner_id' => $this->id)));
		}

		if (count($this->sections) > 0) {
			foreach ($this->sections as $key => $section_id) {

				$BannerHasSections = new BannerHasSections();
				$BannerHasSections->banner_id = $this->id;
				$BannerHasSections->section_id = $section_id;
				$BannerHasSections->save();
			}
		}

		return true;
	}

	protected function beforeDelete() {

		parent::beforeDelete();

		BannerHasSections::model()->deleteAll(array('condition' => 'banner_id = :banner_id',
													'params' => array(':banner_id' => $this->id)));

		return true;
	}

	protected function afterDelete() {

		parent::afterDelete();

		if ($this->file_banner_static != '') {
			$this->fileDelete(self::PATH_IMAGE . $this->file_banner_static);
			$this->file_banner_static = '';
		}

		if ($this->file_banner != '') {
			$this->fileDelete(self::PATH_BANNER . $this->file_banner);
			$this->file_banner = '';
		}

		return true;
	}

	public function byDateEnd() {

		$this->getDbCriteria()->mergeWith(array(
			'condition' => '(' . $this->tableAlias . '.date_end >= :date_end OR ' . $this->tableAlias . '.`date_end` = "0000-00-00 00:00:00") ',
			'params' => array(':date_end' => date("Y-m-d H:i:s"))
		));

		return $this;
	}

	public function byDateStart() {

		$this->getDbCriteria()->mergeWith(array(
			'condition' => '(' . $this->tableAlias . '.date_start <= :date_start OR ' . $this->tableAlias . '.`date_start` = "0000-00-00 00:00:00") ',
			'params' => array(':date_start' => date("Y-m-d H:i:s"))
		));

		return $this;
	}

	public function orderByValue($t = 'ASC') {

		$this->getDbCriteria()->mergeWith(array(
			'order' => $this->tableAlias . '.`value` ' . $t
		));

		return $this;
	}

	public static function getBanner($banerType, $everywhere = 0, $main_page = 0, $current_url = '', $news_cat_id = 0, $section_id = 0) {
		//тестовый просмотр
		$banner_test_show = (int)Yii::app()->request->cookies['banner_test_show']->value;

		if($banner_test_show > 0){
			$Banners = Banners::model()->findByPk($banner_test_show, 't.type = :type', array(':type' => $banerType));

			if($Banners){
				return $Banners;
			}
		}

		$banners_max_num_first = 1;
		$banners_num_first = 0;
		$banners_max_value = 100;
		$_banners = array();

		//---------------------------------------------
		$criteria = new CDbCriteria;
		$criteria->alias = 't';
		$criteria->condition = ' (t.file_banner_static != "" OR t.file_banner != "") AND t.type = :type';
		$criteria->params = array(':type' => $banerType);

		if ($everywhere != 0) {
			$criteria->condition .= ' AND t.everywhere = 1';
		}

		if ($main_page != 0) {
			$criteria->condition .= ' AND t.first = 1';
		}

		if ($current_url != '') {
			$criteria->condition .= ' AND t.current_url = :current_url';
			$criteria->params += array(':current_url' => $current_url);
		}

		if ($news_cat_id > 0) {

			$BannerHasNews_cat = BannerHasNews_cat::model()->withNews_cat($news_cat_id)->findAll();
			if ($BannerHasNews_cat) {
				$ids = CHtml::listData($BannerHasNews_cat, 'id', 'banner_id');
				$criteria->condition .= ' AND t.id IN (' . implode(',', $ids) . ')'; // данные из бд так что не екранирую
			} else {
				$criteria->condition .= ' AND t.id = -1';
			}
		}

		if ($section_id > 0) {

			$BannerHasSections = BannerHasSections::model()->withSection($section_id)->findAll();
			if ($BannerHasSections) {
				$ids = CHtml::listData($BannerHasSections, 'id', 'banner_id');
				$criteria->condition .= ' AND t.id IN (' . implode(',', $ids) . ')'; // данные из бд так что не екранирую
			} else {
				$criteria->condition .= ' AND t.id = -1';
			}
		}

		//---------------------------------------------
		$bannersItems = Banners::model()->published()->byDateStart()->byDateEnd()->orderByValue('DESC')->findAll($criteria);
		$banners_num_first = count($bannersItems);
		if ($bannersItems) {
			$banners_max_value = $bannersItems[0]->value;

			$rand_value = 0;
			//Цикл вывода баннеров (get from zib)
			//for ($i = 0; $i < $banners_num_first; $i++) {
			//Начало алгоритма вывода баннеров в приоритете их веса - бесконечный цикл перебора, до момента, пока баннер не будет выведен.
			while (true) {
				//Определяем критерий отбора: нижнюю границу веса выбираемого баннера, которая позволяет выводить баннер с вероятностью, равной его весу
				$rand_value = rand(0, $banners_max_value);
				if ($rand_value <= $banners_max_value) {

					$criteria1 = new CDbCriteria;
					$criteria1->condition = 'value >= :rand_value';
					$criteria1->params = array(':rand_value' => $rand_value);
					$criteria1->order = 'RAND()';
					$criteria1->mergeWith($criteria);

					//Если случайный вес соответствует допустимому - делаем выборку из базы
					$Banners = Banners::model()->published()->byDateStart()->byDateEnd()->find($criteria1);

					if ($Banners) {
						$_banners[] = $Banners->id;
						//Если баннер успешно прочитан - инкрементируем счетчик его показов

						Yii::app()->db->createCommand('UPDATE comma_banners as t SET t.`views_num` =  t.`views_num`+1 WHERE t.id = ' . $Banners->id)->execute();

						if(!empty($Banners->url_counter_shows)){
							@file_get_contents($Banners->url_counter_shows); // по простому
						} 
						//Переопределяем идентификатор предыдущего баннера, чтобы исключить вывод 2х одинаковых баннеров рядом
						$banner_prev_id = $Banners->id;

						//Если баннер был успешно выведенм на страницу - прерываем бесконечный цикл поиска
						return $Banners;
					}
				}
			}
			//end while
			//}// end for 

		}

		return false;
	}

	public static function getBannerByType($banerType, $news_cat_id = 0, $section_id = 0) {

		//если главная
		if (Yii::app()->request->url == '/') {
			$Banners = Banners::getBanner($banerType, 0, 1, '', 0, 0);
		}

		// конкретная ссылка
		if (!$Banners) {
			$thisUrl = 'http://' . $_SERVER['SERVER_NAME'] .  $_SERVER['REQUEST_URI'];
			$Banners = Banners::getBanner($banerType, 0, 0, $thisUrl, 0, 0);
		}

		//категррия
		if (!$Banners) {
			if ($news_cat_id != '') {
				$Banners = Banners::getBanner($banerType, 0, 0, '', $news_cat_id, 0);
			}
		}

		//раздел
		if (!$Banners) {
			if ($section_id > 0) {
				$Banners = Banners::getBanner($banerType, 0, 0, '', 0, $section_id);
			}
		}

		//везде
		if (!$Banners) {
			$Banners = Banners::getBanner($banerType, 1, 0, '', 0, 0);
		}

		return $Banners;
	}
}
 