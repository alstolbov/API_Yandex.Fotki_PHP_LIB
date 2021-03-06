<?php
/**
 * @author SilentImp <ravenb@mail.ru>
 * @link http://twitter.com/SilentImp/
 * @link http://silentimp.habrahabr.ru/
 * @link http://code.websaints.net/
 * @package YandexFotki
 */
 
/**
 * Класс, который позволяет вам работать с фотографией
 *
 * @throws YFException|YFRequestException|YFXMLException
 * @package YandexFotki
 * @author SilentImp <ravenb@mail.ru>
 * @link http://twitter.com/SilentImp/
 * @link http://silentimp.habrahabr.ru/
 * @link http://code.websaints.net/
 */
class YFPhoto {

	/**
	 * Идентификатор Atom Entry фотографии.
	 * @var string
	 * @access protected
	 */
	protected $id=null;

	/**
	 * Cодержит информацию о владельце фотографии. На данный момент информация ограничивается логином пользователя на Яндексе, который указывается во вложенном теге atom:name
	 * @var string
	 * @access protected
	 */
	protected $summary=null;

	/**
	 * Cодержит информацию о владельце фотографии. На данный момент информация ограничивается логином пользователя на Яндексе, который указывается во вложенном теге atom:name
	 * @var string
	 * @access protected
	 */
	protected $author=null;

	/**
	 * Название фотографии
	 * @var string
	 * @access protected
	 */
	protected $title=null;
	
	/**
	 * Дата создания фотографии согласно ее EXIF-данным. Формат времени соответствует RFC3339 без указания часового пояса.
	 * @var string
	 * @access protected
	 */
	protected $createdOn=null;

	/**
	 * Время загрузки фотографии. Формат времени соответствует RFC3339.
	 * @var string
	 * @access protected
	 */
	protected $publishedOn=null;
	
	/**
	 * Время последнего редактирования фотографии. Формат времени соответствует RFC3339.
	 * @var string
	 * @access protected
	 */
	protected $editedOn=null;
		
	/**
	 * Время последнего значимого с точки зрения системы изменения альбома (в текущей версии API Фоток любое изменение считается значимым, вследствие чего значение atom:updated совпадает с app:edited. Формат времени соответствует RFC3339.
	 * @var string
	 * @access protected
	 */
	protected $updatedOn=null;
	
	/**
	 * Уровень доступа к фотографии
	 * @var string
	 * @access protected
	 */
	protected $accessLevel=null;
	
	/**
	 * Флаг доступности фотографии только взрослой аудитории. Данный параметр может быть установлен только один раз: после того, как фото было помечено "только для взрослых", сбросить данную установку будет невозможно.
	 * @var boolean
	 * @access protected
	 */
	protected $isAdultPhoto=null;
	
	/**
	 * Флаг, запрещающий показ оригинала фотографии.
	 * @var boolean
	 * @access protected
	 */	
	protected $hideOriginalPhoto=null;
	
	/**
	 * Флаг, запрещающий комментирование фотографии.
	 * @var boolean
	 * @access protected
	 */
	protected $commentsDisabled=null;

	/**
	 * Ссылка на графический файл фотографии. Для каждой фотографии создается несколько графических файлов разного размера. Тут хранится XL
	 * @var string
	 * @access protected
	 */
	protected $content=null;

	/**
	 * Ссылка на графический файл фотографии. Для каждой фотографии создается несколько графических файлов разного размера. Тут хранится изображение в оригинальном размере
	 * @var string
	 * @access protected
	 */
	protected $photoOriginalUrl=null;

	/**
	 * Ссылка на графический файл фотографии. Для каждой фотографии создается несколько графических файлов разного размера. Тут хранится изображение шириной в 800px
	 * @var string
	 * @access protected
	 */
	protected $photoXLUrl=null;
	
	/**
	 * Ссылка на графический файл фотографии. Для каждой фотографии создается несколько графических файлов разного размера. Тут хранится изображение шириной в 500px
	 * @var string
	 * @access protected
	 */
	protected $photoLUrl=null;
	
	/**
	 * Ссылка на графический файл фотографии. Для каждой фотографии создается несколько графических файлов разного размера. Тут хранится изображение шириной в 300px
	 * @var string
	 * @access protected
	 */
	protected $photoMUrl=null;
	
	/**
	 * Ссылка на графический файл фотографии. Для каждой фотографии создается несколько графических файлов разного размера. Тут хранится изображение шириной в 150px
	 * @var string
	 * @access protected
	 */
	protected $photoSUrl=null;
	
	/**
	 * Ссылка на графический файл фотографии. Для каждой фотографии создается несколько графических файлов разного размера. Тут хранится изображение шириной в 100px
	 * @var string
	 * @access protected
	 */
	protected $photoXSUrl=null;
	
	/**
	 * Ссылка на графический файл фотографии. Для каждой фотографии создается несколько графических файлов разного размера. Тут хранится изображение шириной в 75px
	 * @var string
	 * @access protected
	 */
	protected $photoXXSUrl=null;
	
	/**
	 * Ссылка на графический файл фотографии. Для каждой фотографии создается несколько графических файлов разного размера. Тут хранится изображение шириной в 50px
	 * @var string
	 * @access protected
	 */
	protected $photoXXXSUrl=null;
	
	/**
	 * Ссылка на ресурс фотографии. Данная ссылка может, например, понадобиться для обращения к ресурсу, если его описание было получено в составе коллекции.
	 * @var string
	 * @access protected
	 */
	protected $selfUrl = null;
	
	/**
	 * Ccылка для редактирования ресурса фотографии.
	 * @var string
	 * @access protected
	 */
	protected $editUrl = null;
	
	/**
	 * Ссылка на web-страницу фотографии в интерфейсе Яндекс.Фоток
	 * @var string
	 * @access protected
	 */
	protected $webUrl = null;

	/**
	 * Ссылка для редактирования содержания ресурса фотографии (графического файла). Для каждой фотографии создается несколько графических файлов разного размера. Для доступа к фотографии в другом размере нужно изменить суффикс в вышеприведенном URL (см. Хранение графического файла фотографии).
	 * @var string
	 * @access protected
	 */
	protected $editMediaUrl = null;

	/**
	 * Ссылка на альбом, в котором содержится фотография.
	 * @var string
	 * @access protected
	 */
	protected $albumUrl = null;
	

	/**
	 * Флаг того, что фотография была удалена
	 * @var string
	 * @access protected
	 */
	protected $isDeleted = false;

	/**
	 * Возвращает идентификатор Atom Entry фотографии. Идентификатор является глобально уникальным и позволяет клиентскому приложению однозначно определить некоторый Atom Entry (например, с целью выявления дубликатов при постраничной выдаче коллекций).
	 * @return string
	 * @access public
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * Возвращает  текстовое описание
	 * @return string
	 * @access public
	 */
	public function getDesc() {
		return $this->summary;
	}

	/**
	 * Возвращает  информацию о владельце фотографии. На данный момент информация ограничивается логином пользователя на Яндексе, который указывается во вложенном теге atom:name
	 * @return string
	 * @access public
	 */
	public function getAuthor(){
		return $this->author;
	}

	/**
	 * Возвращает название фотографии
	 * @return string
	 * @access public
	 */
	public function getTitle(){
		return $this->title;
	}

	/**
	 * Возвращает время загрузки фотографии. Формат времени соответствует RFC3339.
	 * @return string
	 * @access public
	 */
	public function getPublishedOn(){
		return $this->publishedOn;
	}

	/**
	 * Дата создания фотографии согласно ее EXIF-данным. Формат времени соответствует RFC3339 без указания часового пояса.
	 * @return string
	 * @access public
	 */
	public function getCreatedOn(){
		return $this->createdOn;
	}

	/**
	 * Возвращает время последнего редактирования фотографии.
	 * @return string
	 * @access public
	 */
	public function getEditedOn(){
		return $this->editedOn;
	}

	/**
	 * Возвращает время последнего значимого с точки зрения системы изменения альбома (в текущей версии API Фоток любое изменение считается значимым, вследствие чего значение atom:updated совпадает с app:edited. Формат времени соответствует RFC3339.
	 * @return string
	 * @access public
	 */
	public function getUpdatedOn(){
		return $this->updatedOn;
	}

	/**
	 * Возвращает уровень доступа к фотографии "Для всех" (по умолчанию) Фотографию может увидеть любой желающий, даже не авторизованный на Яндекс.Фотках. "Для друзей" Фотография доступна загрузившему ее пользователю и всем его "друзьям". Используется совместная с Я.ру система "друзей". "Для себя" Фотографию может просматривать только загрузивший ее пользователь.
	 * @return string
	 * @access public
	 */
	public function getAccessLevel(){
		return $this->accessLevel;
	}

	/**
	 * Возвращает флаг доступности фотографии только взрослой аудитории. Данный параметр может быть установлен только один раз: после того, как фото было помечено "только для взрослых", сбросить данную установку будет невозможно.
	 * @return boolean
	 * @access public
	 */
	public function isAdultPhoto(){
		return $this->isAdultPhoto;
	}

	/**
	 * Возвращает флаг, запрещающий показ оригинала фотографии.
	 * @return boolean
	 * @access public
	 */
	public function getHideOriginalPhoto(){
		return $this->hideOriginalPhoto;
	}

	/**
	 * Возвращает флаг, запрещающий комментирование фотографии.
	 * @return boolean
	 * @access public
	 */
	public function commentsDisabled(){
		return $this->commentsDisabled;
	}

	/**
	 * Возвращает  ссылку на графический файл фотографии.  Для каждой фотографии создается несколько графических файлов разного размера. Тут хранится XL.
	 * @return string
	 * @access public
	 */
	public function getContent(){
		return $this->content;
	}

	/**
	 * Возвращает ссылку на графический файл фотографии в оргинальном размере.
	 * @return string
	 * @access public
	 */
	public function getPhotoOriginalUrl(){
		return $this->photoOriginalUrl;
	}

	/**
	 * Возвращает ссылку на графический файл фотографии с шириной 800px.
	 * @return string
	 * @access public
	 */
	public function getPhotoXLUrl(){
		return $this->photoXLUrl;
	}

	/**
	 * Возвращает ссылку на графический файл фотографии с шириной 500px.
	 * @return string
	 * @access public
	 */
	public function getPhotoLUrl(){
		return $this->photoLUrl;
	}

	/**
	 * Возвращает ссылку на графический файл фотографии с шириной 300px.
	 * @return string
	 * @access public
	 */
	public function getPhotoMUrl(){
		return $this->photoMUrl;
	}

	/**
	 * Возвращает ссылку на графический файл фотографии с шириной 150px.
	 * @return string
	 * @access public
	 */
	public function getPhotoSUrl(){
		return $this->photoSUrl;
	}

	/**
	 * Возвращает ссылку на графический файл фотографии с шириной 100px.
	 * @return string
	 * @access public
	 */
	public function getPhotoXSUrl(){
		return $this->photoXSUrl;
	}

	/**
	 * Возвращает ссылку на графический файл фотографии с шириной 75px.
	 * @return string
	 * @access public
	 */
	public function getPhotoXXSUrl(){
		return $this->photoXXSUrl;
	}

	/**
	 * Возвращает ссылку на графический файл фотографии с шириной 50px.
	 * @return string
	 * @access public
	 */
	public function getPhotoXXXSUrl(){
		return $this->photoXXXSUrl;
	}

	/**
	 * Возвращает ссылку на ресурс фотографии. Данная ссылка может, например, понадобиться для обращения к ресурсу, если его описание было получено в составе коллекции.
	 * @return string
	 * @access public
	 */
	public function getSelfUrl(){
		return $this->selfUrl;
	}

	/**
	 * Возвращает ссылку для редактирования ресурса фотографии.
	 * @return string
	 * @access public
	 */
	public function getEditUrl(){
		return $this->editUrl;
	}

	/**
	 * Возвращает ссылку на web-страницу фотографии в интерфейсе Яндекс.Фоток
	 * @return string
	 * @access public
	 */
	public function getWebUrl(){
		return $this->webUrl;
	}

	/**
	 * Возвращает ссылку для редактирования содержания ресурса фотографии (графического файла). Для каждой фотографии создается несколько графических файлов разного размера. Для доступа к фотографии в другом размере нужно изменить суффикс в вышеприведенном URL (см. Хранение графического файла фотографии).
	 * @return string
	 * @access public
	 */
	public function getEditMediaUrl(){
		return $this->editMediaUrl;
	}

	/**
	 * Возвращает ссылку на альбом, в котором содержится фотография.
	 * @return string
	 * @access public
	 */
	public function getAlbumUrl(){
		return $this->albumUrl;
	}

	/**
	 * Проверяет был ли альбом удален.
	 * 
	 * Вернет FALSE если альбом не был удален и TRUE если альбом был удален вызовом метода delete
	 *
	 * @return boolean
	 * @access public
	 */
	public function isDeleted(){
		return $this->isDeleted;
	}

	/**
	 * Конструктор фотографии
	 * @param string $xml Atom Entry фотографии
	 * @param string $token токен, подтверждающий аутентификацию пользователя. Не обязательный аргумент. Если не задан, то нельзя будет удалить или отредактировать фотографию, если не введете токен в функцию редактирования или удаления
	 * @return void
	 * @access public
	 */
	public function __construct($xml, $token=null){
		libxml_use_internal_errors(true);
		$this->token=$token;
		$this->reloadXml($xml);
	}

	/**
	 * Удаляет фотографию. В случае успешного удаления фотография будет помечена, как удаленная. Провреить удлаен ли объект можно с помощью метода is_dead
	 * 
	 * @throws YFException|YFRequestException
	 * @param string $token токен, подтверждающий аутентификацию пользователя. Не обязательный аргумент. Если не задан, то нельзя будет удалить или отредактировать фотографию, если не введете токен в функцию редактирования или удаления
	 * @return void
	 * @access public
	 */
	public function delete($token){
		
		if($token!==null){
			$this->token = $token;
		}
		
		if($this->token==null){
			throw new YFException("Эта операция доступна только для аутентифицированных пользователей", E_ERROR,null,"authenticationNeeded");
		}
		
		$connect = new YFConnect();
		$connect->setUrl($this->editUrl);
		$connect->setToken($this->token);
		$connect->setDelete();
		$connect->exec();
		$code = $connect->getCode();
		$xml = $connect->getResponce();
		unset($connect);
		
		switch((int)$code){
			case 204:
				//если код не 204 и не оговоренные в документации Яндекс ошибки, то будет вызвано прерывание общего типа.
				break;
			case 401:
				throw new YFRequestException($xml, $code, null, "unauthorized");
				break;
			case 403:
				throw new YFRequestException($xml, $code, "Аутентифицированный пользователь попытался что-либо изменить в чужом альбоме.", "forbidden");
				break;
			case 500:
				throw new YFRequestException($xml, $code, "Сервер не смог обработать запрос.","internalServerError");
				break;
			default:
				throw new YFRequestException($xml, $code);
				break;
		}
		
		$this->isDeleted=true;
	}

	/**
	 * Метод является оберткой для edit и должен упростить работу с его аргументами.
	 * 
	 * @param array $args ассоциативный массив, в котором хранятся аргументы, значения которых отличаются от значений по умолчанию. Ключи ассоциативного массива: title, xxx, comments, hide, access, album, token. Точное описание аргументов смотрите в описании метода edit
	 * @return void
	 * @access public
	 */
	public function edit($args=array()){

		if(array_key_exists("token", $args)){
			$token=$args["token"];
		}else{
			$token=null;
		}

		if(array_key_exists("album", $args)){
			$album_url=$args["album"];
		}else{
			$album_url=null;
		}

		if(array_key_exists("access", $args)){
			$access=$args["access"];
		}else{
			$access="public";
		}

		if(array_key_exists("hide", $args)){
			$hide_original=$args["hide"];
		}else{
			$hide_original=false;
		}

		if(array_key_exists("comments", $args)){
			$disable_comments=$args["comments"];
		}else{
			$disable_comments=false;
		}

		if(array_key_exists("xxx", $args)){
			$xxx=$args["xxx"];
		}else{
			$xxx=false;
		}

		if(array_key_exists("title", $args)){
			$title=$args["title"];
		}else{
			$title=null;
		}

		$this->editEx($title, $xxx, $disable_comments, $hide_original, $access, $album_url, $token);
	}

	/**
	 * Редактирует свойства фотографии
	 * 
	 * @throws YFRequestException|YFException|YFXMLErrorException
	 * @param string $title Название фотографии.
	 * @param boolean $xxx Флаг «для взрослых», write-only (можно только установить, снять нельзя). Значение по умолчанию: "false".
	 * @param boolean $disable_comments Флаг запрета комментариев. Значение по умолчанию: "false".
	 * @param boolean $hide_original Флаг запрета публичного доступа к оригиналу фотографии. Значение по умолчанию: "false". Если данный флаг установлен в "true", автор не сможет получить оригинал фотографии при помощи API Фоток. Для этого нужно воспользоваться возможностями сервиса Яндекс.Фотки.
	 * @param string $access Уровень доступа к фотографии. Значение по умолчанию: "public" ("Для всех").
	 * @param string $album_url Ссылка на альбом, в котором содержится фотография. Нужно для перемещение фотографии между альбомами.
	 * @param string $token Токен, подтверждающий аутентификацию пользователя. Если не задан, используется токен, который был передан конструктору. Если не задан и он, то метод вызовет исключение.
	 * @return void
	 * @access public
	 */
	public function editEx($title=null, $xxx=false, $disable_comments=false, $hide_original=false, $access="public", $album_url=null, $token=null){

		$changes = false;

		if($token!==null){
			$this->token = $token;
		}
		
		if($this->token===null){
			throw new YFException("Эта операция доступна только для аутентифицированных пользователей", E_ERROR,null,"authenticationNeeded");
		}

		if($title!=null&&$title!=$this->title){
			$this->title = htmlentities($title,ENT_COMPAT,"UTF-8");
			$changes = true;
		}

		if((boolean)$xxx!=$this->isAdultPhoto){
			$this->isAdultPhoto = (boolean)$xxx;
			$changes = true;
		}

		if((boolean)$disable_comments!=$this->commentsDisabled){
			$this->commentsDisabled = (boolean)$disable_comments;
			$changes = true;
		}

		if((boolean)$hide_original!=$this->hideOriginalPhoto){
			$this->hideOriginalPhoto = (boolean)$hide_original;
			$changes = true;
		}

		if(!in_array($access, array("public","friends","private"))){
			$access="public";
		}

		if($access!=$this->accessLevel){
			$this->accessLevel = $access;
			$changes = true;
		}

		if($album_url!=null&&$album_url!=$this->albumUrl){
			$this->albumUrl = htmlentities($album_url,ENT_COMPAT,"UTF-8");
			$changes = true;
		}

		if($changes === false){
			throw new YFException("Никаких изменений сделано не было", E_ERROR, null, "noDifference");
		}

					$message = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><entry xmlns="http://www.w3.org/2005/Atom" xmlns:app="http://www.w3.org/2007/app" xmlns:f="yandex:fotki"/>');
					$message->addChild('id',$this->id);
					$message->addChild('title',$this->title);
					$message->addChild('author');
					$message->author->addChild('name',$this->author);
					$message->addChild('summary');
					$message->summary->addChild('name',$this->summary);
					$message->addChild('link');
					$message->link->addAttribute("href",$this->selfUrl);
					$message->link->addAttribute("rel","self");
					$message->addChild('link');
					$message->link[1]->addAttribute("href",$this->editUrl);
					$message->link[1]->addAttribute("rel","edit");
					$message->addChild('link');
					$message->link[2]->addAttribute("href",$this->webUrl);
					$message->link[2]->addAttribute("rel","alternate");
					$message->addChild('link');
					$message->link[3]->addAttribute("href",$this->editMediaUrl);
					$message->link[3]->addAttribute("rel","edit-media");
					$message->addChild('link');
					$message->link[4]->addAttribute("href",$this->albumUrl);
					$message->link[4]->addAttribute("rel","album");
					$message->addChild('published',$this->publishedOn);
					$message->addChild('edited',$this->editedOn,"http://www.w3.org/2007/app");
					$message->addChild('updated',$this->updatedOn);
					$message->addChild('created',$this->createdOn,"yandex:fotki");
					$tmp = $message->addChild('access',null,"yandex:fotki");
					$tmp->addAttribute("value",$this->accessLevel);
					$tmp = $message->addChild('xxx',null,"yandex:fotki");
					$tmp->addAttribute("value",$this->isAdultPhoto);
					$tmp = $message->addChild('hide_original',null,"yandex:fotki");
					$tmp->addAttribute("value",$this->hideOriginalPhoto);
					$tmp = $message->addChild('disable_comments',null,"yandex:fotki");
					$tmp->addAttribute("value",$this->commentsDisabled);
					$message->addChild('content');
					$message->content->addAttribute("src",$this->content);
					$message->content->addAttribute("type","image/*");

		fwrite($putData, $message->asXML());
		fseek($putData, 0);
		
		$connect = new YFConnect();
		$connect->setUrl($this->editUrl);
		$connect->setToken($this->token);
		$connect->setPutFile($putData,strlen($message->asXML()));
		$connect->addHeader('Content-Type: application/atom+xml; charset=utf-8; type=entry');
		$connect->addHeader('Expect:');
		$connect->exec();
		$code = $connect->getCode();
		$xml = $connect->getResponce();
		unset($connect);
		
		fclose($putData);
	
		switch((int)$code){
			case 200:
				//если код не 200 и не оговоренные в документации Яндекс ошибки, то будет вызвано прерывание общего типа.
				break;
			case 400:
				throw new YFRequestException($xml, $code, "Переданный клиентским приложением XML не является валидным Atom Entry фотографии или содержит пустой параметр atom:title.", "badRequest");
				break;
			case 401:
				throw new YFRequestException($xml, $code, null, "unauthorized");
				break;
			case 403:
				throw new YFRequestException($xml, $code, "Для доступа к альбому требуется пароль.", "forbidden");
				break;
			case 404:
				throw new YFRequestException($xml, $code, "Такого пользователя или альбома не существует.","userOrAlbumNotFound");
				break;
			case 415:
				throw new YFRequestException($xml, $code, "Заголовок Content-Type содержит тип, отличный от типа Atom Entry.","atomEntryNotFound");
				break;
			case 500:
				throw new YFRequestException($xml, $code, "Сервер не смог обработать запрос.","internalServerError");
				break;
			default:
				throw new YFRequestException($xml, $code);
				break;
		}

		$this->refresh();
	}

	/**
	 * Обновляет свойства фотографии
	 * 
	 * @throws YFRequestException|YFXMLErrorException
	 * @return void
	 * @access public
	 */
	public function refresh(){
		
		$connect = new YFConnect();
		$connect->setUrl($this->editUrl);
		if($this->token!=null){
			$connect->setToken($this->token);
		}
		$connect->exec();
		$code = $connect->getCode();
		$xml = $connect->getResponce();
		unset($connect);
		
		switch((int)$code){
			case 200:
				//если код не 200 и не оговоренные в документации Яндекс ошибки, то будет вызвано прерывание общего типа.
				break;
			case 401:
				throw new YFRequestException($xml, $code, null, "unauthorized");
				break;
			case 403:
				throw new YFRequestException($xml, $code, "Аутентифицированный пользователь попытался что-либо изменить в чужом альбоме", "forbidden");
				break;
			case 404:
				throw new YFRequestException($xml, $code, "Такого пользователя или альбома не существует.","userOrAlbumNotFound");
				break;
			case 500:
				throw new YFRequestException($xml, $code, "Сервер не смог обработать запрос.","internalServerError");
				break;
			default:
				throw new YFRequestException($xml, $code);
				break;
		}
		$this->reloadXml($this->deleteXmlNamespace($xml));
	}

	/**
	 * Получив на вход XML на ее основе перезаписывает свойства текущего экземпляра классса
	 * 
	 * @throws YFXMLErrorException
	 * @param string $xml XML содержаий описание фотографии в формате атома
	 * @return void
	 * @access private
	 */
	private function reloadXml($xml){
		//Не проверяется формат XML. Вот неясно стоит ли его проверять или нет.
		$this->xml = '<?xml version="1.0" encoding="UTF-8"?>'.$xml;

		if(($sxml=simplexml_load_string($xml))===false){
			throw new YFXMLException($xml, E_ERROR,"Не удалось распознать ответ Яндекс как валидный XML документ","canNotCreateXML");
		}
		
		$this->id = $sxml->id;
		$this->author = $sxml->author->name;
		$this->summary = $sxml->summary;
		$this->title = $sxml->title;
		$this->publishedOn = $sxml->published;
		$this->editedOn = $sxml->edited;
		$this->createdOn = $sxml->created;
		$this->updatedOn = $sxml->updated;

		if($sxml->xxx->attributes()->value=="false"){
			$this->isAdultPhoto = false;
		}else{
			$this->isAdultPhoto = true;
		}

		if($sxml->hide_original->attributes()->value=="false"){
			$this->hideOriginalPhoto = false;
		}else{
			$this->hideOriginalPhoto = true;
		}

		if($sxml->disable_comments->attributes()->value=="false"){
			$this->commentsDisabled = false;
		}else{
			$this->commentsDisabled = true;
		}

		$this->accessLevel  = $sxml->access->attributes()->value;
		$this->content = $sxml->content->attributes()->src;

		$photos_resourse = substr($sxml->content->attributes()->src,0,strlen($sxml->content->attributes()->src)-4);
		$this->photoOriginalUrl = $photos_resourse."orig";
		$this->photoXLUrl = $sxml->content->attributes()->src;
		$this->photoLUrl = $photos_resourse."L";
		$this->photoMUrl = $photos_resourse."M";
		$this->photoSUrl = $photos_resourse."S";
		$this->photoXSUrl = $photos_resourse."XS";
		$this->photoXXSUrl = $photos_resourse."XXS";
		$this->photoXXXSUrl = $photos_resourse."XXXS";

		foreach($sxml->link as $link){
			switch($link->attributes()->rel){
				case "self":
					$this->selfUrl = $link->attributes()->href;
					break;
				case "edit":
					$this->editUrl = $link->attributes()->href;
					break;
				case "edit-media":
					$this->editMediaUrl = $link->attributes()->href;
					break;
				case "album":
					$this->albumUrl = $link->attributes()->href;
					break;
				case "alternate":
					$this->webUrl = $link->attributes()->href;
					break;
			}
		}
	}
}