<?php
// Version
define('VERSION', '1.0.1');

// Config
require_once(dirname(__FILE__).'/../config.php');
   
// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Application Classes
require_once(DIR_SYSTEM . 'library/currency.php');
require_once(DIR_SYSTEM . 'library/customer.php');
require_once(DIR_SYSTEM . 'library/tax.php');
require_once(DIR_SYSTEM . 'library/weight.php');
require_once(DIR_SYSTEM . 'library/length.php');
require_once(DIR_SYSTEM . 'library/cart.php');

// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Config
$config = new Config();
$registry->set('config', $config);

// Database 
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

$config->set('config_store_id', 0);
		
// Settings
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0' OR store_id = '" . (int)$config->get('config_store_id') . "' ORDER BY store_id ASC");

foreach ($query->rows as $setting) {
	if (!$setting['serialized']) {
		$config->set($setting['key'], $setting['value']);
	} else {
		$config->set($setting['key'], unserialize($setting['value']));
	}
}

$config->set('config_url', HTTP_SERVER);
$config->set('config_ssl', HTTPS_SERVER);	

// Url
$url = new Url($config->get('config_url'), $config->get('config_ssl'));	
$registry->set('url', $url);

// Log 
$log = new Log($config->get('config_error_filename'));
$registry->set('log', $log);

// Request
$request = new Request();
$url_parts = parse_url(HTTP_SERVER);
$request->server = array(
    'HTTP_HOST' => $url_parts['host'],    
    'REQUEST_URI' => '',
	'SERVER_PROTOCOL' => 'HTTP/1.1'
);
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response); 

// Session
$registry->set('session', new Session());

// Cache
$registry->set('cache', new Cache());

// Document
$registry->set('document', new Document());

// Customer
$registry->set('customer', new Customer($registry));

// Language
$languages = array();

$query = $db->query("SELECT * FROM " . DB_PREFIX . "language"); 

foreach ($query->rows as $result) {
	$languages[$result['code']] = array(
		'language_id' => $result['language_id'],
		'name'        => $result['name'],
		'code'        => $result['code'],
		'locale'      => $result['locale'],
		'directory'   => $result['directory'],
		'filename'    => $result['filename']
	);
}

$config->set('config_language_id', $languages[$config->get('config_admin_language')]['language_id']);

$language = new Language($languages[$config->get('config_admin_language')]['directory']);
$language->load($languages[$config->get('config_admin_language')]['filename']);	
$registry->set('language', $language);

// Currency
$currency = new Currency($registry);
// always RU
$currency->set('RUB');

//$symbol_right = trim($currency->getSymbolRight('RUB'));
//$currency->setSymbolRight('RUB', '<span class="ru">&nbsp;&#1041;</span>');
$registry->set('currency', $currency);

// Tax
$registry->set('tax', new Tax($registry));

// Weight
$registry->set('weight', new Weight($registry));

// Length
$registry->set('length', new Length($registry));

// Cart
$registry->set('cart', new Cart($registry));

// Front Controller 
$controller = new Front($registry);
		
//require_once(DIR_APPLICATION . 'controller/feed/yandex_yml.php');

//$controller = new ControllerFeedYandexYml($registry);

// SEO URL's
if (!$seo_type = $config->get('config_seo_url_type')) {
	$seo_type = 'seo_url';
}
$controller->addPreAction(new Action('common/' . $seo_type));	

$action = new Action('feed/yandex_yml/savetofile', array(dirname(__FILE__).'/yandex_yml.xml'));
$controller->dispatch($action, '');
//$controller->saveToFile(dirname(__FILE__).'/yandex_yml.xml');
?>
