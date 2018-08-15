<?php
// ini_set('display_errors','On');
// error_reporting(E_ALL|E_STRICT);
include_once "DB.php";
class Category 
{
	public $id;
	public $name;
	public $parents = [];
	public $sort;
	private $DB;
	public function __construct($id = NULL, $name = "", $parents = "", $sort = 0)
	{
		if(!empty($id) && !empty($name))
		{
			$this->setId($id);
			$this->setName($name);
			$this->setParents($parents);
			$this->setSort(intval($sort));
		}
		$this->DB = new DB();
	}

	public function getId()
	{
		return $this->id;
	}

	public function setId(int $id)
	{
		$this->id = $id;
	}

	public function getSort()
	{
		return $this->sort;
	}

	public function setSort(int $sort)
	{
		$this->sort = $sort;
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

	//returns list of all news
	public function getList(int $limit = 99, $sort = 0)
	{
		if(intval($limit) > 0 && intval($sort) >= 0)
		{
			$list = [];
			$query = "SELECT * FROM categories WHERE SORT >= $sort LIMIT $limit";
			$arRes = $this->DB->query($query);
			foreach ($arRes as $res)
			{
				$list[] = new self($res['ID'], $res['CATEGORY_NAME'], $this->getParents($res['ID']), $res['SORT']);
			}
			return $list;
		}
		return false;
	}
	

	public function getById(int $id)
	{
		if($id > 0)
		{
			$arRes = $this->DB->query("SELECT * FROM categories WHERE ID = '$id'");
			foreach($arRes as $res)
			{
				return new self($res['ID'], $res['CATEGORY_NAME'], $this->getParents($res['ID']), $res['SORT']);
			}
		}
		return false;
	}

	public function getLastId()
	{
			$query = "SELECT ID from categories where ID = (SELECT MAX(ID) FROM categories)";
			$result = $this->DB->query($query);
			//extract int value from arrays
			$result = $result[0];
			$result = $result["ID"];
			return $result;
	}

	public function getParents(int $categoryId)
	{
		$result = [];
		$query = "SELECT PARENT_ID FROM parent_categories WHERE CATEGORY_ID = '$categoryId' AND PARENT_ID <> 0";
		$arRes = $this->DB->query($query);
		foreach($arRes as $res)
		{
				$result[] = $res["PARENT_ID"];
		}
		return array_unique($result);
	}

	public function getChildren(int $categoryId)
	{
		$result = [];
		$query = "SELECT CATEGORY_ID FROM parent_categories WHERE PARENT_ID = '$categoryId'";
		$arRes = $this->DB->query($query);
		foreach($arRes as $res)
		{
			$result[] = $res["CATEGORY_ID"];
		}
		return $result;
	}

	public function getNews($categoryId)
	{
		$result = [];
		$query = "SELECT NEWS_ID FROM news_categories WHERE CATEGORY_ID = '$categoryId'";
		$news = new news;
		foreach ($this->DB->query($query) as $newsAr)
		{
			$result[] = $news->getById($newsAr["NEWS_ID"]);
		}
		return $result;
	}

	public function add(string $name, int $sort)
	{
		$query = "INSERT INTO categories SET CATEGORY_NAME = '$name', SORT = '$sort'";
		$this->DB->query($query);
		//insert into categories tree as root category
		return $this->setParent($this->getLastId());	
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

	public function edit(int $id, string $name, int $sort)
	{
		$query = "SELECT * FROM categories WHERE ID = '$id'";
		$category = $this->DB->query($query);
		$category = $category[0];
		if($category['CATEGORY_NAME'] != $name)
		{
			$fields['CATEGORY_NAME'] = $name;
		}
		if(intval($category['SORT']) != intval($sort))
		{
			$fields['SORT'] = intval($sort);
		}
		if(!empty($fields))
		{
			$sql = "UPDATE categories SET ";
			foreach($fields as $fieldCode => $fieldVal)
			{
				$sql .= $fieldCode . " = " . "'" . $fieldVal . "', ";
			}
			$sql = substr($sql, 0, -2);
			$sql .= " WHERE ID = '$id'";
			$this->DB->query($sql);
		}
	}

	//parentId = 0 meens no parent, 0 nesting lvl
	public function setParent(int $childId, int $parentId = 0)
	{
		if($childId != $parentId)
		{
			this option is used in add function
			if($parentId <= 0)
			{
				$query = "INSERT INTO parent_categories SET CATEGORY_ID = '$childId', PARENT_ID = 0, NESTING = 0";
				return $this->DB->query($query);
			}
			$parentNestings = $this->getNestings($parentId);
			foreach ($parentNestings as $nestingArr)
			{
				foreach ($nestingArr as $parentNesting)
				{
					$query = "INSERT INTO parent_categories SET CATEGORY_ID = '$childId', PARENT_ID = '$parentId', NESTING = $parentNesting + 1";
					print_r($query);
					$this->DB->query($query);
				}
			}
		// 	if($childId != $parentId)
		// 	{
		// 		$query = "INSERT INTO parent_categories SET CATEGORY_ID = $childId, PARENT_ID = $parentId";
		// 		$this->DB->query($query);
		// 	}
		// }
	}

	public function childrenCheck($id, $childrenIds)
	{
		$childrenIds = $this->getChildren($id);
		var_dump($childrenIds);
		foreach ($childrenIds as $childId)
		{
			$childrenIds += $this->childrenCheck($childId, $childrenIds);
		}
	}
	
	public function getNestings($id)
	{
		$query = "SELECT NESTING FROM parent_categories WHERE CATEGORY_ID = $id";
		return $this->DB->query($query);
	}


	public function deleteParent(int $childId, int $parentId)
	{
		$query = "DELETE FROM parent_categories WHERE PARENT_ID = '$parentId' AND CATEGORY_ID = '$childId'";
		return $this->DB->query($query);
	}

}