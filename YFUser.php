<?php

/**
 * @author SilentImp <ravenb@mail.ru>
 * @link http://twitter.com/SilentImp/
 * @link http://silentimp.habrahabr.ru/
 * @package YandexFotki
 */

/**
 * Позволяет провести аутентификацию пользователя на сервисе Яндекс.Фотки и
 * получить коллекции фотографий и альбомов пользователя.
 *
 * @throws YFUserException|YFXMLException
 * @package YandexFotki
 */
class YFUser {	
	/**
	 * @var string RSA ключ, необходимый для получения токена (аутентификации пользователя)
	 */
	protected $rsaKey = null;
	
	/**
	 * @var string Идентификатор запроса, необходимый для получения токена (аутентификации пользователя)
	 */
	protected $requestId = null;

	/**
	 * @var string Токен, подтверждающий, что пользователь аутентифицирован
	 */
	protected $token = null;

	/**
	 * @var string Логин пользователя
	 */
	protected $login = null;
	
	/**
	 * @var string Пароль пользователя
	 */
	protected $password = null;

	/**
	 * @var string Адрес, по которому можно получить кллекцию альбомов пользователя
	 */
	protected $albumCollectionUrl = null;

	/**
	 * @var string Адрес, по которому можно получить кллекцию фотографий пользователя
	 */
	protected $photoCollectionUrl = null;
	
	/**
	 * @var YFAlbumCollection Коллекция альбомов пользователя
	 */
	protected $albumCollection = array();

	/**
	 * @var YFPhotoCollection Коллекция фотографий пользователя
	 */
	protected $photoCollection = array();
	
	/**
	 * @param string $login Логин пользователя.
	 * @param string $password Пароль пользователя.	 
	 * @return void
	 */
	public function __construct($login, $password=null){
		$this->login = $login;
		$this->password = $password;
		libxml_use_internal_errors(true);
	}
	
	public function __get($name){
		return $name;
	}

	/**
	 * Возвращает токен
	 *
	 * @return string
	 */
	function getToken(){
		return $this->token;
	}

	/**
	 * Устанавливает токен
	 * 
	 * @param string $token
	 * @return void
	 */
	function setToken($token){
		$this->token = $token;
	}

	/**
	 * @param string $name Имя коллекции альбомов. Если не указано, метод вернет массив, содержащий все коллекции.
	 * @return array|YFAlbumCollection
	 */
	public function getAlbumCollection($name=null){
		if($name===null) return $this->albumCollection;
		return $this->albumCollection[$name];		
	}

	/**
	 * Возвращает именованную коллекцию фотографий
	 * 
	 * @param string $collection_name Имя коллекции фотографий. Если не указано, метод вернет массив, содержащий все коллекции.
	 * @return array|YFPhotoCollection
	 */
	public function getPhotoCollection($collection_name=null){
		if($collection_name===null) return $this->photoCollection;
		return $this->photoCollection[$collection_name];		
	}
	
	/**
	 * Удаляет именованную коллекцию фотографий
	 *
	 * @param $name Имя коллекции фотографий.
	 * @return void
	 */
	public function removePhotoCollection($name){
		unset($this->photoCollection[$name]);
	}
	
	/**
	 * Удаляет именованную коллекцию альбомов
	 *
	 * @param string $name Имя коллекции альбомов.
	 * @return void
	 */
	public function removeAlbumCollection($name){
		unset($this->albumCollection[$name]);
	}

	/**
	 * Добавляет коллекцию фотографий.
	 * Если коллекция с таким именем уже существует, она будет перезаписана.
	 * При создании происходит поиск в коллекции с условиями по умолчанию.
	 *
	 * @param string $name Имя новой коллекции
	 * @return array|YFPhotoCollection
	 */
	public function addPhotoCollection($name){
		$this->photoCollection[$name] = new YFPhotoCollection($this->photoCollectionUrl, $this->getToken());
		$this->photoCollection[$name]->searchEx($this->getToken());
		return $this->photoCollection[$name];
	}

	/**
	 * Добавляет коллекцию альбомов.
	 * Если коллекция с таким именем уже существует, она будет перезаписана.
	 * При создании происходит поиск в коллекции с условиями по умолчанию.
	 * 
	 * @param string $name Имя новой коллекции.
	 * @return YFAlbumCollection
	 */
	public function addAlbumCollection($name){
		$this->albumCollection[$name] = new YFAlbumCollection($this->albumCollectionUrl, $this->getToken());
		$this->albumCollection[$name]->search($this->getToken());
		return $this->albumCollection[$name];
	}


	/**
	 * Получает сервисный документ, содержащий URL, которые используются
	 * для получения коллекций пользователя.
	 *
	 * @throws YFXMLException
	 * @return void
	 */
	public function getServiceDocument(){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "http://api-fotki.yandex.ru/api/users/".$this->login."/");
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($curl, CURLOPT_HTTPGET, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$xml = curl_exec($curl);
		$xml = $this->deleteXMLNameSpace($xml);
		if(curl_getinfo($curl, CURLINFO_HTTP_CODE)!=200){
			throw new YFXMLException($xml, E_ERROR);
		}
		curl_close($curl);

		if(($sxml=simplexml_load_string($xml))===false){
			throw new YFXMLException("Нестандартный ответ. Текст ответа: ".$xml, E_ERROR);
		}

		$result = $sxml->xpath("//collection[@id='album-list']");
		if(count($result)<1){
			throw new YFXMLException("Адресс коллекции альбомов не был получен", E_ERROR);
		}
		$this->albumCollectionUrl = $result[0]->attributes()->href;
		$result = $sxml->xpath("//collection[@id='photo-list']");
		if(count($result)<1){
			throw new YFXMLException("Адресс коллекции фотографий не был получен", E_ERROR);
		}
		$this->photoCollectionUrl = $result[0]->attributes()->href;
	}	

	/**
	 * Аутентифицирует пользователя.
	 * Если пароль не указан, будет использоватся пароль, установленный
	 * при создании экземпляра класса.
	 *
	 * @throws YFUserException|YFXMLException
	 * @param string $password пароль пользователя
	 * @return void
	 */
	public function authenticate($password=null){
			
			if($password!=null){
				$this->password=$password;
			}			
			
			if($this->password===null){
				throw new Exception("Не задан пароль", E_ERROR);
			}
									
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, "http://auth.mobile.yandex.ru/yamrsa/key/");
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_HTTPGET, true);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$xml = curl_exec($curl);
			
			if(($sxml=simplexml_load_string($xml))===false||curl_getinfo($curl, CURLINFO_HTTP_CODE)!=200){
				throw new Exception("RSA-ключ не был получен. Текст ответа: ".$xml, E_ERROR);
			}
			curl_close($curl);
			
			$this->rsaKey = $sxml->key;
			$this->requestId = $sxml->request_id;
			
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, "http://auth.mobile.yandex.ru/yamrsa/token/");
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, 'request_id='.$this->requestId.'&credentials='.$this->encryptYFRSA($this->rsaKey, "<credentials login='".$this->login."' password='".$this->password."'/>"));
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$xml = curl_exec($curl);
			
			if(curl_getinfo($curl, CURLINFO_HTTP_CODE)!=200){
				curl_close($curl);
				if(($sxml=simplexml_load_string($xml))!==false){
					throw new YFXMLException($sxml->error, E_ERROR);
				}
				throw new YFXMLException("Ответ не well-formed XML. Текст ответа: ".$xml, E_ERROR);
			}
			curl_close($curl);
			
			if(($sxml=simplexml_load_string($xml))===false){
				throw new YFXMLException("Ответ не well-formed XML. Текст ответа: ".$xml, E_ERROR);
			}
			
			$this->token = $sxml->token;
	}	

	/**
	 * RSA шифрование со вкусом Яндекса
	 * Это обертка, которая выберет функцию в зависимости от того, какая из библиотек есть в наличии
	 *
	 * @param string $key ключ шифрования
	 * @param string $data данные, которые будут зашифрованы
	 * @return string
	 */
	private function encryptYFRSA($key, $data){
		if(function_exists("gmp_strval")===true) return $this->encryptYFRSAGMP($key, $data);
		return $this->encryptYFRSABCMath($key, $data);
	}
		
	private function bchexdec($hex){
			$dec = 0;
			$len = strlen($hex);
			for ($i = 1; $i <= $len; $i++) {
				$dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
			}
			return $dec;
		}
	
	private function dec2hex($number){
			$hexvalues = array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F');
			$hexval = '';
			while($number != '0'){
				$hexval = $hexvalues[bcmod($number,'16')].$hexval;
				$number = bcdiv($number,'16',0);
			}
		return $hexval;
		}


	/**
	 * RSA шифрование со вкусом Яндекса
	 * Использует BCMath библиотеку
	 *
	 * @param string $key ключ шифрования
	 * @param string $data данные, которые будут зашифрованы
	 * @return string
	 */	
	private function encryptYFRSABCMath($key, $data){
		$buffer = array();
		list($nstr, $estr) = explode('#', $key);
		
		$nv = $this->bchexdec($nstr);
		$ev = $this->bchexdec($estr);
		
		$stepSize = strlen($nstr)/2 - 1;
		$prev_crypted = array();
		$prev_crypted = array_fill(0, $stepSize, 0);
		$hex_out = '';
		for($i=0; $i<strlen($data); $i++){
			$buffer[] = ord($data{$i});
		}
		for($i=0; $i<(int)(((count($buffer)-1)/$stepSize)+1); $i++){
			$tmp = array_slice($buffer, $i * $stepSize, ($i + 1) * $stepSize);
			for ($j=0;$j<count($tmp); $j++){
				$tmp[$j] = ($tmp[$j] ^ $prev_crypted[$j]);
			}				
			$tmp = array_reverse($tmp);
			$plain = "0";
			$pn="0";
			for($x = 0; $x < count($tmp); ++$x){
				$pow = bcpowmod(256,$x,$nv);
				$pow_mult = bcmul($pow,$tmp[$x]);
				$plain = bcadd($plain,$pow_mult);
			}
			$plain_pow = bcpowmod($plain, $ev, $nv);
			$plain_pow_str = strtoupper($this->dec2hex($plain_pow));
			$hex_result = array();
			
			for($k=0;$k<(strlen($nstr)-strlen($plain_pow))+ 1;$k++){
				$hex_result[]="";
			}
			
			$hex_result = implode("0",$hex_result).$plain_pow_str;
			$min_x = min(strlen($hex_result), count($prev_crypted) * 2);
			
			for($x=0;$x<$min_x;$x=$x+2){
				$prev_crypted[$x/2] = hexdec('0x'.substr($hex_result,$x,2));
			}
			if(count($tmp) < 16){
				$hex_out.= '00';
			}
			$hex_out.= strtoupper(dechex(count($tmp)).'00');
			$ks = strlen($nstr) / 2;
			if($ks<16){
				$hex_out.='0';
			}
			$hex_out.= dechex($ks).'00';
			$hex_out.= $hex_result;
		}
		return UrlEncode(base64_encode(pack("H*" , $hex_out)));
	}
	
	/**
	 * RSA шифрование со вкусом Яндекса
	 * Использует GMP библиотеку
	 *
	 * @param string $key ключ шифрования
	 * @param string $data данные, которые будут зашифрованы
	 * @return string
	 */
	private function encryptYFRSAGMP($key, $data){
		$buffer = array();
		
		list($nstr, $estr) = explode('#', $key);
		$n = gmp_init($nstr,16);
		$e = gmp_init($estr,16);
		$stepSize = strlen($nstr)/2 - 1;
		$prev_crypted = array();
		$prev_crypted = array_fill(0, $stepSize, 0);
		$hex_out = '';
	
		for($i=0; $i<strlen($data); $i++){
			$buffer[] = ord($data{$i});
		}
		
		for($i=0; $i<(int)(((count($buffer)-1)/$stepSize)+1); $i++){
			$tmp = array_slice($buffer, $i * $stepSize, ($i + 1) * $stepSize);
			for ($j=0;$j<count($tmp); $j++){
				$tmp[$j] = ($tmp[$j] ^ $prev_crypted[$j]);
			}
			$tmp = array_reverse($tmp);
			$plain = gmp_init(0);
			for($x = 0; $x < count($tmp); ++$x){
				$pow = gmp_powm(gmp_init(256), gmp_init($x), $n);
				$pow_mult = gmp_mul($pow, gmp_init($tmp[$x]));
				$plain = gmp_add($plain, $pow_mult);
			}
			$plain_pow = gmp_powm($plain, $e, $n);
			$plain_pow_str = strtoupper(gmp_strval($plain_pow, 16));
			$hex_result = array();
			for($k=0;$k<(strlen($nstr)-strlen($plain_pow_str))+ 1;$k++){
				$hex_result[]="";
			}
			$hex_result = implode("0",$hex_result).$plain_pow_str;
			$min_x = min(strlen($hex_result), count($prev_crypted) * 2);
			
			for($x=0;$x<$min_x;$x=$x+2){
				$prev_crypted[$x/2] = hexdec('0x'.substr($hex_result,$x,2));
			}
			
			if(count($tmp) < 16){
				$hex_out.= '00';
			}
			$hex_out.= strtoupper(dechex(count($tmp)).'00');
			$ks = strlen($nstr) / 2;
			if($ks<16){
				$hex_out.='0';
			}
			$hex_out.= dechex($ks).'00';
			$hex_out.= $hex_result;
		}
		return UrlEncode(base64_encode(pack("H*" , $hex_out)));
	}
	
	/**
	 * Удаляет объявления пространств имён 
	 * 
	 * @param string $xml
	 * @return string
	 */
	private function deleteXMLNameSpace($xml){
		$pattern = "|(<[/]*)[a-z][^:\s>]*:([^:\s>])[\s]*|sui";
		$replacement="\\1\\2";
		$xml = preg_replace($pattern, $replacement, $xml);
		$pattern = "|(<[/]*[^\s>]+)[-]|sui";
		$replacement="\\1_";
		$xml = preg_replace($pattern,  $replacement, $xml);
		$pattern = "|xmlns[:a-z]*=\"[^\"]*\"|isu";
		$replacement="";
		return preg_replace($pattern, $replacement, $xml);
	}
}