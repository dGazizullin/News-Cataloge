<?php

class Author
{
	public $lastName;
	public $firstName;
	public $patronimic;
	public $avatarPath;
	public $sign;

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
	
	public function getList()
	{
		$list = [];
		if(!empty($lastName))
		{
			$list[] = $this->lastName;
		}
		$list = [];
		if(!empty($firstName))
		{
			$list[] = $this->firstName;
		}
		$list = [];
		if(!empty($patronimic))
		{
			$list[] = $this->patronimic;
		}
		$list = [];
		if(!empty($avatarPath))
		{
			$list[] = $this->avatarPath;
		}
		$list = [];
		if(!empty($sign))
		{
			$list[] = $this->sign;
		}
		return $list;
	}
}
