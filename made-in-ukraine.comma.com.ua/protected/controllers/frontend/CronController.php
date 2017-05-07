<?php

class CronController extends FrontEndController {
	public function actionCreateMainSitemap() {

		$host = 'http://' . $_SERVER['SERVER_NAME'];

		$sectionItems = Section::model()->published()->orderByOrderNum()->onlyDomain()->findAll();
		if ($sectionItems) {

			//Создать xml документ
			$file = new DomDocument('1.0', 'utf-8');

			$urlset = $file->appendChild($file->createElement('urlset'));
			//Создать атрибут xmlns
			$xmlns = $file->createAttribute("xmlns");
			$xmlns->appendChild($file->createTextNode("http://www.sitemaps.org/schemas/sitemap/0.9"));
			$urlset->appendChild($xmlns);

			//главная
			$url = $urlset->appendChild($file->createElement('url'));
			$loc = $url->appendChild($file->createElement('loc'));
			$loc->appendChild($file->createTextNode($host));
			$lastmod = $url->appendChild($file->createElement('lastmod'));
			$lastmod->appendChild($file->createTextNode(date('Y-m-d')));

			$changefreq = $url->appendChild($file->createElement('changefreq'));
			$changefreq->appendChild($file->createTextNode("hourly"));
			$priority = $url->appendChild($file->createElement('priority'));
			$priority->appendChild($file->createTextNode("0.9"));

			$notSection = array("search", "tag", "home", "articles");
			foreach ($sectionItems as $Section) {

				switch ($Section->controller) {
					case 'C_articlesController.php':

						$rubricsItems = Rubrics::model()->published()->orderByOrderNum()->findAll();
						if ($rubricsItems) {
							foreach ($rubricsItems as $Rubrics) {

								$url = $urlset->appendChild($file->createElement('url'));

								$loc = $url->appendChild($file->createElement('loc'));

								$loc->appendChild($file->createTextNode($host . $Rubrics->getItemUrl()));

								$lastmod = $url->appendChild($file->createElement('lastmod'));
								$lastmod->appendChild($file->createTextNode(date('Y-m-d', strtotime($Rubrics->edited_time))));

								$changefreq = $url->appendChild($file->createElement('changefreq'));
								$changefreq->appendChild($file->createTextNode($Rubrics->changefreq));

								$priority = $url->appendChild($file->createElement('priority'));
								$priority->appendChild($file->createTextNode($Rubrics->priority));

								$articlesItems = Article::model()->withRubric($Rubrics->id)->published()->orderByOrderNum()->findAll("t.active = 1 AND t.datetime <= NOW()");

								if ($articlesItems) {
									foreach ($articlesItems as $Article) {

										$url = $urlset->appendChild($file->createElement('url'));

										$loc = $url->appendChild($file->createElement('loc'));

										$loc->appendChild($file->createTextNode($host . $Article->getItemUrl()));

										$lastmod = $url->appendChild($file->createElement('lastmod'));
										$lastmod->appendChild($file->createTextNode(date('Y-m-d', strtotime($Article->edited_time))));

										$changefreq = $url->appendChild($file->createElement('changefreq'));
										$changefreq->appendChild($file->createTextNode($Article->changefreq));

										$priority = $url->appendChild($file->createElement('priority'));
										$priority->appendChild($file->createTextNode($Article->priority));

									}
									// end foreach
								}
							}
							// end foreach
						}

						break;
				}

				if (in_array($Section->code_name, $notSection)) {
					continue;
				}

				$url = $urlset->appendChild($file->createElement('url'));

				$loc = $url->appendChild($file->createElement('loc'));

				$loc->appendChild($file->createTextNode($host . '/' . $Section->code_name));

				$lastmod = $url->appendChild($file->createElement('lastmod'));
				$lastmod->appendChild($file->createTextNode(date('Y-m-d', strtotime($Section->edited_time))));

				$changefreq = $url->appendChild($file->createElement('changefreq'));
				$changefreq->appendChild($file->createTextNode($Section->changefreq));

				$priority = $url->appendChild($file->createElement('priority'));
				$priority->appendChild($file->createTextNode($Section->priority));

				switch ($Section->controller) {
					case 'C_authorsController.php':

						$authorsItems = Authors::model()->with('transfer')->published()->findAll(array('condition' => 't.photographer = 0', 'order' => 'transfer.last_name'));

						if ($authorsItems) {
							foreach ($authorsItems as $Authors) {

								$url = $urlset->appendChild($file->createElement('url'));

								$loc = $url->appendChild($file->createElement('loc'));

								$loc->appendChild($file->createTextNode($host . $Authors->getItemUrl()));

								$lastmod = $url->appendChild($file->createElement('lastmod'));
								$lastmod->appendChild($file->createTextNode(date('Y-m-d', strtotime($Authors->edited_time))));

								$changefreq = $url->appendChild($file->createElement('changefreq'));
								$changefreq->appendChild($file->createTextNode($Authors->changefreq));

								$priority = $url->appendChild($file->createElement('priority'));
								$priority->appendChild($file->createTextNode($Authors->priority));


							}
							// end foreach
						}

						//photographers
						$url = $urlset->appendChild($file->createElement('url'));

						$loc = $url->appendChild($file->createElement('loc'));

						$loc->appendChild($file->createTextNode($host . '/' . $Section->code_name . "/photographers/"));

						$lastmod = $url->appendChild($file->createElement('lastmod'));
						$lastmod->appendChild($file->createTextNode(date('Y-m-d', strtotime($Section->edited_time))));

						$changefreq = $url->appendChild($file->createElement('changefreq'));
						$changefreq->appendChild($file->createTextNode($Section->changefreq));

						$priority = $url->appendChild($file->createElement('priority'));
						$priority->appendChild($file->createTextNode($Section->priority));

						$authorsItems = Authors::model()->published()->with('transfer')->findAll(array('condition' => 't.photographer = 1', 'order' => 'transfer.last_name'));

						if ($authorsItems) {
							foreach ($authorsItems as $Authors) {

								$url = $urlset->appendChild($file->createElement('url'));

								$loc = $url->appendChild($file->createElement('loc'));

								$loc->appendChild($file->createTextNode($host . $Authors->getItemUrl()));

								$lastmod = $url->appendChild($file->createElement('lastmod'));
								$lastmod->appendChild($file->createTextNode(date('Y-m-d', strtotime($Authors->edited_time))));

								$changefreq = $url->appendChild($file->createElement('changefreq'));
								$changefreq->appendChild($file->createTextNode($Authors->changefreq));

								$priority = $url->appendChild($file->createElement('priority'));
								$priority->appendChild($file->createTextNode($Authors->priority));

							}
							// end foreach
						}

						break;
					case 'C_blogsController.php':

						$blogersItems = Blogers::model()->published()->orderByOrderNum()->findAll();
						if ($blogersItems) {
							foreach ($blogersItems as $Blogers) {

								$url = $urlset->appendChild($file->createElement('url'));

								$loc = $url->appendChild($file->createElement('loc'));

								$loc->appendChild($file->createTextNode($host . $Blogers->getItemUrl()));

								$lastmod = $url->appendChild($file->createElement('lastmod'));
								$lastmod->appendChild($file->createTextNode(date('Y-m-d', strtotime($Blogers->edited_time))));

								$changefreq = $url->appendChild($file->createElement('changefreq'));
								$changefreq->appendChild($file->createTextNode($Blogers->changefreq));

								$priority = $url->appendChild($file->createElement('priority'));
								$priority->appendChild($file->createTextNode($Blogers->priority));

								$articlesItems = Article::model()->published()->orderByOrderNum()->findAll("t.active = 1 AND t.datetime <= NOW() AND blog = 1 AND t.bloger_id = :bloger_id", array(":bloger_id" => $Blogers->id));

								if ($articlesItems) {
									foreach ($articlesItems as $Article) {

										$url = $urlset->appendChild($file->createElement('url'));

										$loc = $url->appendChild($file->createElement('loc'));

										$loc->appendChild($file->createTextNode($host . $Article->getItemUrl()));

										$lastmod = $url->appendChild($file->createElement('lastmod'));
										$lastmod->appendChild($file->createTextNode(date('Y-m-d', strtotime($Article->edited_time))));

										$changefreq = $url->appendChild($file->createElement('changefreq'));
										$changefreq->appendChild($file->createTextNode($Article->changefreq));

										$priority = $url->appendChild($file->createElement('priority'));
										$priority->appendChild($file->createTextNode($Article->priority));

									}
									// end foreach
								}

							}
							// end foreach
						}

						break;
					case 'C_galleryController.php':

						$galleryItems = Gallery::model()->published()->findAll("t.active = 1 AND t.datetime <= NOW() AND in_article = 0");

						if ($galleryItems) {
							foreach ($galleryItems as $Gallery) {

								$url = $urlset->appendChild($file->createElement('url'));

								$loc = $url->appendChild($file->createElement('loc'));

								$loc->appendChild($file->createTextNode($host . $Gallery->getItemUrl()));

								$lastmod = $url->appendChild($file->createElement('lastmod'));
								$lastmod->appendChild($file->createTextNode(date('Y-m-d', strtotime($Gallery->edited_time))));

								$changefreq = $url->appendChild($file->createElement('changefreq'));
								$changefreq->appendChild($file->createTextNode($Gallery->changefreq));

								$priority = $url->appendChild($file->createElement('priority'));
								$priority->appendChild($file->createTextNode($Gallery->priority));

							}
							// end foreach
						}

						break;
					case 'C_newsController.php':

						$newsItems = News::model()->published()->orderByDateDesc()->findAll('t.active = 1 AND t.datetime <= NOW()');

						if ($newsItems) {
							foreach ($newsItems as $News) {

								$url = $urlset->appendChild($file->createElement('url'));

								$loc = $url->appendChild($file->createElement('loc'));

								$loc->appendChild($file->createTextNode($host . $News->getItemUrl()));

								$lastmod = $url->appendChild($file->createElement('lastmod'));
								$lastmod->appendChild($file->createTextNode(date('Y-m-d', strtotime($News->edited_time))));

								$changefreq = $url->appendChild($file->createElement('changefreq'));
								$changefreq->appendChild($file->createTextNode($News->changefreq));

								$priority = $url->appendChild($file->createElement('priority'));
								$priority->appendChild($file->createTextNode($News->priority));


							}
							// end foreach
						}
						break;
					case 'C_videosController.php':

						//------------------------------------------------------------------------------------------------------------------
						$categoryItems = VideoCats::model()->published()->orderByOrderNum()->findAll();
						if ($categoryItems) {
							foreach ($categoryItems as $VideoCats) {

								$url = $urlset->appendChild($file->createElement('url'));

								$loc = $url->appendChild($file->createElement('loc'));

								$loc->appendChild($file->createTextNode($host . $VideoCats->getItemUrl()));

								$lastmod = $url->appendChild($file->createElement('lastmod'));
								$lastmod->appendChild($file->createTextNode(date('Y-m-d', strtotime($VideoCats->edited_time))));

								$changefreq = $url->appendChild($file->createElement('changefreq'));
								$changefreq->appendChild($file->createTextNode($VideoCats->changefreq));

								$priority = $url->appendChild($file->createElement('priority'));
								$priority->appendChild($file->createTextNode($VideoCats->priority));

								$videoItems = Videos::model()->withCategory($VideoCats->id)->published()->orderByOrderNum()->findAll();

								if ($videoItems) {
									foreach ($videoItems as $Videos) {

										$url = $urlset->appendChild($file->createElement('url'));

										$loc = $url->appendChild($file->createElement('loc'));

										$loc->appendChild($file->createTextNode($host . $Videos->getItemUrl()));

										$lastmod = $url->appendChild($file->createElement('lastmod'));
										$lastmod->appendChild($file->createTextNode(date('Y-m-d', strtotime($Videos->edited_time))));

										$changefreq = $url->appendChild($file->createElement('changefreq'));
										$changefreq->appendChild($file->createTextNode($Videos->changefreq));

										$priority = $url->appendChild($file->createElement('priority'));
										$priority->appendChild($file->createTextNode($Videos->priority));

									}
									// end foreach
								}
							}
							// end foreach
						}
						break;
				}
				//end switch

			}
			//Сохранить документ в указанное место
			$file->save($_SERVER['DOCUMENT_ROOT'] . '/sitemap.xml');
		}


	}

	public function actionCreateNewsRss() {

		ini_set('memory_limit', '2048M');
		set_time_limit(0);

		$host = 'http://' . $_SERVER['SERVER_NAME'];
		//------------------------------------------------------------------------------------------------------
		$newsItems = News::model()->published()->orderByDateDesc()->only(50)->findAll("t.datetime <= NOW()");

		//Создать xml документ (все новости)
		$file = new DomDocument('1.0', 'utf-8');

		$rss = $file->appendChild($file->createElement('rss'));
		//Создать атрибут version
		$version = $file->createAttribute("version");
		$version->appendChild($file->createTextNode("2.0"));
		$rss->appendChild($version);

		$channel = $rss->appendChild($file->createElement('channel'));

		$title = $channel->appendChild($file->createElement('title'));
		$title->appendChild($file->createTextNode(Yii::t('app', 'rss_news_title')));

		$link = $channel->appendChild($file->createElement('link'));
		$link->appendChild($file->createTextNode($host));

		//$description = $channel->appendChild($file->createElement('description'));
		//$description->appendChild($file->createTextNode(Yii::t('app','rss_news_description')));

		$language = $channel->appendChild($file->createElement('language'));
		$language->appendChild($file->createTextNode(Yii::app()->language));

		$lastBuildDate = $channel->appendChild($file->createElement('lastBuildDate'));
		$lastBuildDate->appendChild($file->createTextNode(date("r")));

		$pubDate = $channel->appendChild($file->createElement('pubDate'));
		$pubDate->appendChild($file->createTextNode(date("r")));

		foreach ($newsItems as $News) {

			$item = $channel->appendChild($file->createElement('item'));

			$title = $item->appendChild($file->createElement('title'));
			$title->appendChild($file->createTextNode($News->transfer->name));

			$link = $item->appendChild($file->createElement('link'));
			$link->appendChild($file->createTextNode($host . $News->getItemUrl()));

			$description = $item->appendChild($file->createElement('description'));
			$description->appendChild($file->createTextNode('<![CDATA[' . $News->transfer->description . ']]>'));

			$pubDate = $item->appendChild($file->createElement('pubDate'));
			$pubDate->appendChild($file->createTextNode(date("r", strtotime($News->datetime))));

			$guid = $item->appendChild($file->createElement('guid'));
			$guid->appendChild($file->createTextNode($host . $News->getItemUrl()));
		}
		//end foreach

		//Сохранить документ в указанное место
		$file->save($_SERVER['DOCUMENT_ROOT'] . '/rss/news_rss.xml');

	}

	public function actionCreateRubricsRss() {

		ini_set('memory_limit', '2048M');
		set_time_limit(0);

		$host = 'http://' . $_SERVER['SERVER_NAME'];
		//------------------------------------------------------------------------------------------------------

		$rubricsItems = Rubrics::model()->published()->orderByOrderNum()->findAll();
		if ($rubricsItems) {
			foreach ($rubricsItems as $Rubrics) {

				$articlesItems = Article::model()->withRubric($Rubrics->id)->published()->orderByOrderNum()->only(50)->findAll("t.datetime <= NOW()");

				if ($articlesItems) {

					//Создать xml документ
					$file = new DomDocument('1.0', 'utf-8');

					$rss = $file->appendChild($file->createElement('rss'));
					//Создать атрибут version
					$version = $file->createAttribute("version");
					$version->appendChild($file->createTextNode("2.0"));
					$rss->appendChild($version);

					$channel = $rss->appendChild($file->createElement('channel'));

					$title = $channel->appendChild($file->createElement('title'));
					$title->appendChild($file->createTextNode($Rubrics->transfer->name));

					$link = $channel->appendChild($file->createElement('link'));
					$link->appendChild($file->createTextNode($host));

					$description = $channel->appendChild($file->createElement('description'));
					$description->appendChild($file->createTextNode($Rubrics->transfer->description));

					$language = $channel->appendChild($file->createElement('language'));
					$language->appendChild($file->createTextNode(Yii::app()->language));

					$lastBuildDate = $channel->appendChild($file->createElement('lastBuildDate'));
					$lastBuildDate->appendChild($file->createTextNode(date("r")));

					$pubDate = $channel->appendChild($file->createElement('pubDate'));
					$pubDate->appendChild($file->createTextNode(date("r")));

					foreach ($articlesItems as $Article) {

						$item = $channel->appendChild($file->createElement('item'));

						$title = $item->appendChild($file->createElement('title'));
						$title->appendChild($file->createTextNode($Article->transfer->name));

						$link = $item->appendChild($file->createElement('link'));
						$link->appendChild($file->createTextNode($host . $Article->getItemUrl()));

						$description = $item->appendChild($file->createElement('description'));
						$description->appendChild($file->createTextNode('<![CDATA[' . $Article->transfer->description . ']]>'));

						$pubDate = $item->appendChild($file->createElement('pubDate'));
						$pubDate->appendChild($file->createTextNode(date("r", strtotime($Article->datetime))));

						$guid = $item->appendChild($file->createElement('guid'));
						$guid->appendChild($file->createTextNode($host . $Article->getItemUrl()));


					}
					// end foreach

					//Сохранить документ в указанное место
					$file->save($_SERVER['DOCUMENT_ROOT'] . '/rss/' . $Rubrics->code_name . '_rss.xml');

				}
			}
			// end foreach
		}
	}

	private function YandexReplace($str) {
		$str = str_replace("<br /><br />", "\n", $str); //перевод строки
		$str = strip_tags($str); //убираем теги
		$str = str_replace(array("&nbsp;", "&ndash;", '&mdash;', '&hellip;', '&rdquo;', '&ldquo;', '&laquo;', '&raquo;', '&times;', '&shy;', '&thinsp;', '&euml;', '&minus;', '&rsquo;', '&euro;', '&deg;', '&uuml;'), array(" ", "-", '-', '...', '&quot;', '&quot;', '&quot;', '&quot;', '×', '', '', '', '-', '&quot;', '€', '°', 'ü'), $str);
		$str = html_entity_decode($str);
		$str = htmlspecialchars($str);
		$str = preg_replace('/\.(?!\.)/', '. ', $str); //пробел после точки
		$str = preg_replace('|([ ]+)|s', ' ', $str); //лишние пробелы
		return ($str);
	}

	public function actionCreateOneRss() {

		ini_set('memory_limit', '2048M');
		set_time_limit(0);

		$host = 'http://' . $_SERVER['SERVER_NAME'];
		//------------------------------------------------------------------------------------------------------
		//Создать xml документ
		$file = new DomDocument('1.0', 'utf-8');
		$rss = $file->appendChild($file->createElement('rss'));
		//Создать атрибут version
		$version = $file->createAttribute("version");
		$version->appendChild($file->createTextNode("2.0"));
		$rss->appendChild($version);

		//Создать xmlns:media
		$xmlns = $file->createAttribute("xmlns:media");
		$xmlns->appendChild($file->createTextNode("http://search.yahoo.com/mrss/"));
		$rss->appendChild($xmlns);

		$channel = $rss->appendChild($file->createElement('channel'));

		$image = $channel->appendChild($file->createElement('image'));

		$_url = $image->appendChild($file->createElement('url'));
		$_url->appendChild($file->createTextNode($host . '/images/header-logo.png'));
		//-------------
		$_title = $image->appendChild($file->createElement('title'));
		$_title->appendChild($file->createTextNode("comma.com.ua"));

		$link = $image->appendChild($file->createElement('link'));
		$link->appendChild($file->createTextNode($host));

		$title = $channel->appendChild($file->createElement('title'));
		$title->appendChild($file->createTextNode("comma.com.ua"));

		$link = $channel->appendChild($file->createElement('link'));
		$link->appendChild($file->createTextNode($host));

		$description = $channel->appendChild($file->createElement('description'));
		$description->appendChild($file->createTextNode("Редакция интернет-журнала Comma — это личности с опытом в медиа, диджитале и музыке, которых объединило стремление создать качественный ресурс со своим особенным духом и характером. Мы хотим, чтобы творческие и думающие люди объединились в дружное сообщество на базе нашего проекта."));

		$language = $channel->appendChild($file->createElement('language'));
		$language->appendChild($file->createTextNode(Yii::app()->language));

		$lastBuildDate = $channel->appendChild($file->createElement('lastBuildDate'));
		$lastBuildDate->appendChild($file->createTextNode(date("r")));

		$pubDate = $channel->appendChild($file->createElement('pubDate'));
		$pubDate->appendChild($file->createTextNode(date("r")));

		$rubricsItems = Rubrics::model()->published()->orderByOrderNum()->findAll();
		if ($rubricsItems) {

			foreach ($rubricsItems as $Rubrics) {

				$articlesItems = Article::model()->withRubric($Rubrics->id)->with('transfer:nameNoEmpty')->published()->orderByDateDesc()->only(50)->findAll("t.datetime <= NOW()");

				if ($articlesItems) {

					foreach ($articlesItems as $Article) {

						$item = $channel->appendChild($file->createElement('item'));

						$title = $item->appendChild($file->createElement('title'));
						$title->appendChild($file->createTextNode($Article->transfer->name));

						$link = $item->appendChild($file->createElement('link'));
						$link->appendChild($file->createTextNode($host . $Article->getItemUrl()));

						$category = $item->appendChild($file->createElement('category'));
						$category->appendChild($file->createTextNode($Rubrics->transfer->name));

						$list = CHtml::listData(ArticleHasAuthors::model()->withArticle($Article->id)->findAll(), 'id', 'authors_id');
						$authorsItems = Authors::model()->published()->orderByOrderNum()->findAllByPk($list);
						if ($authorsItems) {
							foreach ($authorsItems as $Authors) {

								$author = $item->appendChild($file->createElement('author'));
								$author->appendChild($file->createTextNode($Authors->transfer->getName()));
							}
						}

						$pubDate = $item->appendChild($file->createElement('pubDate'));
						$pubDate->appendChild($file->createTextNode(date("r", strtotime($Article->datetime))));

						$description = $item->appendChild($file->createElement('description'));
						$description->appendChild($file->createTextNode(strip_tags($Article->transfer->description)));

						$content = "";
						$status = preg_match_all('|###GALLERY_([0-9]+)###|', $Article->transfer->content, $mathces);
						if ($status) {
							foreach ($mathces[0] as $key => $mathc) {
								$content = str_replace($mathc, "", $Article->transfer->content);
							}
						} else {
							$content = $Article->transfer->content;
						}

						$content = strip_tags($content);

						$fulltext = $item->appendChild($file->createElement('fulltext'));
						$fulltext->appendChild($file->createTextNode($content));

						//--------------------
						$og_image = "";
						if (!empty($Article->share_image) && file_exists($_SERVER['DOCUMENT_ROOT'] . Article::PATH_SHARE_IMAGE . $Article->share_image)) {
							$og_image = 'http://' . $_SERVER['HTTP_HOST'] . Article::PATH_SHARE_IMAGE . $Article->share_image;
						} else if (!empty($Article->image_filename) && file_exists($_SERVER['DOCUMENT_ROOT'] . Article::PATH_IMAGE . $Article->image_filename)) {
							$og_image = 'http://' . $_SERVER['HTTP_HOST'] . Article::PATH_IMAGE_SRC . $Article->image_filename;
						}
						if ($og_image) {

							list($_width, $_height, $_type, $_attr) = getimagesize($og_image);

							$type = $file->createAttribute("type");
							$type->appendChild($file->createTextNode(image_type_to_mime_type($_type)));

							$width = $file->createAttribute("width");
							$width->appendChild($file->createTextNode($_width));

							$height = $file->createAttribute("height");
							$height->appendChild($file->createTextNode($_height));

							$image_url = $file->createAttribute("url");
							$image_url->appendChild($file->createTextNode($og_image));

							$media_content = $item->appendChild($file->createElement('media:content'));
							$media_content->appendChild($image_url);
							$media_content->appendChild($type);
							$media_content->appendChild($height);
							$media_content->appendChild($width);
						}

						$guid = $item->appendChild($file->createElement('guid'));
						$guid->appendChild($file->createTextNode($host . $Article->getItemUrl()));

					}
					// end foreach

				}
			}
			// end foreach
		}

		$newsItems = News::model()->with('transfer:nameNoEmpty')->published()->orderByDateDesc()->only(50)->findAll("t.datetime <= NOW()");
		if ($newsItems) {
			foreach ($newsItems as $News) {

				$item = $channel->appendChild($file->createElement('item'));

				$title = $item->appendChild($file->createElement('title'));
				$title->appendChild($file->createTextNode($News->transfer->name));

				$link = $item->appendChild($file->createElement('link'));
				$link->appendChild($file->createTextNode($host . $News->getItemUrl()));

				$category = $item->appendChild($file->createElement('category'));
				$category->appendChild($file->createTextNode("Новости"));

				$description = $item->appendChild($file->createElement('description'));
				$description->appendChild($file->createTextNode(strip_tags($News->transfer->description)));

				$content = "";
				$status = preg_match_all('|###GALLERY_([0-9]+)###|', $News->transfer->content, $mathces);
				if ($status) {
					foreach ($mathces[0] as $key => $mathc) {
						$content = str_replace($mathc, "", $News->transfer->content);
					}
				} else {
					$content = $News->transfer->content;
				}

				$content = strip_tags($content);

				$fulltext = $item->appendChild($file->createElement('fulltext'));
				$fulltext->appendChild($file->createTextNode($content));

				$pubDate = $item->appendChild($file->createElement('pubDate'));
				$pubDate->appendChild($file->createTextNode(date("r", strtotime($News->datetime))));

				$guid = $item->appendChild($file->createElement('guid'));
				$guid->appendChild($file->createTextNode($host . $News->getItemUrl()));
			}
			//end foreach
		}
		//Сохранить документ в указанное место
		$file->save($_SERVER['DOCUMENT_ROOT'] . '/rss/rss.xml');
	}

	public function actionCollectLikes() {

		$membersItems = Members::model()->findAll('image_filename != ""'); //published()->

		if ($membersItems) {
			foreach ($membersItems as $Members) {
				$Members->updateLikes();
			}
		}

		exit;
		$membersItems = Members::model()->published()->findAll('image_filename != ""');

		if ($membersItems) {
			$host = 'http://sziget.comma.com.ua/members';

			foreach ($membersItems as $Members) {
				if ($Members->id == 1) {
					$url = 'http://sziget.comma.com.ua/members/5%C2%A0vymir/';
				} else {
					$url = $host . '/' . $Members->code_name . '/';
				}
				/*
								$api_id = '4786964';
								$secret_key = 'AmBAmQETf3J7OpNEgovS';

								$VK = new VKapi($api_id, $secret_key);
								$resp = $VK->api('likes.getList', array(
									'type' => 'sitepage',
									'owner_id' => $api_id,
									'item_id' => 321,
									'page_url' => $url));

								$count_vk_likes = $resp['response']['count'];
				*/
				$c1 = Likes::vkShares($url);
				$c2 = Likes::fb_likes($url);

				$Members->order_num = $c1 + $c2;
				$Members->update(array('order_num'));
			}

		}

		exit;

		//$c1 = Likes::vkShares($url);
		$c2 = Likes::fb_likes($url);

		var_dump($c1, $c2);
		//http://vk.com/dev/likes.getList

		$res = file_get_contents('https://api.vk.com/method/likes.getList?type=sitepage&owner_id=4786964&page_url=' . $url);
		$resp = json_decode($res, true);
		var_dump($resp);
		//$url = 'http://test.comma.com.ua/members/gruppa_2/';
		$url = 'http://test.comma.com.ua/members/pervyy/';

		//http://vk.com/share.php?act=count&index=1&url=http://test.comma.com.ua/members/pervyy/

		$api_id = '4786964';
		$secret_key = 'AmBAmQETf3J7OpNEgovS';

		$VK = new VKapi($api_id, $secret_key);
		$resp = $VK->api('likes.getList', array(
			'type' => 'sitepage',
			'owner_id' => $api_id,
			'item_id' => 321,
			'page_url' => $url));
		print_r($resp); // раскомментировать эту строку, чтобы увидеть ответ от сервера

		/* http://vk.com/dev/wall.get
		 wall.get
		owner_id - user -id
		filter - owner
		count - не более 100
		 */

	}

	public function actionCollectLikes2() {

		$url = 'http://sziget.comma.com.ua/members/wild_shadows/';

		$api_id = '4786964';
		$secret_key = 'AmBAmQETf3J7OpNEgovS';

		$VK = new VKapi($api_id, $secret_key);
		$resp = $VK->api('likes.getList', array(
			'type' => 'sitepage',
			'owner_id' => $api_id,
			'item_id' => 321,
			'page_url' => $url));

		$count_vk_likes = $resp['response']['count'];

		$c1 = Likes::vkShares($url);
		$c2 = Likes::fb_likes($url);

		//var_dump($resp);

		var_dump($count_vk_likes, $c1, $c2);

		echo '<br>';
		echo '<br>';
		exit;
		$membersItems = Members::model()->published()->findAll('image_filename != ""');

		if ($membersItems) {
			$host = 'http://sziget.comma.com.ua/members';

			foreach ($membersItems as $Members) {
				if ($Members->id == 1) {
					$url = 'http://sziget.comma.com.ua/members/5%C2%A0vymir/';
				} else {
					$url = $host . '/' . $Members->code_name . '/';
				}

				echo $url . '<br>';
			}
		}

	}
}