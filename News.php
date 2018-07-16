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

	public function add(string $announcement, string $text, string $header)
	{
		$query = "INSERT INTO news SET ANNOUNCEMENT = '$announcement', NEWS_TEXT = '$text', HEADER = '$header'";
		return $this->DB->query($query);
	}

	public function delete(int $id = 0)
	{
		$query = "DELETE FROM news WHERE ID = '$id'";
		$deleteCategories = $this->deleteCategories($id);
		$deleteAuthors = $this->deleteWholeAuthor($id);
		$delete = $this->DB->query($query);
		if($delete && $deleteCategories && $deleteAuthors)
		{
			return $delete;
		}		
	}

	public function edit(int $id, string $announcement, string $text, string $header)
	{
		$news = $this->DB->query("SELECT * FROM news WHERE ID = '$id'");
		if($news['ANNOUNCEMENT'] != $announcement)
		{
			$fields['ANNOUNCEMENT'] = $announcement;
		}
		if($news['NEWS_TEXT'] != $text)
		{
			$fields['NEWS_TEXT'] = $text;
		}
		if($news['HEADER'] != $header)
		{
			$fields['HEADER'] = $header;
		}
		if(!empty($fields))
		{
			$sql = "UPDATE news SET ";
			foreach($fields as $fieldCode => $fieldVal)
			{
				$sql .= $fieldCode . " = " . "'" .  $fieldVal . "', ";
			}
			$sql = substr($sql, 0, -2);
			$sql .= "WHERE ID = '$id'";
			$this->DB->query($sql);
		}
	}

	public function setCategory(int $newsID, int $categoryID)
	{
		$query = "INSERT INTO news_categories VALUES (NULL, '$newsID', '$categoryID')";
		return $this->DB->query($query);
	}

	public function addAuthor(int $newsID, int $authorID)
	{
		$query = "INSERT INTO news_authors VALUES (NULL, '$newsID', '$authorID')";
		return $this->DB->query($query);
	}

	public function deleteAuthor(int $newsID, int $authorID)
	{
		$query = "DELETE FROM news_authors WHERE NEWS_ID = $newsID AND AUTHOR_ID = $authorID";
		return $this->DB->query($query);
	}

	//delete all news links by given author ID 
	public function deleteWholeAuthor(int $ID)
	{
		$query = "DELETE FROM news_authors WHERE NEWS_ID = $ID";
		return $this->DB->query($query);// 
	}

	//delete all categories links by given news ID
	public function deleteCategories(int $ID)
	{
		$query = "DELETE FROM news_categories WHERE NEWS_ID = $ID";
		return $this->DB->query($query);

	}
}