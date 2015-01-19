<?php 
class ControllerProductCategory extends Controller {  
	public function index() { 
		$this->language->load('product/category');
		
		$this->load->model('catalog/category');
		
		$this->load->model('catalog/product');
		
		$this->load->model('tool/image'); 
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else { 
			$page = 1;
		}	
							
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_catalog_limit');
		}
					
		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
       		'separator' => false
   		);	
			
		if (isset($this->request->get['path'])) {
			$path = '';
		
			$parts = explode('_', (string)$this->request->get['path']);
		
			foreach ($parts as $path_id) {
				if (!$path) {
					$path = $path_id;
				} else {
					$path .= '_' . $path_id;
				}
									
				$category_info = $this->model_catalog_category->getCategory($path_id);
				
				if ($category_info) {
	       			$this->data['breadcrumbs'][] = array(
   	    				'text'      => $category_info['name'],
						'href'      => $this->url->link('product/category', 'path=' . $path),
        				'separator' => $this->language->get('text_separator')
        			);
				}
			}		
		
			$category_id = array_pop($parts);
		} else {
			$category_id = 0;
		}
		
		$category_info = $this->model_catalog_category->getCategory($category_id);
	
		if ($category_info) {
			if ($category_info['seo_title']) {
		  		$this->document->setTitle($category_info['seo_title']);
			} else {
		  		$this->document->setTitle($category_info['name']);
			}

			if ($category_info['podarok']) {
		  		$this->data['podarok'] = $category_info['podarok'];
			} else {
		  		$this->data['podarok'] = '';
			}

			$this->document->setDescription($category_info['meta_description']);
			$this->document->setKeywords($category_info['meta_keyword']);
			
			if ($category_info['seo_h1']) {
				$this->data['heading_title'] = $category_info['seo_h1'];
			} else {
				$this->data['heading_title'] = $category_info['name'];
			}
			
			$this->data['text_refine'] = $this->language->get('text_refine');
			$this->data['text_empty'] = $this->language->get('text_empty');			
			$this->data['text_quantity'] = $this->language->get('text_quantity');
			$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$this->data['text_model'] = $this->language->get('text_model');
			$this->data['text_price'] = $this->language->get('text_price');
			$this->data['text_tax'] = $this->language->get('text_tax');
			$this->data['text_points'] = $this->language->get('text_points');
			$this->data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
			$this->data['text_display'] = $this->language->get('text_display');
			$this->data['text_list'] = $this->language->get('text_list');
			$this->data['text_grid'] = $this->language->get('text_grid');
			$this->data['text_sort'] = $this->language->get('text_sort');
			$this->data['text_limit'] = $this->language->get('text_limit');
					
			$this->data['button_cart'] = $this->language->get('button_cart');
			$this->data['button_wishlist'] = $this->language->get('button_wishlist');
			$this->data['button_compare'] = $this->language->get('button_compare');
			$this->data['button_continue'] = $this->language->get('button_continue');

			$this->data['medal'] = $category_info['category_id'];
					
			if ($category_info['image']) {
				$this->data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
			} else {
				$this->data['thumb'] = '';
			}
			
		if (isset($this->session->data['simple']['shipping_address']['city'])) {
			$city = $this->session->data['simple']['shipping_address']['city'];
		}else{
			$this->load->model('module/geo');
			$city = $this->model_module_geo->smarty_function_get_city();
		}
		
		$description = '';
	
		$service_centers = $this->model_catalog_product->getServiceCentersGarant($city, $category_id);
			
			$this->data['service_centers'] = array(); 
			
			foreach ($service_centers as $service_center) {
			
			$service = array();
			$service['city'] = $service_center['name'];
			$service['name'] = $service_center['sname'];
			$service['address'] = $service_center['address'];
			$service['time'] = $service_center['time'];
			$service['phone'] = $service_center['phone'];
			$service['href'] = $service_center['urlserviceinfo'];
			
			$services[] = $service;
			
			if (count($services)) { 
		          $i_services = array();
		          foreach ($services as $serv) {
		          if ($serv['city']) { 
		          	$e_city = '<strong>г. '.$serv['city'].'</strong>';
		          	}else{
		          	$e_city = '';
		          }
		          if ($serv['href']) { 
		          	$e_name = '<a href="'.$serv['href'].'"><strong>'.$serv['name'].'</strong></a>';
		          	}else{
		          	$e_name = '<strong>'.$serv['name'].'</strong>';
		          }
		          if ($serv['address']) { 
		          	$e_address = $serv['address'];
		          	}else{
		          	$e_address = '';
		          }
		          if ($serv['time']) { 
		          	$e_time = '<br />Время работы: '.$serv['time'];
		          	}else{
		          	$e_time = '';
		          }
		          if ($serv['phone']) { 
		          	$e_phone = '<br />Тел: '.$serv['phone'];
		          	}else{
		          	$e_phone = '';
		          }
		            
		          $i_services[] = '<li>'.$e_city.'&nbsp;&nbsp;'.$e_name.'&nbsp;&nbsp;'.$e_address.'&nbsp;'.$e_time.'&nbsp;'.$e_phone.'</li>';
		          }
		         
		     	  $i_serv = implode($i_services);
		     	  $serv_c = '<ul>'.$i_serv.'</ul>';
		     	           
			  }
			
			if (!$this->config->get('config_store_id')) {
			$category_description = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
		
		$find = array(
				'{city}',
				'{city_in}',
				'{city_to}',
				'{city_a}',
				'{service_centers}'
			);
			
			$replace = array(
				'city' => $service_center['city'],
				'city_in' => $service_center['name_in'],
				'city_to' => $service_center['name_to'],
				'city_a' => $service_center['name_a'],
				'service_centers' => $serv_c
			);
				
			$description = str_replace($find, $replace, $category_description);

			} 
	}

			$delivery_points = $this->model_catalog_product->getDeliveryPoints($city);

			if ($delivery_points) {
			
			foreach ($delivery_points as $delivery_point) {
					
				$this->data['delivery_period_to_warehouse'] = $delivery_point['delivery_period_to_warehouse'];
				$this->data['price_to_warehouse'] = $delivery_point['price_to_warehouse'];
				$this->data['price_to_door'] = $delivery_point['price_to_door'];
				$this->data['city_a'] = $delivery_point['name_a'];
				$this->data['city_to'] = $delivery_point['name_to'];
			}
			}else{
				$this->data['price_to_warehouse'] = '';
				$this->data['price_to_door'] = '';
				$this->data['city_a'] = '';
				$this->data['city_to'] = $city;
			}
			
/*	if ($delivery_point['delivery_period_to_door'] && $delivery_point['delivery_period_to_door'] != 0) {
			$d = $delivery_point['delivery_period_to_door'];
		} else {
			$d = 0;
		}
		
    $date=explode(".", date("l.d.m.Y", strtotime((+(int)$d)." days")));
	    switch ($date[0]){
	    case 'Monday': $l='в понедельник'; break;
	    case 'Tuesday': $l='во вторник'; break;
	    case 'Wednesday': $l='в среду'; break;
	    case 'Thursday': $l='в четверг'; break;
	    case 'Friday': $l='в пятницу'; break;
	    case 'Saturday': $l='в субботу'; break;
	    case 'Sunday': $l='в воскресенье'; break;
	    }
	    switch ($date[2]){
	    case 1: $m='января'; break;
	    case 2: $m='февраля'; break;
	    case 3: $m='марта'; break;
	    case 4: $m='апреля'; break;
	    case 5: $m='мая'; break;
	    case 6: $m='июня'; break;
	    case 7: $m='июля'; break;
	    case 8: $m='августа'; break;
	    case 9: $m='сентября'; break;
	    case 10: $m='октября'; break;
	    case 11: $m='ноября'; break;
	    case 12: $m='декабря'; break;
    }
    
 if ($delivery_point['delivery_period_to_door'] && $delivery_point['delivery_period_to_door'] != '0') {
			$this->data['delivery_period_to_door'] = $l.'&nbsp;'.(int)$date[1].'&nbsp;'.$m;
		} elseif (isset($delivery_point['delivery_period_to_door']) && $delivery_point['delivery_period_to_door'] == '0') {
			$this->data['delivery_period_to_door'] = 'в день заказа';
		} else {
			$this->data['delivery_period_to_door'] = '';
		}  */



		 if (isset($delivery_point['delivery_period_to_door']) && $delivery_point['delivery_period_to_door'] == '0') {
			$this->data['delivery_period_to_door'] = 'в день заказа';
		} else {
			$this->data['delivery_period_to_door'] = '';
		} 

		// $this->data['delivery_period_to_door'] = '';

									
			$this->data['description'] = $description;
			
			$this->data['info'] = $category_info['info'];
			
			$this->data['compare'] = $this->url->link('product/compare');
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}	
			
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
								
			$this->data['categories'] = array();
			
			$results = $this->model_catalog_category->getCategories($category_id);
			
			foreach ($results as $result) {
				$data = array(
					'filter_category_id'  => $result['category_id'],
					'filter_sub_category' => true	
				);
							
				$product_total = $this->model_catalog_product->getTotalProducts($data);
				
				$this->data['infoblock'] = false;
				
				if (!$result['info']) {
					$cattip = $result['name'] . ' (' . $product_total . ')';
				} else {
					$cattip = $result['name'];
					$this->data['infoblock'] = true;
				}
				
				$this->data['categories'][] = array(
					'category_id'  => $result['category_id'],
					'name'  => $cattip,
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url),
					'info'  => $result['info']
				);
			}







$this->data['products_all'] = array();

for( $x = 0; $x < count( $this->data['categories'] ); $x++ ) {

    $cat = $this->data['categories'][ $x ][ 'category_id' ];
    $this->data['products_all'][ $cat ] = array();

    $data = array(
        'filter_category_id' => $cat, 
        'sort'               => $sort,
        'order'              => $order,
        'start'              => ($page - 1) * $limit,
        'limit'              => $limit
    );

    $product_total = $this->model_catalog_product->getTotalProducts($data); 
    $results = $this->model_catalog_product->getProducts($data);

    foreach ($results as $result) {

        if ($result['image']) {
            $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
        }
        else { $image = false; }

        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
            $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
        } 
        else { $price = false; }

        if ((float)$result['special']) {
            $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
        } 
        else { $special = false; }  

        if ($this->config->get('config_tax')) {
            $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
        }
        else { $tax = false; }              

        if ($this->config->get('config_review_status')) {
            $rating = (int)$result['rating'];
        } 
        else { $rating = false; }

        $this->data['products_all'][ $cat ][] = array(
            'product_id'  => $result['product_id'],
            'thumb'       => $image,
            'name'        => $result['name'],
            'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 200) . '...',
            'price'       => $price,
            'special'     => $special,
            'tax'         => $tax,
            'rating'      => $result['rating'],
            'reviews'     => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
            'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'])

        );
    }
}






			
			$this->data['products'] = array();
			
			$data = array(
				'filter_category_id' => $category_id, 
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
			);
					
			$product_total = $this->model_catalog_product->getTotalProducts($data); 
			
			$results = $this->model_catalog_product->getProducts($data);
			
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				} else {
					$image = false;
				}
				
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}
				
				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}	
				
				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
				} else {
					$tax = false;
				}				
				
				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}
								
				$this->data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_truncate(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 200, '&nbsp;&hellip;', true),
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'rating'      => $result['rating'],
					'reviews'     => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'])
				);
			}
			
			$url = '';
	
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
							
			$this->data['sorts'] = array();
			
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.sort_order&order=ASC' . $url)
			);
			
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC' . $url)
			);

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC' . $url)
			);

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url)
			); 

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url)
			); 
			
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_desc'),
				'value' => 'rating-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=DESC' . $url)
			); 
			
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_asc'),
				'value' => 'rating-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=ASC' . $url)
			);
			
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=ASC' . $url)
			);

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=DESC' . $url)
			);
			
			$url = '';
	
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->data['limits'] = array();
			
			$this->data['limits'][] = array(
				'text'  => $this->config->get('config_catalog_limit'),
				'value' => $this->config->get('config_catalog_limit'),
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $this->config->get('config_catalog_limit'))
			);
						
			$this->data['limits'][] = array(
				'text'  => 25,
				'value' => 25,
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=25')
			);
			
			$this->data['limits'][] = array(
				'text'  => 50,
				'value' => 50,
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=50')
			);

			$this->data['limits'][] = array(
				'text'  => 75,
				'value' => 75,
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=75')
			);
			
			$this->data['limits'][] = array(
				'text'  => 100,
				'value' => 100,
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=100')
			);
						
			$url = '';
	
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
	
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
					
			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}');
		
			$this->data['pagination'] = $pagination->render();
		
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			$this->data['limit'] = $limit;
		
			$this->data['continue'] = $this->url->link('common/home');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/category.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/product/category.tpl';
			} else {
				$this->template = 'default/template/product/category.tpl';
			}
			
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
				
			$this->response->setOutput($this->render());										
    	} else {
			$url = '';
			
			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}
									
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
				
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
						
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
						
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_error'),
				'href'      => $this->url->link('product/category', $url),
				'separator' => $this->language->get('text_separator')
			);
				
			$this->document->setTitle($this->language->get('text_error'));

      		$this->data['heading_title'] = $this->language->get('text_error');

      		$this->data['text_error'] = $this->language->get('text_error');

      		$this->data['button_continue'] = $this->language->get('button_continue');

      		$this->data['continue'] = $this->url->link('common/home');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
			}
			
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
					
			$this->response->setOutput($this->render());
		}
  	}
}
?>