<?php
class ModelModuleGeo extends Model {
	
	public function smarty_function_get_city()
{  
    
    $gcity = false;

    if (isset($_COOKIE['city'])) {
        
	$gcity = $_COOKIE['city']; 
	$cities = $this->db->query("SELECT name FROM " . DB_PREFIX . "cities WHERE name = '" . $gcity . "'");
        
       if ($cities->row) {
       		$city = $gcity;
       }else{
       		$city = 'Новосибирск';
       }

        return $city;

   } else {

        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '127.0.0.1' && preg_match('#^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$#', $_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ipa[] = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] != '127.0.0.1' && preg_match('#^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$#', $_SERVER['HTTP_CLIENT_IP']))
        {
            $ipa[] = $_SERVER['HTTP_CLIENT_IP'];
        }
        if (isset($_SERVER['REMOTE_ADDR']) && preg_match('#^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$#', $_SERVER['REMOTE_ADDR']))
        {
            $ipa[] = $_SERVER['REMOTE_ADDR'];
        }
        $ip = $ipa[0];
    

    $url = 'http://194.85.91.253:8090/geo/geo.html';
    $cl = curl_init();
    $query = '<ipquery><fields><city/></fields><ip-list><ip>' . $ip . '</ip></ip-list></ipquery>';
    curl_setopt($cl, CURLOPT_URL, $url);
    curl_setopt($cl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($cl, CURLOPT_TIMEOUT, 1);
    curl_setopt($cl, CURLOPT_POST, 1);
    curl_setopt($cl, CURLOPT_POSTFIELDS, $query);
    $result = curl_exec($cl);
    curl_close($cl);
    preg_match("|<city>(.*?)</city>|", $result, $city);
    if (isset($city[1])) {
        $gcity = iconv('windows-1251', 'utf-8', $city[1]);
    } else {
        $gcity = 'Новосибирск';
    }  
      
    $cities = $this->db->query("SELECT name FROM " . DB_PREFIX . "cities WHERE name = '" . $gcity . "'");
        
       if ($cities->row) {
       $city = $gcity;
       }else{
       $city = 'Новосибирск';
       }
       
      setcookie('city', $city, time()+3600*24*7, '/');
	}
    return $city;
}
	
}
?>