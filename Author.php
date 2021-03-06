<?php
include_once "DB.php";
class Author
{
	public $lastName;
	public $firstName;
	public $patronimic;
	public $avatar;
	public $sign;
	private $DB;
	public function __construct($lastName = "", $firstName = "", $patronimic = "", $avatar = "", $sign = "") 
	{
		if(!empty($lastName) && !empty($firstName) && !empty($patronimic) && !empty($avatar) && !empty($sign))
		{
			$this->setLastName($lastName);
			$this->setFirstName($firstName);
			$this->setPatronimic($patronimic);
			$this->setAv($avatar);
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
		return $this->avatar;
	}

	public function setAv(string $avatar)
	{
		$this->avatar = $avatar;
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

	public function add(string $firstName, string $lastName, string $patronimic, string $avatar, string $sign)
	{
		$query = "INSERT INTO authors SET FIRSTNAME = '$firstName', LASTNAME = '$lastName', PATRONIMIC = '$patronimic', AVATAR = '$avatar', SIGN = '$sign'";
		return $this->DB->query($query);
	}


	public function delete(int $id)
	{
		$query1 = "DELETE FROM authors WHERE ID = $id";
		$delete = $this->DB->query($query1);
		$news = new news;
		$deleteTrace = $news->deleteWholeAuthor($id);
		if($delete && $deleteTrace)
		{
			return $delete;
		}		
	}

	public function edit(int $id, string $firstName, string $lastName, string $patronimic, string $avatar, string $sign)
	{
		$author = $this->DB->query("SELECT * FROM author WHERE ID = '$id'");
		if($author['FIRSTNAME'] != $firstName)
		{
			$fields['FIRSTNAME'] = $firstName;
		}
		if($author['LASTNAME'] != $lastName)
		{
			$fields['LASTNAME'] = $lastName;
		}
		if($author['PATRONIMIC'] != $patronimic)
		{
			$fields['PATRONIMIC'] = $patronimic;
		}
		if($author['AVATAR'] != $avatar)
		{
			$fields['AVATAR'] = $avatar;
		}
		if($author['SIGN'] != $sign)
		{
			$fields['SIGN'] = $sign;
		}
		if(!empty($fields))
		{
			$sql = "UPDATE authors SET ";
			foreach($fields as $fieldCode => $fieldVal)
			{
				$sql .= $fieldCode . " = " . "'" . $fieldVal . "', ";
			}
			$sql = substr($sql, 0, -2);
			$sql .= " WHERE ID = '$id'";
			$this->DB->query($sql);
		}
	}
}
