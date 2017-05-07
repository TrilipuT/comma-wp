<?php

class SiteController extends FrontEndController {
	public function urlRules() {
		return array(
			array('site/index',
				  'pattern' => '{this}'
			),
			array('site/login',
				  'pattern' => '{this}/login'
			),
		);
	}

	private function mainDomain($page, $r_num) {
		$this->main_page = true;
		$colsArray = array();
		$offset = 0;
		$artNums = 19;

		if ($this->featuresEnabled['nativeAds']) {
			$artNums = 19;
		}

		if (Yii::app()->request->isAjaxRequest) {
			$offset = -2;
			$artNums = 21;
		}

		$articleItems = Article::getItemsForMain(0, 0, $page, $artNums, $offset);
		//--------------------------------------------------------------------------
		$videoItems = Videos::getForMain($page);
		//--------------------------------------------------------------------------

		// TODO: refactor it to Article model
		if ($articleItems['items'] > 0) {
			for ($i = 0; $i < $articleItems['itemsCount']; $i++) {
				$Article = $articleItems['items'][$i];
				if ($Article->rubric->is_subsite) {
					$Article->main_title = $Article->transfer->page_title;
				}
			}
		}

		if (Yii::app()->request->isAjaxRequest) {
			$vidoes = array();

			if (count($videoItems) > 0) {
				foreach ($videoItems as $item) {
					if (!$item['video']) {
						continue;
					}

					if (!empty($item['video']->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'] . Videos::PATH_IMAGE . $item['video']->image_filename)) {
						$vidoes[] = array('cat' => $item['cat']->code_name,
										  'video' => Videos::PATH_IMAGE . $item['video']->image_filename,
										  'title' => $item['video']->transfer->name,
										  'text' => $item['video']->transfer->description);
					}
				}
			}
			//---------------------------------------
			$article = array();
			$lastCol = array();
			$rowsArray = array();

			if ($articleItems['items'] > 0) {

				$row = 0;
				$colView = $r_num; // "0" - 2 елемента в строке, "1" - 3 елемента в строке
				$col = 0;

				for ($i = 0; $i < $articleItems['itemsCount']; $i++) {
					$Article = $articleItems['items'][$i];

					//если это блог и нет блогера то пропускаем или блог первый
					if ($Article->blog && $Article->bloger_id == 0) {
						continue;
					}

					if ($colView == 0) {
						if ($col == 2) {
							$lastCol[] = $Article;
							$row++;
							$col = 0;
							$colView = 1;
							continue;
						}
					} else {
						if ($col == 3) {
							$lastCol[] = $Article;
							$row++;
							$col = 0;
							$colView = 0;
							continue;
						}
					}

					$rowsArray[$row][] = $Article;

					$col++;
				}
				//end foreach
			}

			$article['total_pages'] = $articleItems['total_pages'];
			$article['page'] = $page;
			$article['remains'] = $articleItems['remains'];

			if (count($rowsArray) > 0) {
				$html_center = $this->renderPartial('_article_items2', array('items' => $rowsArray, 'i' => $r_num, 'rows' => $rows), true);
			}

			if (count($lastCol) > 0) {
				$html_right = $this->renderPartial('_article_items', array('items' => $lastCol), true);
			}

			$out = array('success' => 1,
						 'html_center' => $html_center,
						 'html_right' => $html_right,
						 'vidoes' => $vidoes,
						 'remains' => $article['remains']);
			//----------------------------------------------------
			header('Content-type: application/json');
			echo json_encode($out);
			exit;

		} else {

			if ($articleItems['itemsCount'] > 0) {

				$mainArticle = $articleItems['items'][0];
				//если первая блог то меняем местами
				// так как блог не можеи идти друг за другом, то предпологаем что следующий не блог
				if ($mainArticle && $mainArticle->blog) {
					$_Article = $articleItems['items'][1];
					if (!$_Article->blog) {
						$articleItems['items'][1] = $mainArticle;
						$mainArticle = $_Article;
					}
				}

				if ($articleItems['itemsCount'] > 1) {
					$colsArray[2][] = $articleItems['items'][1];

					$col = 0;
					if ($articleItems['itemsCount'] > 2) {
						$ads_skip = 0;
						foreach ($articleItems['items'] as $key => $Article) {
							if ($key <= 1) {
								continue;
							}
							if ($key == 10) {
								break;
							}

							//если это блог и нет блогера то пропускаем
							if ($Article->blog && $Article->bloger_id == 0) {
								continue;
							}

							if ($key == 4) {
								//$col = 0;
							}

							//if ( $this->featuresEnabled['nativeAds'] && $col == 1 && !$ads_skip ) {
							//    $ads_skip = 1; // skipping one article
							//} else {
							$colsArray[$col][] = $Article;
							//}

							$col++;
							if ($col == 4) {
								$col = 0;
							}
						}
						// endforeach
					}
				}

				//блок после полоски видео
				//$articleItems = Article::getItemsForMain(0, 0, $page+1);
				$article = array();
				$lastCol = array();
				$rowsArray = array();
				if ($articleItems['items'] > 10) {

					$row = 0;
					$colView = 0; // "0" - 2 елемента в строке, "1" - 3 елемента в строке
					$col = 0;

					for ($i = 10; $i < $articleItems['itemsCount']; $i++) {
						$Article = $articleItems['items'][$i];

						//foreach ($articleItems['items'] as $key => $Article){

						//если это блог и нет блогера то пропускаем
						if ($Article->blog && $Article->bloger_id == 0) {
							continue;
						}

						if ($colView == 0) {
							if ($col == 2) {
								$lastCol[] = $Article;
								$row++;
								$col = 0;
								$colView = 1;
								continue;
							}
						} else {
							if ($col == 3) {
								$lastCol[] = $Article;
								$row++;
								$col = 0;
								$colView = 0;
								continue;
							}
						}

						$rowsArray[$row][] = $Article;

						$col++;
					}
					//end foreach
				}

				$article['total_pages'] = $articleItems['total_pages'];
				$article['page'] = $page;
				$article['remains'] = $articleItems['remains'];
			}

			//--------------------------------------------------------------------------
			$newsItems = News::model()->with('transfer:nameNoEmpty')->published()->orderByDateDesc()->limit(15)->findAll();
			//--------------------------------------------------------------------------
			$articleGallery = Gallery::model()->published()->orderByDateDesc()->limit(15)->findAll('t.datetime <= NOW() AND in_article = 0');
			//--------------------------------------------------------------------------
			$this->render('index', array('mainArticle' => $mainArticle,
										 'colsArray' => $colsArray,
										 'rowsArray' => $rowsArray,
										 'lastCol' => $lastCol,
										 'newsItems' => $newsItems,
										 'articleGallery' => $articleGallery,
										 'videoItems' => $videoItems,
										 'article' => $article));
		}
	}

	public function actionIndex($page = 1, $r_num = 0) {
		if (Yii::app()->theme == null || Yii::app()->theme->name == 'main') {
			$this->mainDomain($page, $r_num);
		} else if (Yii::app()->theme->name == 'sziget') {
			$this->szigetDomain();
		}
	}

	public function szigetDomain() {
		$this->og_title = 'Я хочу виступити на Sziget Festival 2015!';

		$this->render('index');
	}

	public function actionError() {
		$this->layout = 'error';
		$this->render('error');
	}
}