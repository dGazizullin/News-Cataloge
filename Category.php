<?php
include_once "DB.php";
class Category 
{
	public $ID;
	public $name;
	public $parents = [];
	private $DB;

	public function __construct($ID = NULL, $name = "", $parents = "")
	{
		if(!empty($ID) && !empty($name) && !empty($parents))
		{
			$this->setID($ID);
			$this->setName($name);
			$this->setParents($parents);
		}
		$this->DB = new DB();
	}

	public function getID()
	{
		return $this->ID;
	}

	public function setId(int $ID)
	{
		$this->ID = $ID;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName(string $name)
	{
		$this->name = $name;
	}

	public function setParents($parents)
	{
		if(is_array($parents))
		{
			$this->parents += $parents;
		}else if(intval($parents > 0))
		{
			$this->parents[] = $parents;
		}else
		{
			throw new Exception($this->parents . ' includes wrong ID. ');
		}
	}

	
	public function getList(int $limit = 3)
	{
		if(intval($limit) > 0)
		{
			$list = [];
			$arRes = $this->DB->query("SELECT * FROM categories LIMIT $limit");
			foreach ($arRes as $res)
			{
				$list[] = new self($res['ID'], $res['CATEGORY_NAME'], $this->getParents($res["ID"]));
			}
			return $list;
		}
		return false;
	}
	

	public function getByID(int $ID)
	{
		if($ID > 0)
		{
			$arRes = $this->DB->query("SELECT * FROM categories WHERE ID = '$ID'");
			foreach($arRes as $res)
			{
				return new self($res["ID"], $res["CATEGORY_NAME"], $this->getParents($res["ID"]));
			}
		}
		return false;
	}

	public function getParents(int $categoryID)
	{
		$result = [];
		$query = "SELECT PARENT_ID FROM parent_categories WHERE CATEGORY_ID = '$categoryID'";
		$arRes = $this->DB->query($query);
		foreach($arRes as $res)
		{
			$result[] = $res["PARENT_ID"];
		}
		return $result;
	}

	public function add(string $name)
	{
		$query = "INSERT INTO categories SET CATEGORY_NAME = '$name';";
		return $this->DB->query($query);			
	}

	public function delete(int $id)
	{
		$query = "DELETE FROM categories WHERE ID = '$id'";
		$delete = $this->DB->query($query);
		$query = "DELETE FROM parent_categories WHERE CATEGORY_ID = '$id' OR PARENT_ID = '$id'";
		$deleteParents = $this->DB->query($query);
		$query = "DELETE FROM news_categories WHERE CATEGORY_ID = '$id'";
		$deleteNews = $this->DB->query($query);
		if($delete && $deleteParents && $deleteNews)
		{
			return $delete;
		}	
	}

	public function edit(int $id, string $name)
	{
		$category = $this->DB->query("SELECT * FROM categories WHERE ID - '$id'");
		if($category['CATEGORY_NAME'] != $name)
		{
			$fields['CATEGORY_NAME'] = $name;
		}
		if(!empty($fields))
		{
			$sql = "UPDATE categories SET ";
			foreach($fields as $fieldCode => $fieldVal)
			{
				$sql .= $fieldCode . " = " . "'" . $fieldVal . "'";
			}
			$sql .= " WHERE ID = '$id'";
			$this->DB->query($sql);
		}
	}

	public function setParent(int $childID,int $parentID)
	{
		if($childID != $parentID)
		{
			$query = "INSERT INTO parent_categories SET CATEGORY_ID = '$childID', PARENT_ID = '$parentID'";
			return $this->DB->query($query);
		}
	}

	public function deleteParent(int $childID, int $parentID)
	{
		$query = "DELETE FROM parent_categories WHERE PARENT_ID = '$parentID' AND CATEGORY_ID = '$childID'";
		return $this->DB->query($query);
	}
}