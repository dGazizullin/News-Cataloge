<?php
/*
Каждая категория имеет свой ID, задаваемый функцией setAuthId(принимает целочисленные значения - иначе- сообщение об ошибке).  

У категории есть свойство, содержащее массив catInс, где хранятся ID катерогий, включенных в данную категорию. Массив push'ится методом setCatInc(целочисленный ID)*/
//some changes test

//										author
 class author
 {
	public $authId;
	public $lastName;
	public $firstName;
	public $patronimic;
	public $avatarPath;
	public $sign;

	public function __construct($lastName, $firstName, $patronimic, $av, $sign) 
 	{
 		$this->authId = $authId;
 		$this->lastName = $lastname;
 		$this->firstname = $firstname;
 		$this->patronimic = $patronimic;
 		$this->avatarPath = $avatarPath;
 		$this->sign = $sign;
 	}

	public static function getAuthID()
	{
		return self::$authId;
	}

	public function setAuthID($authId)
	{
		if(is_integer($this->authId))
		{
		$this->authId = $authId;
		}else
		{
		echo $authId . " isn't integer.";
		}
	}

	public function getLastName() 
	{
		return $this->lastName;
	}
	public function setLastName($lastName) {
		if(is_string($lastName)){
			$this->lastName = $lastName;
		}else {
			echo $lastName . " isn't string.";
		}
	}

 	public function getFirstName() 
 	{
		return $this->firstName;
	}
	public function setFirstName($firstName) 
	{
		if(is_string($firstName))
		{
		$this->firstName = $firstName;
		}else 
		{
		echo $firstN . "isn't string";
		}
	}

 	public function getPatronimic()
 	{
 		return $this->patronimic;
 	}
 	public function setPatronimic($patronimic)
 	{
 		if(is_string($patronimic))
 		{
 			$this->patronimic = $patronimic;
 		}else 
 		{
			echo $patronimic . " isn't string.";
		}
	 }

	  public function getAvPath()
	 {
	 	return $this->avatarPath;
	 }

	 public function setAvPath($avatarPath)
	 {
	 	if(is_string($avatarPath))
	 	{
	 		$this->avatarPath = $avatarPath;
	 	}else
	 	{
	 		echo $avatarPath . " isn't string.";
	 	}
	 }

	public function setSign($sign)
	 {
	 	if(is_string($sign))
	 	{
	 		$this->sign = $sign;
	 	}else
	 	{
	 		echo $sign . " isn't string.";
	 	}
	 }
	 public function getSign()
	 {
	 	return $this->sign;
	 }
 }

 class news {
 	public $newsId;
 	public $header;
 	public $announcement;
 	public $text;
 	private $authId;
	public $catIdArr = array();
	public function __construct($newsId, $header, $announcement, $text, $authorId, $catIdArr)
 	{
 		$this->newsId = $newsId;
 		$this->header = $header;
 		$this->announcement = $announcement;
 		$this->text = $text;
 		$this->authId = $authId;
 		$this->catIdArr = $catIdArr;
 	}

 	public function getNewsID()
 	{
 		return $this->newsId;
 	}
 	public function setNewsID($setNewsID){
 		if(is_integer($newsId))
 		{
 			$this->id = $newsId;
 		}else 
 		{
 			echo $newsId . " isn't integer";
 		}
 	}

 	public function getHeader(){
 		return $this->header;
 	}
 	public function setHeader($header){
 		if(is_string($header))
 		{
 			$this->header = $header;
 		}else
 		{
 			echo $header . " ins't string";
 		}
 	}

 	public function getAnnouncement()
 	{
 		return $this->announcement;
 	}
 	public function setAnnouncement($announcement)
 	{
 		if(is_string($announcement))
 		{
 			$this->announcement = $announcement;
 		}else 
 		{
 			echo $announcement . " isn't string.";
 		}
 	}

 	public function getText(){
 		return $this->text;
 	}
 	public function setText($text){
 		if(is_string($text))
 		{
 			$this->text = $text;
 		}else
 		{
 			echo $echo . " isn't string";
 		}
 	}

 	//single author
 	//get author ID
 	//public $authorId = author::getAuthID();		//CHECK OUT AN ERROR!!!!!!!!
 	public function getAuth()
 	{
 		return $this->authId;
 	}
 	public function setAuth($authId) {
 		if(is_integer($authId))
 		{
 			$this->authId = $authId;
 		}else
 		{
 			echo $this->authId . " isn't integer.";
 		}
 	}

 	//categories
public function getCat()
	{
		return $catIdArr;
	}
	public function addToCat($catId){
		if(is_integer($catId))
		{
			array_push($catIdArr, $catId);
		}else
		{
			echo $catId . "isn't integer";
		}
	}
}
//										category
 class category {
 	public $catId;
 	public $catName;
 	public $catInc = array();
 	public function __construct($catId, $catName, $catInc)
 	{
 		$this->catId = $catId;
 		$this->catName = $catName;
 		$this->catInc = $catInc;
 	}

 	public function getCatId(){
 		return $this->catId;
 	}
 	public function setCatId($catId){
 		if(is_integer($catId))
 		{
 			$this->catId = $catId;
 		}else
 		{
 			echo $catId / " isn't integer.";
 		}
 	}

 	public function getCatName()
 	{
 		return $this->catName;
 	}
 	public function setCatName($catName)
 	{
 		if(is_string($catName))
 		{
 			$this->catName = $catName;
 		}else
 		{
 			echo $catName . " isn't string.";
 		}
 	}

 	//given category includes categories, who's ID's are in array 
 	public function getCatInc()
 	{
 		return $catInc;
 	}
 	public function setCatInc($catId)
 	{
 		if(is_integer($catId))
 		{
 			array_push($catInc, $catId);
 		}else
 		{
 			echo $catId . " isn't integer";
 		}	
 	}
 }