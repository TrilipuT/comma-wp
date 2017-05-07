<?php

/**
 * This is the model class for table "comma_article".
 *
 * The followings are the available columns in table 'comma_article':
 * @property integer $id
 * @property string $code_name
 * @property integer $blog
 * @property integer $rubric_id
 * @property string $datetime
 * @property string $color
 * @property string $image_filename
 * @property string $icon_filename
 * @property integer $views_num
 * @property integer $comments_num
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
class Article extends BaseModel {
	public static $SectionUrl,
		$SectionBlogUrl;
	const    PATH_IMAGE_SRC = '/graphics/article/img/src/',
		PATH_IMAGE = '/graphics/article/img/',
		PATH_ICON_SRC = '/graphics/article/icon/src/',
		PATH_ICON = '/graphics/article/icon/',
		PATH_ICON_MINI = '/graphics/article/icon/mini/',
		PATH_ICON_SMALL = '/graphics/article/icon/small/',
		PATH_IMAGE_GIF = '/graphics/article/gif/',
		IMAGE488x423 = '/graphics/article/img/488x423/',
		ICON150x150 = '/graphics/article/icon/150x150/',
		ICON480x480 = '/graphics/article/icon/480x480/',
		IMAGE1050 = '/graphics/article/img/1050x/',
		PATH_SHARE_IMAGE = '/graphics/article/share_img/';
	public $image_delete = 0,
		$image,
		$icon_delete = 0,
		$icon,
		$gif_delete = 0,
		$gif,
		$update = false;
	public $authors;
	public $transfer_type = true;
	public $main_title;

	public function init() {

		parent::init();

		if ($this->isNewRecord) {
			$this->datetime = date('Y-m-d H:i:s');
		}

		return true;
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return parent::tablePrefix() . 'article';
	}

	public function thumbnailsRules() {
		return array('image' => array('700x' => array('method' => 'scale', 'canPinch' => true, 'path' => self::PATH_IMAGE),
									  '488x423' => array('method' => 'scale', 'canPinch' => true, 'path' => self::IMAGE488x423),
									  '1080x' => array('method' => 'scale', 'canPinch' => true, 'path' => self::IMAGE1050)
		),
					 'icon' => array('488x423' => array('method' => 'scale', 'canPinch' => true, 'path' => self::PATH_ICON),
									 '190x' => array('method' => 'scale', 'canPinch' => true, 'path' => self::PATH_ICON_SMALL),
									 '80x80' => array('method' => 'scale', 'canPinch' => true, 'path' => self::PATH_ICON_MINI),
									 '150x150' => array('method' => 'scale', 'canPinch' => true, 'path' => self::ICON150x150),
									 '480x480' => array('method' => 'scale', 'canPinch' => true, 'path' => self::ICON480x480))
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('code_name', 'required'),
			array('blog, rubric_id, views_num, comments_num, order_num, created_by, modified_by, blocked, active', 'numerical', 'integerOnly' => true),
			array('code_name', 'length', 'max' => 255),
			array('color, changefreq', 'length', 'max' => 10),
			array('image_filename, icon_filename', 'length', 'max' => 50),
			array('priority', 'length', 'max' => 3),
			array('shareimage_delete, gif_delete, gif, image_delete, image, SectionUrl, icon, icon_delete, update, authors', 'default'),

			array('id, share_image, gallery_id, interview, gif_filename, on_main, light, code_name, bloger_id, blog, rubric_id, datetime, color, image_filename, icon_filename, views_num,
					comments_num, order_num, changefreq, priority, created_by, modified_by, blocked, added_time, edited_time, active', 'safe'), //, 'on'=>'search'
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'bloger' => array(self::BELONGS_TO, 'Blogers', 'bloger_id'),
			'tags' => array(self::HAS_MANY, 'ArticleHasTegs', 'article_id'),
			'rubric' => array(self::BELONGS_TO, 'Rubrics', 'rubric_id'),
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
			'blog' => 'Blog',
			'rubric_id' => 'рубрика',
			'datetime' => 'дата публикации',
			'color' => 'цвет',
			'image_filename' => 'фото в статье',
			'icon_filename' => 'иконка превью',
			'views_num' => 'количество просмотров',
			'comments_num' => 'количество коментов',
			'order_num' => 'порядковый номер',
			'changefreq' => 'changefreq (sitemap)',
			'priority' => 'priority (sitemap)',
			'created_by' => 'кто создал',
			'modified_by' => 'кто последний редактировал',
			'blocked' => 'Blocked',
			'added_time' => 'Added Time',
			'edited_time' => 'Edited Time',
			'active' => 'Active',
			'on_main' => 'выводить на главную',
			'light' => 'светлый блок в статье',
			'interview' => 'интервью'
		);
	}

	public function withRubric($id) {

		$this->getDbCriteria()->mergeWith(array(
			'condition' => $this->tableAlias . '.rubric_id = :rubric_id',
			'params' => array(':rubric_id' => $id)
		));

		return $this;
	}

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	//----------------------------------------------------------------------------------------
	public static function getSectionUrl() {

		if (self::$SectionUrl == '') {
			if (Yii::app()->language != 'ru') {
				self::$SectionUrl = '/' . Yii::app()->language;
			}

			self::$SectionUrl .= '/' . Base::findControllerAlias('C_articles');
		}

		return self::$SectionUrl;
	}

	public static function getSectionBlogUrl() {

		if (self::$SectionBlogUrl == '') {

			if (Yii::app()->language != 'ru') {
				self::$SectionBlogUrl = '/' . Yii::app()->language;
			}

			self::$SectionBlogUrl .= '/' . Base::findControllerAlias('C_blogs');
		}

		return self::$SectionBlogUrl;
	}

	public function getItemUrl() {
		if (!$this->blog) {
			return self::getSectionUrl() . '/' . $this->code_name; //.'.html'
		} else {
			return self::getSectionBlogUrl() . '/' . $this->bloger->code_name . '/' . $this->code_name; //.'.html'
		}
	}

	//----------------------------------------------------------------------------------------
	public static function recountComments($id) {

		$Article = Article::model()->findByPk($id);
		if ($Article) {
			$Article->update = true;
			$Article->save();
		}
	}

	protected function beforeSave() {

		parent::beforeSave();

		if ($this->image_delete == 1 && $this->image_filename != '') {
			$this->fileDelete(self::PATH_IMAGE . $this->image_filename);
			$this->fileDelete(self::PATH_IMAGE_SRC . $this->image_filename);
			$this->image_filename = '';
		}

		if ($this->icon_delete == 1 && $this->icon_filename != '') {
			$this->fileDelete(self::PATH_ICON . $this->icon_filename);
			$this->fileDelete(self::PATH_ICON_SRC . $this->icon_filename);
			$this->fileDelete(self::PATH_ICON_SMALL . $this->icon_filename);
			$this->fileDelete(self::PATH_ICON_MINI . $this->icon_filename);
			$this->icon_filename = '';
		}

		if ($this->update) {
			$sql = 'UPDATE ' . $this->tableName() . ' AS t
						SET t.`comments_num` = 
	  						(SELECT COUNT(*) FROM ' . parent::tablePrefix() . 'comment AS com
	  								WHERE com.`article_id` = t.id)
	  					WHERE t.id = :id';

			Yii::app()->db->createCommand($sql)->bindParam(":id", $this->id, PDO::PARAM_STR)->execute();
			//------------------------------------------
			$sql = 'SELECT t.comments_num FROM ' . $this->tableName() . ' AS t WHERE t.id = :id';
			$dataReader = Yii::app()->db->createCommand($sql)->bindParam(":id", $this->id, PDO::PARAM_STR)->query();
			$row = $dataReader->read();
			$this->comments_num = $row['comments_num'];
		}

		return true;
	}

	protected function afterSave() {

		parent::afterSave();

		if (!$this->update) {

			if ($_FILES[__CLASS__]['tmp_name']['image'] != null) {

				if ($this->image_filename != '') {
					$this->fileDelete(self::PATH_IMAGE . $this->image_filename);
					$this->fileDelete(self::PATH_IMAGE_SRC . $this->image_filename);
				}

				$doc = CUploadedFile::getInstance($this, 'image');
				if ($doc) {

					Yii::import('application.components.Image');

					$tmp_image = $doc->getTempName();

					$Image = new Image();
					$Image->load($tmp_image);

					$width = 700;
					if ($Image->getWidth() < $width) {
						$this->addError('image_filename', 'Картинка [image_filename] слишком маленькая. Загрузите,
						                                    пожалуйста, картинку большего разрешения (минимальная ширина 700px)');

						return false;
					}

					$Image->save($_SERVER['DOCUMENT_ROOT'] . self::PATH_IMAGE_SRC . $this->id);

					$this->image_filename = basename($Image->scale(array('w', $width))
						->save($_SERVER['DOCUMENT_ROOT'] . self::PATH_IMAGE . $this->id));

					$this->addUploadedFile($this->image_filename);

					$_FILES[__CLASS__]['tmp_name']['image'] = null;
					$this->update(array('image_filename'));
				}
			}

			if ($_FILES[__CLASS__]['tmp_name']['icon'] != null) {

				if ($this->icon_filename != '') {
					$this->fileDelete(self::PATH_ICON . $this->icon_filename);
					$this->fileDelete(self::PATH_ICON_SRC . $this->icon_filename);
					$this->fileDelete(self::PATH_ICON_SMALL . $this->icon_filename);
					$this->fileDelete(self::PATH_ICON_MINI . $this->icon_filename);
				}

				$doc = CUploadedFile::getInstance($this, 'icon');
				if ($doc) {

					Yii::import('application.components.Image');

					$tmp_image = $doc->getTempName();

					$Image = new Image();
					$Image->load($tmp_image);

					$width = 400;
					$height = 200;
					if ($Image->getWidth() < $width || $Image->getHeight() < $height) {
						$this->addError('icon_filename', 'Картинка [icon_filename] слишком маленькая. Загрузите, пожалуйста, картинку большего разрешения  (минимальный размер 400х200)');

						return false;
					}

					$Image->save($_SERVER['DOCUMENT_ROOT'] . self::PATH_ICON_SRC . $this->id);
					$Image->crop(array(488, 423))->save($_SERVER['DOCUMENT_ROOT'] . self::PATH_ICON . $this->id);
					$Image->crop(array(80, 80))->save($_SERVER['DOCUMENT_ROOT'] . self::PATH_ICON_MINI . $this->id);

					$this->icon_filename = basename($Image->scale(array('w', 190))->save($_SERVER['DOCUMENT_ROOT'] . self::PATH_ICON_SMALL . $this->id));

					$this->addUploadedFile($this->icon_filename);

					$_FILES[__CLASS__]['tmp_name']['icon'] = null;
					$this->update(array('icon_filename'));
				}
			}

			//echo '<pre>'; var_dump($_POST['Thumbnail']); echo '</pre>'; exit;
			//--------------------------------------------------------------------------------------------------------------------------------------
			if (isset($_POST['Thumbnail']) && ($this->image_filename != null || $this->icon_filename != null)) {

				foreach ($_POST['Thumbnail'] as $type => $item) {

					if (count($item) > 0) {
						foreach ($item as $resolution => $rules) {

							//if ($_POST['Thumbnail'][$resolution]['delete'] == 1)
							//$this->deleteThumbnail($resolution);

							// Ищем исходник иконки
							if ($type == 'image') {
								$src = self::PATH_IMAGE_SRC . $this->image_filename;
								$img = $this->image_filename;
							} else {
								$src = self::PATH_ICON_SRC . $this->icon_filename;
								$img = $this->icon_filename;
							}

							if ($img != null && file_exists($_SERVER['DOCUMENT_ROOT'] . $src)) {

								Yii::import('application.components.Image');
								$Image = new Image();
								$Image->load($_SERVER['DOCUMENT_ROOT'] . $src);

								// Ищем область выделения
								$selection = $_POST['Thumbnail'][$type][$resolution]['selection'];

								if ($selection == '0;0;805;537') {
									$selection = '';
								} else if (empty($selection) && isset($rules['selection'])) {
									$selection = $_POST['Thumbnail'][$rules['selection']]['selection'];
								}

								//var_dump($selection);
								// Область есть, значит пережимали или иконку или ее родителя
								if (!empty($selection)) {

									$selection = explode(';', $selection);
									if (count($selection) != 4) {
										$selection = null;
									}

									$Image->select($selection);
								}

								// Определяем конечный размер иконки
								$size = array();
								list($width, $height) = explode('x', $resolution);
								if (empty($height)) {
									$size = array('w', intval($width));
								} else if (empty($width)) {
									$size = array('h', intval($height));
								} else {
									$size = array(intval($width), intval($height));
								}

								//var_dump($size, $this->thumbnailsRules[$resolution]['method'], $this->thumbnailsRules[$resolution]['path']); echo '<br>';

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
								$Image->save($_SERVER['DOCUMENT_ROOT'] . $thumbnailsRules[$type][$resolution]['path'] . $this->id);

							}// end if image isset  
						} // end foreach  
					}
				}  // end foreach
			}
			//---------------------------------------------------------------------------------------------------------
			ArticleHasAuthors::model()->deleteAll('article_id = :article_id', array(':article_id' => $this->id));
			//-------------------------------
			if (count($this->authors) > 0) {
				foreach ($this->authors as $idt) {

					$ArticleHasAuthors = new ArticleHasAuthors();
					$ArticleHasAuthors->authors_id = $idt;
					$ArticleHasAuthors->article_id = $this->id;

					$ArticleHasAuthors->save();
				}//end foreach
			}

			//добавление
			$postnewTagsTransfer = Yii::app()->request->getPost('newTagsTransfer');
			if (count($postnewTagsTransfer) > 0) {

				foreach ($postnewTagsTransfer as $tagData) {

					if ($tagData['ru_name'] == null || $tagData['ua_name'] == null) {
						continue;
					}

					//var_dump($tagData);
					$TagsTransferRu = TagsTransfer::model()->find('language_id = 1 AND name = :name', array(':name' => $tagData['ru_name']));
					$TagsTransferUa = TagsTransfer::model()->find('language_id = 2 AND name = :name', array(':name' => $tagData['ua_name']));

					if (!$TagsTransferRu || !$TagsTransferUa) {

						$Tags = new Tags();
						$Tags->save();

						$TagsTransferUa = new TagsTransfer();
						$TagsTransferUa->parent_id = $Tags->id;
						$TagsTransferUa->language_id = 2;
						$TagsTransferUa->name = $tagData['ua_name'];
						$TagsTransferUa->save();

						$TagsTransferRu = new TagsTransfer();
						$TagsTransferRu->parent_id = $Tags->id;
						$TagsTransferRu->language_id = 1;
						$TagsTransferRu->name = $tagData['ru_name'];
						$TagsTransferRu->save();

					}

					$ArticleHasTegs = new ArticleHasTegs();
					$ArticleHasTegs->article_id = $this->id;
					$ArticleHasTegs->teg_id = $TagsTransferRu->parent_id;
					$ArticleHasTegs->save();
				}

			}

		}

		return true;
	}

	protected function beforeDelete() {

		parent::beforeDelete();

		ArticleHasAuthors::model()->deleteAll('article_id = :article_id', array(':article_id' => $this->id));
		ArticleHasTegs::model()->deleteAll('article_id = :article_id', array(':article_id' => $this->id));
		Comment::model()->deleteAll('article_id = :article_id', array(':article_id' => $this->id));

		return true;
	}

	protected function afterDelete() {

		parent::afterDelete();

		if ($this->gif_filename != '') {
			$this->fileDelete(self::PATH_IMAGE_GIF . $this->gif_filename);
			$this->gif_filename = '';
		}

		if ($this->image_filename != '') {
			$this->fileDelete(self::PATH_IMAGE . $this->image_filename);
			$this->fileDelete(self::PATH_IMAGE_SRC . $this->image_filename);
		}

		if ($this->icon_filename != '') {
			$this->fileDelete(self::PATH_ICON . $this->icon_filename);
			$this->fileDelete(self::PATH_ICON_SRC . $this->icon_filename);
			$this->fileDelete(self::PATH_ICON_SMALL . $this->icon_filename);
			$this->fileDelete(self::PATH_ICON_MINI . $this->icon_filename);
		}

		return true;
	}

	public function blog($status) {

		if ($status == 1) {
			$this->getDbCriteria()->mergeWith(array(
				'condition' => $this->tableAlias . '.blog = 1 AND bloger_id > 0'
			));
		} else {
			$this->getDbCriteria()->mergeWith(array(
				'condition' => $this->tableAlias . '.blog = 0'
			));
		}

		return $this;
	}

	public static function getItemsForMain($user_id = 0, $notId = 0, $page = 1, $limit = 9, $offset = 0) { //$limit = $limit-4;

		$rubrics = Rubrics::model()->published()->findAll();
//		if ( $user_id ) {
//       			
//		} else {
//			$rubrics = Rubrics::model()->published()->findAll("is_subsite = 0");
//		}
		$rubrics_ids = array();
		foreach ($rubrics as $r) {
			$rubrics_ids[] = $r->id;
		}
		// for blogs
		$rubrics_ids[] = 0;

		// общее
		$criteria = new CDbCriteria;
		$criteria->select = 't.*';
		$criteria->condition = 't.active = 1 AND t.datetime <= NOW() AND t.on_main = 1';
		$criteria->addInCondition("rubric_id", $rubrics_ids);

		if ($notId > 0) {

			$criteria->addCondition("t.id != :id");
			$criteria->params += array(":id" => $notId);
		}

		if ($user_id > 0) {

			$ids = CHtml::listData(ArticleHasAuthors::model()->withAuthors($user_id)->findAll(), 'id', 'article_id');
			if ($ids) {
				$criteria->condition .= ' AND t.id IN (' . implode(',', $ids) . ')';
			} else {
				$criteria->condition .= ' AND t.id = 0';
			}
		}

		$total = Article::model()->with('transfer:nameNoEmpty')->orderByDateDesc()->count($criteria);     //
		$_offset = (($page - 1) * $limit) + $offset;

		$criteria->limit = $limit;
		$criteria->offset = $_offset;

		$items = Article::model()->with('transfer:nameNoEmpty')->orderByDateDesc()->findAll($criteria);  //->with('transfer:nameNoEmpty')
		$itemsCount = count($items);
		$total_pages = ceil($total / $limit);

		$remains = 0;

		if ($total_pages > 1) {
			$remains = $total - (($page * $limit) + 1);
		}

		$result = array('total' => $total,
						'total_pages' => $total_pages,
						'itemsCount' => $itemsCount,
						'page' => $page,
						'remains' => $remains,
						'items' => $items
		);

		return $result;
	}

	public static function getItems(array $rubric_id, $bloger = 0, $page = 1, $limit = 8) {

		// общее
		$criteria = new CDbCriteria;
		$criteria->select = 't.*';
		$criteria->condition = 't.active = 1 AND t.datetime <= NOW() ';

		/*
		if($notId != ''){

			$criteria->addCondition("t.id != :id");
			$criteria->params += array(":id" => $notId);
		} */
		if ($bloger > 0) {

			$criteria->condition .= ' AND blog = 1 AND t.bloger_id = :bloger_id';
			$criteria->params += array(":bloger_id" => $bloger);

		} else {
			if (count($rubric_id) > 0) {
				$criteria->condition .= ' AND blog = 0 AND t.rubric_id IN(' . implode(',', $rubric_id) . ')';
				//$criteria->params 	+= array(":rubric_id" => implode(',', $rubric_id));
			}
		}

		$total = Article::model()->with('transfer:nameNoEmpty')->orderByDateDesc()->count($criteria);
		$offset = (($page - 1) * $limit);

		$criteria->limit = $limit;
		$criteria->offset = $offset;

		$items = Article::model()->with('transfer:nameNoEmpty')->orderByDateDesc()->findAll($criteria);
		$itemsCount = count($items);

		$total_pages = ceil($total / $limit);
		$remains = 0;

		if ($total_pages > 1) {
			$remains = $total - ($page * $limit);
		}

		$result = array('total' => $total,
						'total_pages' => $total_pages,
						'itemsCount' => $itemsCount,
						'page' => $page,
						'remains' => $remains,
						'q' => $q,
						'items' => $items
		);

		return $result;
	}

	public function searchByKeywords($k = '') {
		$k = mb_strtolower($k, 'utf-8');

		$params = array();

		$tmp_condition = array();
		$tmp_condition[] = '(transfer.name LIKE :keys OR transfer.description LIKE :keys)';
		$params += array(':keys' => '%' . $k . '%');

		$k = explode(" ", $k);

		$tmp_params = array();
		$i = 0;

		foreach ($k as $kw) {
			//$tmp_condition[] = $this->tableAlias.'.code_name LIKE \'%'.$kw.'%\'';

			$tag = mb_strtolower($tag, 'utf-8');
			$TagsTransfer = Tags::findByName($kw);
			if ($TagsTransfer) {
				$tmp_condition[] = '(transfer.name LIKE :key' . $i . ' OR transfer.description LIKE :key' . $i . ' OR tags.teg_id = ' . $TagsTransfer->parent_id . ')';
			} else {
				$tmp_condition[] = '(transfer.name LIKE :key' . $i . ' OR transfer.description LIKE :key' . $i . ')';
			}

			$params += array(':key' . $i => '%' . $kw . '%');
			$i++;
		}

		$criteria = new CDbCriteria;
		$criteria->select = 't.*';
		$criteria->condition = implode(' OR ', $tmp_condition);
		$criteria->params = $params;
		$criteria->join = ' LEFT JOIN ' . ArticleHasTegs::model()->tableName() . ' tags ON t.id = tags.article_id';
		$criteria->with = array('transfer');

		$this->getDbCriteria()->mergeWith($criteria);

		return $this;
	}

	public function searchByTag($tag = '', $status = true) {
		$tag = mb_strtolower($tag, 'utf-8');
		$TagsTransfer = Tags::findByName($tag);

		if ($TagsTransfer) {
			$this->getDbCriteria()->mergeWith(array(
				'with' => array(
					'tags' => array(
						'joinType' => 'RIGHT JOIN',
						'condition' => 'tags.teg_id = ' . $TagsTransfer->parent_id,
					),
				)
			));
		} else if ($status) { //делаем так что б ничего не нашло
			$this->getDbCriteria()->mergeWith(array(
				'with' => array(
					'tags' => array(
						'joinType' => 'RIGHT JOIN',
						'condition' => 'tags.teg_id = -1',
					),
				)
			));
		}

		return $this;
	}

	public function getOther($blog = 0) {

		// общее
		$criteria = new CDbCriteria;
		$criteria->select = 't.*';
		$criteria->condition = 't.active = 1 AND t.datetime <= NOW()'; // AND t.id != :id
		//$criteria->params 	    = array(':id' => $this->id);
		$result = array();
		$countItems = 4;
		$ids = array();

		if (count($this->tags) > 0) {
			$_ids = CHtml::listData($this->tags, 'id', 'teg_id');
			$items = Yii::app()->db->createCommand()
				->select('t.id')
				->from($this->tableName() . ' AS t')
				->rightjoin(ArticleHasTegs::model()->tableName() . ' at', 't.id = at.article_id')
				->where('t.active = 1
				    			AND t.datetime <= NOW() 
				    			AND t.blog = :blog 
				    			AND t.id != :id
				    			AND at.teg_id IN(' . implode(',', $_ids) . ')',
					array(':blog' => $blog, ':id' => $this->id))
				->limit(4)
				->group('at.article_id')
				->order('t.datetime DESC')
				->queryAll();

			if (GetRealIp() == "95.67.66.46") {

				//var_dump($items); exit;

			}

			if (count($items) > 0) {
				foreach ($items as $value) {

					$ids[] = $value['id'];
				}
				$countItems = $countItems - count($items);
			}
		}
		//---------------------------------------------------------------------------
		//var_dump($ids, $countItems);
		//---------------------------------------------------------------------------
		if ($countItems > 0) {

			$dopSql = '';
			if (count($ids) > 0) {
				$dopSql = ' AND t.id NOT IN(' . implode(',', $ids) . ')';
			}
			//----------------------------------
			if ($this->blog == 1) {
				$items = Yii::app()->db->createCommand()
					->select('t.id')
					->from($this->tableName() . ' AS t')
					->where('t.active = 1
							    			AND t.datetime <= NOW() 
							    			AND t.id != :id
							    			AND t.bloger_id = :bloger_id' . $dopSql,
						array(':id' => $this->id, ':bloger_id' => $this->bloger_id))
					->limit($countItems)
					->order('t.datetime DESC')
					->queryAll();

			} else {
				$rubrics_id = Rubrics::getAllRubrics($this->rubric_id);
				if (count($rubrics_id) > 0) {
					$items = Yii::app()->db->createCommand()
						->select('t.id')
						->from($this->tableName() . ' AS t')
						->where('t.active = 1
							    			AND t.datetime <= NOW() 
							    			AND t.id != :id
							    			AND t.rubric_id IN(' . implode(',', $rubrics_id) . ')' . $dopSql,
							array(':id' => $this->id))
						->limit($countItems)
						->order('t.datetime DESC')
						->queryAll();
				}
			}
			//-----------
			if (count($items) > 0) {
				foreach ($items as $value) {
					$ids[] = $value['id'];
				}
			}
		}

		//---------------------------------------------------------------------------
		if (count($ids) > 0) {

			$criteria->condition .= ' AND t.id IN(' . implode(',', $ids) . ')';
			$result = Article::model()->with('transfer:nameNoEmpty')->orderByDateDesc()->limit(4)->findAll($criteria);

			if (GetRealIp() == "95.67.66.46") {

				//var_dump($ids); exit;

			}
		}

		return $result;
	}
	/*
	public function search($q, $page=1, $limit=100){


		$q = mb_strtolower($q, 'utf-8');

		$params = array();

		$tmp_condition = array();
		$tmp_condition[] = '(transfer.name LIKE :keys OR transfer.description LIKE :keys)';
		$params  += array(':keys' => '%'.$q.'%');

		$q = explode(" ", $q);

		$tmp_params = array();
		$i = 0;

		foreach ($q as $kw) {
			//$tmp_condition[] = $this->tableAlias.'.code_name LIKE \'%'.$kw.'%\'';

			$tag = mb_strtolower($tag, 'utf-8');
			$TagsTransfer = Tags::findByName($kw);
			if($TagsTransfer){
				//$tmp_condition[] = '(transfer.name LIKE :key'.$i.' OR transfer.description LIKE :key'.$i.' OR tags.id = '.$TagsTransfer->parent_id.')';
			} else {
				//$tmp_condition[] = '(transfer.name LIKE :key'.$i.' OR transfer.description LIKE :key'.$i.')';
			}


			$params  += array(':key'.$i => '%'.$kw.'%');
			$i++;
		}

		var_dump(implode(' OR ', $tmp_condition));




	}*/
}
