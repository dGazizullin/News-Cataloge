<?php

class Category 
{
	public $ID;
	public $catName;
	public $catInc = [];

	public function __construct($ID = NULL, $catName = "", $catInc = "")
	{
		if(!empty($ID) && !empty($catName) && !empty($catInc))
		{
			$this->setID($ID);
			$this->setName($catName);
			$this->setInc($catInc);
		}
	}

	public function getId()
	{
		return $this->ID;
	}

	public function setId(int $ID)
	{
		$this->ID = $ID;
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
			throw new Exception($this->catInc . ' includes wrong ID. ');
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
	public function getByID($ID = 0)
	{
		if(!empty($ID))
		{
			$conn = mysqli_connect("localhost", "bitrix0", "bitrix", "news") or die("NO CONNECTION: " . mysqli_error());
			if (!$conn)
			{
				die('Ошибка соединения: ' . mysqli_connect_errno());
			}
			$sql = "SELECT * FROM categories WHERE ID = '$ID'";
			$result = mysqli_query($conn, $sql) or die ("ERROR! " . mysqli_error($conn));
			while ($row = mysqli_fetch_assoc($result))
			{
				$category = new Category($row['ID'], $row['CATEGORY_NAME'], $catInc = [NULL]);
			}
			if ($category)
			{
				return $category;
			}else 
			{
				print_r($ID . " is wrong ID. " . "<br>");
			}
		}else
		{
			print_r($ID . " is wrong ID. " . "<br>");
		}
	}
}