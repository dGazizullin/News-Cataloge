<?php

class Category 
{
	public $catName;
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

	//èìåíà catName õðàíÿòñÿ â ìàññèâå catInc

	public function getInc()
	{
		return $catInc; 
	}

	public function setInc(string $catName)
	{
		$this->catInc[] = $catName;
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
				$categories[] = new Category($row['ID'], $row['CATEGORY_NAME']);
			}
			mysqli_close($conn);
			return $categories;
		}
		
	}
}