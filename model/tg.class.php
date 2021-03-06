<?php

class Tg {

	public $tmp_value;

	public function run($file_name = '', $uid = array(), $info = array(), $cache_on = 1) {
		$out = '';
		$uid = empty($uid) ? $_SERVER['REQUEST_URI'] : $uid; 

		$path = PATH_TEXT_GENERATOR.'text/'.$file_name;

		if(file_exists($path)) { 
 			
			$cache = unserialize($this->get_cache($uid)); 

			if(!isset($cache[$file_name])) {

				$content = file_get_contents($path);
				$content = $this->set_placeholder($content, $info); 
				$out = $content = $this->set_random_word($content);

				if($cache === false) { 
					$set_cache = serialize(array($file_name => $content));
				} else { 
					$set_cache = serialize(array_merge($cache,array($file_name => $content)));
				}

 				if($cache_on > 0) {
 					$this->set_cache($set_cache,$uid);						
 				}

			} else { 
				$out = $cache[$file_name];
			}
		}
		return $out;
	}

	private function set_random_word($content) {
		 
		return preg_replace_callback(
					'/\[\[(.*?)\]([:a-z]+)?\]/iu', 
					array('Tg', 'rep_random'),
            		$content
            	);
	}

	private function rep_random($m) {   
		$rand_text = explode('//',$m[1]);
		$rand_text = $rand_text[array_rand($rand_text, 1)]; 
		$filters = isset($m[2]) ? $m[2] : array();
						
        return $this->set_filter($filters, $rand_text);
	}

	private function set_filter($filters, $content) { 
		if(!empty($filters)) {
			$filters = explode(':',$filters);
			foreach ($filters as $filter) {
				switch($filter) {

					case 'low': 
						$content =  mb_convert_case($content, MB_CASE_LOWER, "UTF-8");
					break;

					case 'up': 
						$content = mb_convert_case($content, MB_CASE_UPPER, "UTF-8");
					break;

					case 'title': 
						$content = mb_convert_case($content, MB_CASE_TITLE, "UTF-8");
					break;

					case 'upfirst': 
						$content = $this->lowercase_up_word($content);
					break;

					case 'ip': 
						$content = $this->set_declension($content, 'ip');
					break;
					
					case 'rp': 
						$content = $this->set_declension($content, 'rp');
					break;
		 
					case 'dp': 
						$content = $this->set_declension($content, 'dp');
					break;

					case 'vp': 
						$content = $this->set_declension($content, 'vp');
					break;

					case 'tp': 
						$content = $this->set_declension($content, 'tp');
					break;

					case 'pp': 
						$content = $this->set_declension($content, 'pp');
					break;

					default:; break;
				}  
			}
		} 
		return $content; 
	}
 
	private function set_placeholder($content, $placeholder) { 
		foreach ((array)$placeholder as $key => $value){ 
			$this->tmp_value = $value;  
			$content = preg_replace_callback(
					"/\[\[(\+".$key.")\]([:a-z]+)?\]/iu",
					 array('Tg', 'rep_placeholder'),
            		$content
            	); 
		}
		return $content; 
	}

	private function rep_placeholder($m) {
		return  str_replace($m[1],$this->tmp_value, $m[0]) ;
	}

	public function set_declension($words, $declension) {
		$out = array();
		if(!empty($words)) {
			$words = explode(' ', $words);
			foreach ($words as $key => $word) {
				$declension_word = $this->declension($word, $declension);
				if(empty($declension_word)) {
					$out[] = $word;
				} else {
					$out[] = $declension_word;
				}
				
			}
		}
		return implode(' ',$out);
	}
 
	public function declension($word, $declension) { 
    	$val = array_search($declension, array( 'ip', 'rp', 'dp', 'vp', 'tp', 'pp'));
    	$xml = simplexml_load_file("http://export.yandex.ru/inflect.xml?name=$word");
    	return (string) $xml->inflection[$val];
	}

	private function get_cache($uid) {
		$out = '';
		$path = PATH_TEXT_GENERATOR.'cache/'.md5($uid);
		if(file_exists($path)) {
			$out = file_get_contents($path); 
		}
		return $out;
	}

	private function set_cache($text, $uid) {
		$path = PATH_TEXT_GENERATOR.'cache/'.md5($uid); 
		$fp = fopen($path,"wb+");
    	flock ($fp,LOCK_EX);
    	fwrite($fp,   $text);
    	fflush ($fp);
    	flock ($fp,LOCK_UN);
    	fclose($fp);
    	chmod($path, 0777);
	}

	public function lowercase_up_word($word) {
		$out = '';
		$word = explode(' ',$word);
		$out = mb_convert_case($word[0], MB_CASE_TITLE, "UTF-8");
		unset($word[0]);
		return $out.' '.implode(' ',$word); 
	}
}