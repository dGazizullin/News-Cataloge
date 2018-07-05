<?php
/*
Каждая категория имеет свой ID, задаваемый функцией setAuthId(принимает целочисленные значения - иначе- сообщение об ошибке).  

У категории есть свойство, содержащее массив catInс, где хранятся ID катерогий, включенных в данную категорию. Массив push'ится методом setCatInc(целочисленный ID)*/
//some changes test
//test2

//										author
 class Author
 {
	public $authId;
	public $lastName;
	public $firstName;
	public $patronimic;
	public $avatarPath;
	public $sign;

	public function __construct($authId, $lastName = null, $firstName, $patronimic, $avatarPath, $sign) 
 	{
 		//зануляем свойства, необязательные к присвоению новому экземпляру (делать это здесь или в параметрах, принимаемых констрактом?)
 		setAuthId($authId);
 		setFirstName($firstname);
 		setLastName($lastname = null);
 		setPatronimic($patronimic = null);
 		setAvPath($avatarPath = null);
 		setSign($sign = null);
 	}

	public function getAuthId()
	{
		return(int) $this->$authId;
	}

	public function setAuthId(int $authId)
	{
		$this->authId = $authId;
	}

	public function getLastName() 
	{
		return(string) $this->lastName;
	}
	public function setLastName(string $lastName) {
		$this->lastName = $lastName;
	}

 	public function getFirstName() 
 	{
		return(string) $this->firstName;
	}
	public function setFirstName(string $firstName) 
	{
		$this->firstName = $firstName;
	}

 	public function getPatronimic()
 	{
 		return(string) $this->patronimic;
 	}
 	public function setPatronimic(string $patronimic)
 	{
 		$this->patronimic = $patronimic;
	 }

	  public function getAvPath()
	 {
	 	return(string) $this->avatarPath;
	 }

	 public function setAvPath(string $avatarPath)
	 {
	 	$this->avatarPath = $avatarPath;
	 }
	 public function getSign()
	 {
	 	return(string) $this->sign;
	 }
	 	public function setSign(string $sign)
	 {
	 	$this->sign = $sign;
	 }
 }

 class News {
 	public $newsId;
 	public $header;
 	public $announcement;
 	public $text;
 	private $authId;
	public $catIdArr = array();
	public function __construct($newsId, $header, $announcement, $text, $authorId, $catIdArr)
 	{
 		setNewsID($newsId);
 		setHeader($header);
 		setAnnouncement($announcement = null);
 		setText($text);
 		setAuth($authId);
 		setCatInc($catInc = null);
 	}

 	public function getNewsID()
 	{
 		return(int) $this->newsId;
 	}
 	public function setNewsID(int $setNewsID){
 			$this->id = $newsId;
 	}

 	public function getHeader(){
 		return(string) $this->header;
 	}
 	public function setHeader(string $header){
 			$this->header = $header;
 	}

 	public function getAnnouncement()
 	{
 		return(string) $this->announcement;
 	}
 	public function setAnnouncement(string $announcement)
 	{
 			$this->announcement = $announcement;
 	}

 	public function getText(){
 		return(string) $this->text;
 	}
 	public function setText(string $text){
 			$this->text = $text;
 	}

 	//single author
 	public function getAuth()
 	{
 		return(int) $this->authId;
 	}
 	public function setAuth(int $authId) {
 			$this->authId = $authId;
 	}

 	//categories
	public function getCat()
	{
		return(array) $catIdArr;
	}
	public function addToCat(int $catId){
		$this->catIdArr[] = $catId;
	}
}
//										category
 class Category {
 	public $catId;
 	public $catName;
 	public $catInc = array();
 	public function __construct($catId, $catName, $catInc)
 	{
 		setCatId($catId);
 		setCatName($catName);
 		setCatId($catInc = null);
 	}

 	public function getCatId(){
 		return(int) $this->catId;
 	}
 	public function setCatId(int $catId){
 			$this->catId = $catId;
 	}

 	public function getCatName()
 	{
 		return(string) $this->catName;
 	}
 	public function setCatName(string $catName)
 	{
 		$this->catName = $catName;
 	}

 	//given category includes categories, who's ID's are in array 
 	public function getCatInc()
 	{
 		return(array) $catInc;
 	}
 	public function setCatInc(int $catId)
 	{
 		$this->catInc[] = $catId;
 	}
 }