<?php
class ControllerCommonSeoPro extends Controller {
	private $cache_data = null;

	public function __construct($registry) {
		parent::__construct($registry);
		$this->cache_data = $this->cache->get('seo_pro');
		if (!$this->cache_data) {
			$query = $this->db->query("SELECT LOWER(`keyword`) as 'keyword', `query` FROM " . DB_PREFIX . "url_alias");
			$this->cache_data = array();
			foreach ($query->rows as $row) {
				$this->cache_data['keywords'][$row['keyword']] = $row['query'];
				$this->cache_data['queries'][$row['query']] = $row['keyword'];
			}
			$this->cache->set('seo_pro', $this->cache_data);
		}
	}

	public function index() {
		// Add rewrite to url class
		if ($this->config->get('config_seo_url')) {
			$this->url->addRewrite($this);
		}

		// Decode URL
		if (isset($this->request->get['_route_'])) {
			$route = $this->request->get['_route_'];
			unset($this->request->get['_route_']);
			$parts = explode('/', trim(utf8_strtolower($route), '/'));
			list($last_part) = explode('.', array_pop($parts));
			array_push($parts, $last_part);

			if ($parts[0] == 'search') {
				if (isset($parts[1]) && $parts[1] == 'tags' && isset($parts[2])) {
					$this->request->get['filter_tag'] = $parts[2];
				}
				if (isset($parts[1]) && $parts[1] == 'name' && isset($parts[2])) {
					$this->request->get['filter_name'] = $parts[2];
				}
				$this->request->get['route'] = 'product/search';
			}

			elseif ($parts[0] == 'order') {
				if (isset($parts[1])) {
					$this->request->get['order_id'] = $parts[1];
					$this->request->get['route'] = 'account/order/info';
				} else {
					$this->request->get['route'] = 'error/not_found';
				}
			}

			else
			{
				$rows = array();
				foreach ($parts as $keyword) {
					if (isset($this->cache_data['keywords'][$keyword])) {
						$rows[] = array('keyword' => $keyword, 'query' => $this->cache_data['keywords'][$keyword]);
					}
				}

				if (count($rows) == sizeof($parts)) {
					$queries = array();
					foreach ($rows as $row) {
						$queries[utf8_strtolower($row['keyword'])] = $row['query'];
					}

					reset($parts);
					foreach ($parts as $part) {
						$url = explode('=', $queries[$part], 2);

						if ($url[0] == 'news_id') {
							$this->request->get['news_id'] = $url[1];
						}

						if ($url[0] == 'ncategory_id') {
							if (!isset($this->request->get['ncat'])) {
								$this->request->get['ncat'] = $url[1];
							} else {
								$this->request->get['ncat'] .= '_' . $url[1];
							}
						}
						
						if ($url[0] == 'gallery_id') {
							if (!isset($this->request->get['album'])) {
							$this->request->get['album'] = $url[1];
							} else {
								$this->request->get['album'] .= '_' . $url[1];
							}
						}

						if ($url[0] == 'record_id') {
							$this->request->get['record_id'] = $url[1];
						}

						if ($url[0] == 'blog_id') {
							if (!isset($this->request->get['blog_id'])) {
								$this->request->get['blog_id'] = $url[1];
							} else {
								$this->request->get['blog_id'] .= '_' . $url[1];
							}
						}
						
						if ($url[0] == 'article_id') {
							if (!isset($this->request->get['id'])) {
							$this->request->get['id'] = $url[1];
						} else {
							$this->request->get['id'] .= '_' . $url[1];
						         }
						} 

						if ($url[0] == 'category_id') {
							if (!isset($this->request->get['path'])) {
								$this->request->get['path'] = $url[1];
							} else {
								$this->request->get['path'] .= '_' . $url[1];
							}
						}
						elseif (count($url) > 1) {
							$this->request->get[$url[0]] = $url[1];
						}
					}
				} else {
					$this->request->get['route'] = 'error/not_found';
				}
			}

			if (isset($this->request->get['product_id'])) {
				$this->request->get['route'] = 'product/product';
				if (!isset($this->request->get['path'])) {
					$path = $this->getPathByProduct($this->request->get['product_id']);
					if ($path) $this->request->get['path'] = $path;
				}
			} elseif (isset($this->request->get['path'])) {
				$this->request->get['route'] = 'product/category';
			} elseif (isset($this->request->get['id'])) {
				$this->request->get['route'] = 'information/article';
			} elseif (isset($this->request->get['manufacturer_id'])) {
				$this->request->get['route'] = 'product/manufacturer/product';
			} elseif (isset($this->request->get['information_id'])) {
				$this->request->get['route'] = 'information/information';
			} elseif (isset($this->request->get['news_id'])) {
				$this->request->get['route'] = 'information/news';
			} elseif (isset($this->request->get['album'])) {
				$this->request->get['route'] = 'gallery/gallery';
			} elseif (isset($this->request->get['ncat'])) {
				$this->request->get['route'] = 'news/ncategory';
			} elseif (isset($this->request->get['record_id'])) {
				$this->request->get['route'] = 'record/record';
			} elseif (isset($this->request->get['blog_id'])) {
				$this->request->get['route'] = 'record/blog';
			} else {
				if (isset($queries[$parts[0]])) {
					$this->request->get['route'] = $queries[$parts[0]];
				}
			}

			if (isset($this->request->get['route']) && $this->request->get['route'] != 'error/not_found') {
				$this->validate($route);
			}

			if (isset($this->request->get['route'])) {
				return $this->forward($this->request->get['route']);
			}


		}
	}

	public function rewrite($link) {
		if (!$this->config->get('config_seo_url')) return $link;

		$seo_url = '';

		$component = parse_url(str_replace('&amp;', '&', $link));

		$data = array();
		parse_str($component['query'], $data);

		$route = $data['route'];
		unset($data['route']);

		switch ($route) {
			case 'product/product':
				if (isset($data['product_id'])) {
					$tmp = $data;
					$data = array();
					if ($this->config->get('config_seo_url_include_path')) {
						$data['path'] = $this->getPathByProduct($tmp['product_id']);
						if (!$data['path']) return $link;
					}
					$data['product_id'] = $tmp['product_id'];
					if (isset($tmp['tracking'])) {
						$data['tracking'] = $tmp['tracking'];
					}
				}
				break;

			case 'product/category':
				if (isset($data['path'])) {
					$category = explode('_', $data['path']);
					$category = end($category);
					$data['path'] = $this->getPathByCategory($category);
					if (!$data['path']) return $link;
				}
				break;

			case 'information/news/news':
			case 'product/product/review':
			case 'information/information/info':
				return $link;
				break;

			case 'common/home':
				return trim($seo_url, '//');
				break;

			default:
				break;
		}

		if ($component['scheme'] == 'https') {
			$link = $this->config->get('config_ssl');
		} else {
			$link = $this->config->get('config_url');
		}

		$link .= 'index.php?route=' . $route;

		if (count($data)) {
			$link .= '&' . urldecode(http_build_query($data));
		}

		$queries = array();
		foreach ($data as $key => $value) {
			switch ($key) {
				case 'product_id':
				case 'manufacturer_id':
				case 'category_id':
				case 'information_id':
				case 'news_id':
				case 'gallery_id':
				case 'article_id':
				case 'record_id':
					$queries[] = $key . '=' . $value;
					unset($data[$key]);
					$postfix = 1;
					break;

				case 'filter_tag':
					$ft = $value;
					unset($data[$key]);
					break;

				case 'filter_name':
					$fn = $value;
					unset($data[$key]);
					break;

				case 'order_id':
					$oi = $value;
					unset($data[$key]);
					break;

				case 'path':
					$categories = explode('_', $value);
					foreach ($categories as $category) {
						$queries[] = 'category_id=' . $category;
					}
					unset($data[$key]);
					break;
					
				case 'album':
					$galleries = explode('_', $value);
					foreach ($galleries as $gallery) {
						$queries[] = 'gallery_id=' . $gallery;
					}
					unset($data[$key]);
					break;
					
				case 'id':
					$articles = explode('_', $value);
					foreach ($articles as $article) {
						$queries[] = 'article_id=' . $article;
					}
					unset($data[$key]);
					break;

				case 'ncat':
					$ncategories = explode('_', $value);
					foreach ($ncategories as $ncategory) {
						$queries[] = 'ncategory_id=' . $ncategory;
					}
					unset($data[$key]);
					break;

				case 'blog_id':
					$bcategories = explode('_', $value);
					foreach ($bcategories as $bcategory) {
						$queries[] = 'blog_id=' . $bcategory;
					}
					unset($data[$key]);
					break;

				default:
					break;
			}
		}

		if (empty($queries)) {
			$queries[] = $route;
		}

		$rows = array();
		foreach ($queries as $query) {
			if (isset($this->cache_data['queries'][$query])) {
				$rows[] = array('query' => $query, 'keyword' => $this->cache_data['queries'][$query]);
			}
		}

		if (count($rows) == count($queries)) {
			$aliases = array();
			foreach ($rows as $row) {
				$aliases[$row['query']] = $row['keyword'];
			}
			foreach ($queries as $query) {
				$seo_url .= '/' . rawurlencode($aliases[$query]);
			}
		}

		if (!empty($ft)) {
			$seo_url .= '/tags/' . rawurlencode($ft);
		}

		if (!empty($fn)) {
			$seo_url .= '/name/' . rawurlencode($fn);
		}

		if (!empty($oi)) {
			$seo_url .= '/order/' . rawurlencode($oi);
		}

		if ($seo_url == '') return $link;

		$seo_url = trim($seo_url, '/');

		if ($component['scheme'] == 'https') {
			$seo_url = $this->config->get('config_ssl') . $seo_url;
		} else {
			$seo_url = $this->config->get('config_url') . $seo_url;
		}

		if (isset($postfix))
		{
			$seo_url .= trim($this->config->get('config_seo_url_postfix'));
		}
		else
		{
			$seo_url .= '/';
		}

		if (count($data)) {
			$seo_url .= '?' . urldecode(http_build_query($data));
		}

		return $seo_url;
	}

	private function getPathByProduct($product_id) {
		$product_id = (int)$product_id;
		if ($product_id < 1) return false;

		static $path = null;
		if (!is_array($path)) {
			$path = $this->cache->get('product.seopath');
			if (!is_array($path)) $path = array();
		}

		if (!isset($path[$product_id])) {
			$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . $product_id . "' ORDER BY main_category DESC LIMIT 1");

			$path[$product_id] = $this->getPathByCategory($query->num_rows ? (int)$query->row['category_id'] : 0);

			$this->cache->set('product.seopath', $path);
		}

		return $path[$product_id];
	}

	private function getPathByCategory($category_id) {
		$category_id = (int)$category_id;
		if ($category_id < 1) return false;

		static $path = null;
		if (!is_array($path)) {
			$path = $this->cache->get('category.seopath');
			if (!is_array($path)) $path = array();
		}

		if (!isset($path[$category_id])) {
			$max_level = 10;

			$sql = "SELECT CONCAT_WS('_'";
			for ($i = $max_level-1; $i >= 0; --$i) {
				$sql .= ",t$i.category_id";
			}
			$sql .= ") AS path FROM " . DB_PREFIX . "category t0";
			for ($i = 1; $i < $max_level; ++$i) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "category t$i ON (t$i.category_id = t" . ($i-1) . ".parent_id)";
			}
			$sql .= " WHERE t0.category_id = '" . $category_id . "'";

			$query = $this->db->query($sql);

			$path[$category_id] = $query->num_rows ? $query->row['path'] : false;

			$this->cache->set('category.seopath', $path);
		}

		return $path[$category_id];
	}

	private function validate($link) {
		$get = array('path', 'ncat', 'blog_id', 'product_id', 'manufacturer_id', 'category_id', 'information_id', 'order_id', 'news_id', 'id', 'album', 'record_id', 'filter_tag', 'filter_name');

		$data = array_intersect_key($this->request->get, array_flip($get));

		$args = '';

		if (isset($data['path'])) {
			$args .= 'path=' . $data['path'];
			unset($data['path']);
		}

		if (count($data)) {
			$args .= '&' . urldecode(http_build_query($data));
		}

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$scheme = 'SSL';
		} else {
			$scheme = 'NONSSL';
		}

		$seo_url = $this->url->link($this->request->get['route'], $args, $scheme);

		$seo_url = str_replace('&amp;', '&', $seo_url);

		$link = parse_url($this->config->get('config_url'), PHP_URL_PATH) . $link;

		if ($link != rawurldecode(parse_url($seo_url, PHP_URL_PATH))) {
			$get[] = 'route';

			$data = array_diff_key($this->request->get, array_flip($get));

			if (count($data)) {
				$seo_url .= (strpos($seo_url, '?') === false) ? '?' : '&';
				$seo_url .= urldecode(http_build_query($data));
			}

			header($this->request->server['SERVER_PROTOCOL'] . ' 301 Moved Permanently');

			$this->response->redirect($seo_url);
		}
	}
}
?>
