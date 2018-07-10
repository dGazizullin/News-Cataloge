<?php

class Category 
{
	public $catID;
	public $catName;
	public $catInc = [];

	public function __construct($catID = NULL, $catName = "", $catInc = "")
	{
		if(!empty($catID) && !empty($catName) && !empty($catInc))
		{
			$this->setID($catID);
			$this->setName($catName);
			$this->setInc($catInc);
		}
	}

	public function getId()
	{
		return $this->catID;
	}

	public function setId(int $catID)
	{
		$this->catID = $catID;
	}

	public function getName()
	{
		return $this->catName;
	}

	public function setName(string $catName)
	{
		$this->catName = $catName;
	}

	public function getInc()
	{
		return $catInc; 
	}

	public function setInc($catInc)
	{
		if(is_array($catInc))
		{
			$this->catInc += $catInc;
		}else if(intval($catInc > 0))
		{
			$this->catInc[] = $catInc;
		}else
		{
			throw new Exception($this->catInc . ' includes wrong data. ');
		}
		//$this->catInc[] = $catArr;
	}
	public function getList($limit = 2)
	{
		if(!empty($limit))
		{
			$conn = mysqli_connect("localhost", "bitrix0", "bitrix", "news") or die("NO CONNECTION: " . mysqli_error());
			if (!$conn)
			{
				die('Ошибка соединения: ' . mysqli_connect_errno());
			}
			$sql = "SELECT * FROM categories LIMIT $limit";
			$result = mysqli_query($conn, $sql) or die ("ERROR! " . mysqli_error());
			while ($row = mysqli_fetch_assoc($result))
			{
				$categories[] = new Category($row['ID'], $row['CATEGORY_NAME'], $catInc = [NULL]);
			}
			mysqli_close($conn);
			return $categories;
		}
	}
}