<?php
class ControllerModuleModuleExtract extends Controller {
	private $error = array (); 
	
	public function index() {
		$this->load->language('module/module_extract');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->data['heading_title'       ] = $this->language->get('heading_title');
		
		$this->data['button_modules'      ] = $this->language->get('button_modules');
		$this->data['text_no_results'     ] = $this->language->get('text_no_results');
		$this->data['text_module_name'    ] = $this->language->get('text_module_name');
			
		$this->data['example_module_name' ] = $this->language->get('example_module_name');
		
		$this->data['error_select_extract'] = $this->language->get('error_select_extract');
		$this->data['error_select_delete' ] = $this->language->get('error_select_delete');
		
		$this->data['error_warning'] = $this->data['success'] = FALSE;
		
		$this->data['breadcrumbs'] = array (
			array (
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => FALSE
			),
			array (
				'text'      => $this->language->get('text_module'),
				'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => ' :: '
			),
			array (
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('module/module_extract', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => ' :: '
			)
		);
		
		if (isset ($this->request->get['page'])) {
			$this->data['page'] = (string) $this->request->get['page'];
		} else {
			$this->data['page'] = FALSE;
		}
		
		if ($this->data['page'] == 'MODULES_EXTRACT') {
			$url = '&page=' . $this->data['page'];
			
			$this->data['text_module_size'] = $this->language->get('text_module_size');
			$this->data['text_module_date'] = $this->language->get('text_module_date');
			$this->data['text_module_time'] = $this->language->get('text_module_time');
			$this->data['text_total'      ] = $this->language->get('text_total');
			
			$this->data['button_cancel'   ] = $this->language->get('button_cancel');
			$this->data['button_delete'   ] = $this->language->get('button_delete');
			
			$this->data['action'] = $this->url->link('module/module_extract', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$this->data['cancel'] = $this->url->link('module/module_extract', 'token=' . $this->session->data['token'], 'SSL');
			
			$this->data['module_total'] = FALSE;
			$this->data['module_list' ] = array ();
			
			$this->data['breadcrumbs'][] = array (
				'text'      => $this->data['button_modules'],
				'href'      => $this->url->link('module/module_extract', 'token=' . $this->session->data['token'] . $url, 'SSL'),
				'separator' => ' :: '
			);
			
			if ($this->validate() && isset ($this->request->post['module_list']) && is_array ($this->request->post['module_list'])) {
				foreach ($this->request->post['module_list'] as $module) {
					if (file_exists ($module)) {
						unlink ($module);
					}
				}
				
				$this->session->data['success'] = $this->language->get('success_delete');
				$this->redirect($this->url->link('module/module_extract', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			if ($this->user->hasPermission('modify', 'module/module_extract') && $this->user->hasPermission('access', 'module/module_extract')) {
				$this->data['module_list' ] = $this->moduleList();
				$this->data['module_total'] = count ($this->data['module_list']);
			}
		} else {
			$this->data['text_search'      ] = $this->language->get('text_search');
			$this->data['text_total_search'] = $this->language->get('text_total_search');
			$this->data['text_path_name'   ] = $this->language->get('text_path_name');
			$this->data['text_file_name'   ] = $this->language->get('text_file_name');
			
			$this->data['button_cancel'    ] = $this->language->get('button_modules_list');
			$this->data['button_extract'   ] = $this->language->get('button_extract');
			
			$this->data['module_name'  ] = FALSE;
			$this->data['module_search'] = array ();
			
			$this->data['action'] = $this->url->link('module/module_extract', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
			
			if (isset ($this->request->post['module_name'])) {
				if (!empty ($this->request->post['module_name'])) {
					$this->data['module_name'] = (string) $this->request->post['module_name'];
				} else {
					$this->data['error_warning'] = $this->language->get('error_module_name');
				}
			}
			
			if (!isset ($this->request->post['module_search'])) {
				if ($this->validate() && !empty ($this->data['module_name'])) {
					$directory = str_replace ('/admin/', '', DIR_APPLICATION);
					$this->data['module_search'] = $this->moduleSearch($directory, $directory, $this->data['module_name']);
					$this->data['module_total' ] = count ($this->data['module_search']);
				}
				
			}
			
			if ($this->validate() && isset ($this->request->post['module_search']) && is_array ($this->request->post['module_search'])) {
				$this->moduleExtract($this->request->post['module_search']);
			}
		}
		
		if (isset ($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		}
		
		if (isset ($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset ($this->session->data['success']);
		}
		
		$this->data['modules'] = $this->url->link('module/module_extract', 'token=' . $this->session->data['token'] . '&page=MODULES_EXTRACT', 'SSL');
		
		$this->template = 'module/module_extract.tpl';
		$this->children = array (
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
	}
	
	private function moduleList() {
		$modules = array ();
		$files = glob ($this->validateDir() . '*');
		
		foreach ($files as $file) {
			$info = pathinfo ($file);
			$stat = stat ($file);
			
			$modules[] = array (
				'file' => $file,
				'link' => HTTP_CATALOG . 'system/cache/module_extract/' . $info['basename'],
				'name' => $info['basename'],
				'size' => round (($stat['size'] / 1024), 2),
				'date' => date ('d-m-Y', $stat['ctime']),
				'time' => date ('H:i:s', $stat['ctime'])
			);
		}
		
		asort ($modules);
		return $modules;
	}
	
	private function moduleSearch ($dir, $dir_, $module_name) {
		static $files = array ();
		$s = '/';
		
		if (is_dir ($dir) && $handle = opendir ($dir)) {
			while (FALSE !== ($file = readdir ($handle))) {
				if ($file[0] != '.') {
					if ($dir . $s . $file . $s == DIR_CACHE) {
						continue;
					}
					
					if (is_dir ($dir . $s . $file)) {
						$files = array_merge ($this->moduleSearch($dir . $s . $file, $dir_, $module_name), $files);
					} else if (preg_match ('/'. $module_name . '/', $file)) {
						
						$info = pathinfo ($dir . $s . $file);
						
						if (!isset ($files[$info['filename']])) {
							$files[$info['filename']] = array ('module' => $info['filename'], 'files' => array ());
						}
						
						$files[$info['filename']]['files'][] = array (
							'name' => $dir . $s . $file,
							'path' => str_replace ($dir_, FALSE, $dir),
							'file' => $file
						);
					}
					
				}
			}
			
			closedir($handle);
		}
		
		return $files;
	}
	
	private function moduleExtract ($module_search) {
		if (class_exists ('ZipArchive')) {
			
			$dir = $this->validateDir() . $this->data['module_name'] . '.' . time() . '.' . md5 ($this->data['module_name']) . '.zip';
			
			$zip = new ZipArchive();
			if ($zip->open($dir, ZIPARCHIVE::CREATE) !== true) {
				$this->error['warning'] = $this->language->get('error_creat_zip');
			}
			
			foreach ($module_search as $modules) {
				$dir_zip = FALSE;
				$info    = pathinfo ($modules);
				$folders = explode ('/', $info['dirname']);
				
				for ($i = 1; $i < count ($folders); $i++) {
					$dir_zip .= $folders[$i] . '/';
				}
				
				$zip->addFile($modules, $dir_zip . $info['basename']);
			}
			
			$zip->close();
			
			$this->session->data['success'] = str_replace ('{NAME}', $this->data['module_name'], $this->language->get('success_extract'));
			$this->redirect($this->url->link('module/module_extract', 'token=' . $this->session->data['token'], 'SSL'));
		} else {
			$this->error['warning'] = $this->language->get('error_class_zip');
		}
	}
	
	private function validateDir() {
		$dir = DIR_CACHE . '/module_extract';
		if (!is_dir ($dir)) {
			mkdir ($dir);
		}
		return $dir . '/';
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/module_extract')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>