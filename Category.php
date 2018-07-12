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

	
	public function getList($limit = 3)
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
	

	public function getByID($ID = 0)
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

	public function getParents($categoryID)
	{
		$result = [];
		$query = "SELECT PARENT_ID FROM incl_categories WHERE CATEGORY_ID = '$categoryID'";
		$arRes = $this->DB->query($query);

		foreach($arRes as $res)
		{
			$result[] = $res["PARENT_ID"];
		}

		return $result;
	}

	public function add(int $id,string $name)
	{
		$query = "INSERT INTO categories VALUES ('$id', '$name');";
		$add = $this->DB->query("$query");
		if($add)
		{
			return "Category (ID = $id) added successfully.";
		}
		return false;			
	}

	public function delete(int $id)
	{
		$query = "DELETE FROM categories WHERE ID = '$id'";
		$delete = $this->DB->query("$query");
		if($delete)
		{
			return "Category (ID = $id) deleted successfully.";
		}
		return false;			
	}

	public function edit(int $id, string $name)
	{
		$query = "UPDATE categories SET CATEGORY_NAME = '$name' WHERE ID = '$id';";
		$edit = $this->DB->query("$query");
		if($edit)
		{
			return "Category (ID = $id) edited successfully.";
		}
		return false;
	}
}