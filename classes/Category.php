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
	public $maxCounter = 0;
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
			if($parentId <= 0 || $parentId == null)
			{
				$query = "INSERT INTO parent_categories SET CATEGORY_ID = $childId, PARENT_ID = 0, NESTING = 0";
				$this->DB->query($query);
			}
			//inserting new row into parent_categories table
			$parentNestings = $this->getNestings($parentId);
			foreach ($parentNestings as $nestingArr)
			{
				foreach ($nestingArr as $parentNesting)
				{
					$query = "INSERT INTO parent_categories SET CATEGORY_ID = '$childId', PARENT_ID = '$parentId', NESTING = $parentNesting + 1";
					$this->DB->query($query);
					//delete row where new child is root category
					$this->deleteParent($childId, 0);
				}
			}
			//check if new relation is correct(category tree can be built)
			//getting root-categiries's IDs
			$roots = $this->getRootCats();
			foreach ($roots as $root)
			{
				$rootIds[] = $root['CATEGORY_ID'];
			}
			foreach ($rootIds as $rootId)
			{
				try
				{
					$rel = $this->getRelations();
					$this->getTree($rel, $rootId, 0, 0, 0, 0);
					//if can't build tree, delete row from parent_categories table
				} catch (Exception $e)
				{
					$this->deleteParent($childId, $parentId);
				}
			}
		}
	}

	//gets all nestings of set ID
	public function getNestings($id)
	{
		$query = "SELECT NESTING FROM parent_categories WHERE CATEGORY_ID = $id";
		$res = $this->DB->query($query);
		return $res;
	}

	//gets all unique relations from parent_categiries as an array
	public function getRelations()
	{
		$query = "SELECT CATEGORY_ID, PARENT_ID FROM parent_categories";
		$resAr = $this->DB->query($query);
		//delete duplicates
		foreach ($resAr as $res)
		{
			if($res != $prevRes)
			{
				$result[] = $res;
				$prevRes = $res;
			}
		}
		return $result;
	}

	public function getRootCats()
	{
		$query = "SELECT * FROM parent_categories WHERE PARENT_ID = 0";
		return $this->DB->query($query);
	}

	public function getParRowsNum()
	{
		$query = "SELECT count(*) FROM parent_categories";
		$this->maxCounter = $this->DB->query($query);
		$this->maxCounter = $this->maxCounter[0];
		return $this->maxCounter;
	}

	//build the tree with root == $parentId
	public function getTree($tree, $parentId, $counter = 0, $idToCheck = 0, $rootId, $uniqueId)
	{
		//if function is executed recursively more times than rows in parent_categories, throw Exception
		if($counter == 0)
		{
			$this->maxCounter = $this->getParRowsNum();
		}

		if($counter > $this->maxCounter["count(*)"] + 1)
		{
			throw new Exception('Невозможно выполнить операцию.');
		}
		//start forming html
	    $html = '<ul class="list-group">' . "\n";
		$ul = false;
	    foreach ($tree as $row):
	        if ($row['PARENT_ID'] == $parentId):
	            $html .= '<li class="list-group-item">' . "\n";
	            //forming uniqueId for id attribute of checkbox
	            $uniqueId .= $row['PARENT_ID'];
	            $html .= '<input type="checkbox" id="' . $uniqueId.$row['CATEGORY_ID'] . '"name="CATEGORY'.$row['CATEGORY_ID'].'"';
	            $html .= 'onchange="syncChecks(\''.$uniqueId.$row['CATEGORY_ID'] . '\', ' . '\'CATEGORY' . $row['CATEGORY_ID']."'" .')"';
	            //mark checkboxes current category reffers to (in edit form)
	            $curIds = $this->getParents($idToCheck);
					foreach ($curIds as $curId):
						if($row['CATEGORY_ID'] == $curId):
							$html .= " checked";
						endif;
					endforeach;
	            $html .= "><a href='/category/" . $row['CATEGORY_ID'] . "''>" . $this->getById($row['CATEGORY_ID'])->getName() . "</a>";
	            $html .=  $this->getTree($tree, $row['CATEGORY_ID'], ++$counter, $idToCheck, $rootId, $uniqueId);
	            $html .= '</li>' . "\n";
	            $ul = true;
	        endif;
	    endforeach;
	    $html .= '</ul>' . "\n";
		return $html = ($ul) ? $html : "";
	}

	public function deleteParent(int $childId, int $parentId)
	{
		$query = "DELETE FROM parent_categories WHERE PARENT_ID = '$parentId' AND CATEGORY_ID = '$childId'";
		return $this->DB->query($query);
	}
}