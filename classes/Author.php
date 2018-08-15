<?php
include_once "DB.php";
class Author
{
	public $id;
	public $lastName;
	public $firstName;
	public $patronimic;
	public $avatar;
	public $sign;
	public $sort;
	private $DB;
	public function __construct($lastName = "", $firstName = "", $patronimic = "", $avatar = "", $sign = "", $id = 0, $sort = 0) 
	{
		if(!empty($lastName) && !empty($firstName) && !empty($patronimic) && !empty($avatar) && !empty($sign))
		{
			$this->setLastName($lastName);
			$this->setFirstName($firstName);
			$this->setPatronimic($patronimic);
			$this->setAv($avatar);
			$this->setSign($sign);
			$this->setId($id);
			$this->setSort(intval($sort));
		}
		$this->DB = new DB();
	}

	public function setId(int $id)
	{
		if(intval($id) > 0)
		{
			$this->id = $id;
		}
	}

	public function getId()
	{
		return $this->id;
	}

	public function setSort(int $sort)
	{
		$this->sort = $sort;
	}

	public function getSort()
	{
		return $this->sort;
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
	
	//returns list of all news
	public function getList($limit = 99, $sort = 0)
	{
		if(intval($limit) > 0 && intval($sort) >= 0)
		{
			$list = [];
			$query = "SELECT * FROM authors WHERE SORT >= $sort LIMIT $limit";
			$arRes = $this->DB->query($query);
			foreach ($arRes as $res)
			{
				$list[] = new self($res['LASTNAME'], $res['FIRSTNAME'], $res['PATRONIMIC'], $res['AVATAR'], $res['SIGN'], $res["ID"], $res['SORT']); 
			}
			return $list;
		}
		return false;
	}

	public function getByID($id = 0)
	{
		if($id > 0)
		{
			$query = "SELECT * FROM authors WHERE ID = '$id'";
			$arRes = $this->DB->query($query);
			foreach ($arRes as $res)
			{
				return new self($res['LASTNAME'], $res['FIRSTNAME'], $res['PATRONIMIC'], $res['AVATAR'], $res['SIGN'], $res["ID"], $res['SORT']);
			}
		}
		return false;
	}

	public function getLastId()
	{
			$query = "SELECT ID from authors where ID = (SELECT MAX(ID) FROM authors)";
			$result = $this->DB->query($query);
			//extract int value from arrays
			$result = $result[0];
			$result = $result["ID"];
			return $result;
	}

	//returns list of authors ID's
	public function getIds()
	{
		return $this->DB->query("SELECT ID FROM authors");
	}

	public function add(string $firstName, string $lastName, string $patronimic, string $avatar, string $sign, int $sort)
	{
		$query = "INSERT INTO authors SET FIRSTNAME = '$firstName', LASTNAME = '$lastName', PATRONIMIC = '$patronimic', AVATAR = '$avatar', SIGN = '$sign', SORT = '$sort'";
		return $this->DB->query($query);
	}

	public function delete(int $id = 0)
	{
		$query1 = "DELETE FROM authors WHERE ID = $id";
		$delete = $this->DB->query($query1);
		$query2 = "DELETE FROM news_authors WHERE AUTHOR_ID = $id";
		$deleteTrace = $this->DB->query($query2);
		if($delete && $deleteTrace)
		{
			return $delete;
		}
	}

	public function edit(int $id, string $firstName, string $lastName, string $patronimic, string $avatar, string $sign, int $sort)
	{
		$query = "SELECT * FROM authors WHERE ID = '$id'";
		$author = $this->DB->query($query);
		$author = $author[0];
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
		if(intval($author['SORT']) != intval($sort))
		{
			$fields['SORT'] = intval($sort);
		}
		if(!empty($fields))
		{
			$sql = "UPDATE authors SET ";
			foreach($fields as $fieldCode => $fieldVal)
			{
				$sql .= $fieldCode." = "."'".$fieldVal."', ";
			}
			$sql = substr($sql, 0, -2);
			$sql .= " WHERE ID = '$id'";
			$this->DB->query($sql);
		}
	}

//возвращает ID новостей автора с указанным ID
	public function getNews($authorId)
	{
		$result = [];
		$query = "SELECT NEWS_ID FROM news_authors WHERE AUTHOR_ID = '$authorId'";
		include_once './News.php';
		$new = new news;
		foreach($this->DB->query($query) as $news)
		{
			$new->getById($news["NEWS_ID"]);
			$result[] = $new->getById($news["NEWS_ID"]);
		}
		return $result;
	}
}
