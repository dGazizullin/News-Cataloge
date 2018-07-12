<?php
include_once 'DB.php';
class News
{
	public $ID;
	public $header;
	public $announcement;
	public $text;
	public $catIds = [];
	private $DB;

	public function __construct($ID = NULL, $header = '', $announcement = '', $text = '', $catIds = '')
	{
		if(!empty($ID) && !empty($header) && !empty($announcement) && !empty($text) && !empty($catIds))
		{
			$this->setID($ID);
			$this->setHeader($header);
			$this->setAnnouncement($announcement);
			$this->setText($text);
			$this->setCat($catIds);
		}
		$this->DB = new DB();
	}

	public function getID()
	{
		return $this->ID;
	}

	public function setID(int $ID)
	{
		$this->ID = $ID;
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
		}else if(intval($catIds) > 0)
		{
			$this->catIds[] = $catIds;
		}else
		{
			throw new Exception($this->catIds . ' includes wrong ID. ');
		}
	}

	public function getList($limit = 2)
	{
		if(intval($limit) > 0)
		{
			$list = [];
			$arRes = $this->DB->query('SELECT * FROM news LIMIT $limit');
			foreach ($arRes as $res)
			{
				$list[] = new self($row['ID'], $row['HEADER'], $row['ANNOUNCEMENT'], $row['NEWS_TEXT'], $catIds = [NULL]);
			}
			return $list;
		}
		return false;
	}

	public function getByID($ID = 0)
	{
		if($ID > 0)
		{
			$arRes = $this->DB->query("SELECT * FROM news WHERE ID = '$ID'");
			foreach ($arRes as $res)
			{
				return new self($res['ID'], $res['HEADER'], $res['ANNOUNCEMENT'], $res['NEWS_TEXT'], $catIds = [1]);
			}
		}
		return false;
	}

	public function add(int $id, string $announcement, string $text, string $header)
	{
		$query = "INSERT INTO news VALUES ('$id', '$announcement', '$text', '$header');";
		$add = $this->DB->query("$query");
		if($add)
		{
			return "News (ID = $id) added successfully.";
		}
		return false;			
	}

	public function delete(int $id = 0)
	{
		$query = "DELETE FROM news WHERE ID = '$id'";
		$delete = $this->DB->query("$query");
		if($delete)
		{
			return "News (ID = $id) deleted successfully.";
		}
		return false;			
	}

	public function edit(int $id, string $announcement, string $text, string $header)
	{
		$query = "UPDATE news SET ANNOUNCEMENT = '$announcement', NEWS_TEXT = '$text', HEADER = '$header' WHERE ID = '$id';";
		$edit = $this->DB->query("$query");
		if($edit)
		{
			return "News (ID = $id) edited successfully.";
		}
		return false;
	}

	public function setCategory(int $ID, int $categoryID)
	{
		//checking if news ID exists
		$query = "SELECT ID FROM news WHERE ID = $ID";
		$arRes = $this->DB->query($query);
		if($arRes)
		{
			//checking if category ID exists
			$query = "SELECT ID FROM categories WHERE ID = $categoryID";
			$arRes = $this->DB->query($query);
			if($arRes)
			{
				$query = "INSERT INTO news_categories VALUES (NULL, '$ID', '$categoryID')";
				$set = $this->DB->query($query);
				if($set)
				{
					return "News $ID belongs to category $categoryID now.";
				}
			}	
		}		
		return false;
	}

	public function deleteCategory(int $ID, int $categoryID)
	{
		$query = "DELETE FROM news_categories WHERE NEWS_ID = $ID AND CATEGORY_ID = $categoryID";
		$delete = $this->DB->query($query);
		if($delete)
		{
			return "News $ID doesn't belong to category $categoryID now.";
		}
		return false;
	}

	public function addAuthor($ID, $authorID)
	{
		//checking if news ID exists
		$query = "SELECT ID FROM news WHERE ID = $ID";
		$arRes = $this->DB->query($query);
		if($arRes)
		{
			//checking if author ID exists
			$query = "SELECT ID FROM authors WHERE ID = $authorID";
			$arRes = $this->DB->query($query);
			if($arRes)
			{
				$query = "INSERT INTO news_authors VALUES (NULL, '$ID', '$authorID')";
				$add = $this->DB->query($query);
				if($add)
				{
					return "News $ID is written by author $authorID.";
				}
			}
		}
	}

	public function deleteAuthor($ID, $authorID)
	{
		$query = "DELETE FROM news_authors WHERE NEWS_ID = $ID AND AUTHOR_ID = $authorID";
		$delete = $this->DB->query($query);
		if($delete)
		{
			return "News $ID isn't written by author $authorID now.";
		}
		return false;
	}

	public function deleteWholeAuthor($authorID)
	{
		$query = "DELETE FROM news_authors WHERE AUTHOR_ID = $authorID";
		$delete = $this->DB->query($query);
		if($delete)
		{
			return true;
		}
		return false;
	}
}