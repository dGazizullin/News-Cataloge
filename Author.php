<?php
include_once "DB.php";
class Author
{
	public $lastName;
	public $firstName;
	public $patronimic;
	public $avatarPath;
	public $sign;
	private $DB;
	public function __construct($lastName = "", $firstName = "", $patronimic = "", $avatarPath = "", $sign = "") 
	{
		if(!empty($lastName) && !empty($firstName) && !empty($patronimic) && !empty($avatarPath) && !empty($sign))
		{
			$this->setLastName($lastName);
			$this->setFirstName($firstName);
			$this->setPatronimic($patronimic);
			$this->setAv($avatarPath);
			$this->setSign($sign);
		}
		$this->DB = new DB();
	}

	public function getLastName()
	{
		return $this->lastName;
	}

	public function setLastName(string $lastName) 
	{
		$this->lastName = $lastName;
	}

	public function getFirstName() 
	{
		return $this->firstName;
	}

	public function setFirstName(string $firstName) 
	{
		$this->firstName = $firstName;
	}

	public function getPatronimic()
	{
		return $this->patronimic;
	}

	public function setPatronimic(string $patronimic)
	{
		$this->patronimic = $patronimic;
	}

	public function getAv()
	{
		return $this->avatarPath;
	}

	public function setAv(string $avatarPath)
	{
		$this->avatarPath = $avatarPath;
	}

	public function getSign()
	{
		return $this->sign;
	}

	public function setSign(string $sign)
	{
		$this->sign = $sign;
	}
	
	public function getList($limit = 2)
	{
		if(intval($limit) > 0)
		{
			$list = [];
			$arRes = $this->DB->query("SELECT * FROM authors LIMIT $limit");
			foreach ($arRes as $res)
			{
				$list[] = new self($res['LASTNAME'], $res['FIRSTNAME'], $res['PATRONIMIC'], $res['AVATAR'], $res['SIGN']); 
			}
			return $list;
		}
		return false;
	}

	public function getByID($ID = 0)
	{
		if($ID > 0)
		{
			$arRes = $this->DB->query("SELECT * FROM authors WHERE ID = '$ID'");
			foreach ($arRes as $res)
			{
				return new self($res['LASTNAME'], $res['FIRSTNAME'], $res['PATRONIMIC'], $res['AVATAR'], $res['SIGN']);
			}
		}
		return false;
	}
}
