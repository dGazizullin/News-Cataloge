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
}

class News
{
	public $header;
	public $announcement;
	public $text;
	public $catIds = [];

	public function __construct($header = "", $announcement = "", $text = "", $catIds = "")
	{
		if(!empty($header) && !empty($announcement) && !empty($text) && !empty($catIds))
		{
			$this->setHeader($header);
			$this->setAnnouncement($announcement);
			$this->setText($text);
			$this->setCat($catIds);
		}
	}

	public function getHeader()
	{
		return $this->header;
	}

	public function setHeader(string $header)
	{
		$this->header = $header;
	}

	public function getAnnouncement()
	{
		return $this->announcement;
	}

	public function setAnnouncement(string $announcement)
	{
		$this->announcement = $announcement;
	}

	public function getText()
	{
		return $this->text;
	}

	public function setText(string $text)
	{
		$this->text = $text;
	}

	public function getCat()
	{
		return $catIds;
	}

	public function setCat($catIds)
	{
		if(is_array($catIds))
		{
			$this->catIds += $catIds;
		}
		}else if(intval($catIds) > 0)
		{
			$this->catIds[] = $catIds;
		}else
		{
			throw new Exception($this->catIds . ' is wrong ID. ');
		}
	}
}

class Category 
{
	public $catName;
	public $catId;
	public $catInc = [];

	public function __construct($catName = "", $catInc = "")
	{
		if(!empty($catName) && !empty($catInc))
		{
			$this->setName($catName);
			$this->setInc($catInc);
		}
	}

	public function getName()
	{
		return $this->catName;
	}

	public function setName(string $catName)
	{
		$this->catName = $catName;
	}

	public function getId()
	{
		return $this->$catId;
	}

	public function setId(int $catId)
	{
		$this->$catId;
	}

	//имена catName хранятся в массиве catInc

	public function getInc()
	{
		return $catInc; 
	}

	public function setInc(int $catId)
	{
		$this->catInc[] = $catId;
	}
}
