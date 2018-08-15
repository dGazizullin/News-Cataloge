<?php
include_once 'DB.php';
class News
{
	public $id;
	public $header;
	public $announcement;
	public $text;
	public $catIds = [];
	public $sort;
	private $DB;

	public function __construct($id = NULL, $header = '', $announcement = '', $text = '', $catIds = '', $sort = 0)
	{
		if(!empty($id) && !empty($header) && !empty($announcement) && !empty($text) && !empty($catIds))
		{
			$this->setId($id);
			$this->setHeader($header);
			$this->setAnnouncement($announcement);
			$this->setText($text);
			$this->setCat($catIds);
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
		if($id > 0)
		{
			$this->id = $id;
		}
	}

	public function getSort()
	{
		return $this->sort;
	}

	public function setSort($sort)
	{
		$this->sort = $sort;
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

	//returns list of all news
	public function getList($limit = 999, $sort = 0)
	{
		if(intval($limit) > 0 && intval($sort) >= 0)
		{
			$list = [];
			$query = "SELECT * FROM news WHERE SORT >= $sort LIMIT $limit";
			$arRes = $this->DB->query($query);
			foreach ($arRes as $row)
			{
				$list[] = new self($row['ID'], $row['HEADER'], $row['ANNOUNCEMENT'], $row['NEWS_TEXT'], $catIds = [NULL], $row['SORT']);
			}
			return $list;
		}
		return false;
	}

	public function getById($id = 0)
	{
		if($id > 0)
		{
			$query = "SELECT * FROM news WHERE ID = '$id'";
			$arRes = $this->DB->query($query);
			foreach ($arRes as $res)
			{
				return new self($res['ID'], $res['HEADER'], $res['ANNOUNCEMENT'], $res['NEWS_TEXT'], $catIds = [1], $res['SORT']);
			}
		}
		return false;
	}

	public function getLastId()
	{
			$query = "SELECT ID from news where ID = (SELECT MAX(ID) FROM news)";
			$result = $this->DB->query($query);
			//extract int value from arrays
			$result = $result[0];
			$result = $result["ID"];
			return $result;
	}

	public function add(string $announcement, string $text, string $header, int $sort)
	{
		$query = "INSERT INTO news SET ANNOUNCEMENT = '$announcement', NEWS_TEXT = '$text', HEADER = '$header', SORT = '$sort'";
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

	public function edit(int $id, string $announcement, string $text, string $header, int $sort)
	{
		$news = $this->DB->query("SELECT * FROM news WHERE ID = '$id'");
		$news = $news[0];
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
		if(intval($news['SORT']) != intval($sort))
		{
			$fields['SORT'] = intval($sort);
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

	public function setCategory(int $newsId, int $categoryId)
	{
		$query = "INSERT INTO news_categories VALUES (NULL, '$newsId', '$categoryId')";
		return $this->DB->query($query);
	}

	public function addAuthor(int $newsId, int $authorId)
	{
		$query = "INSERT INTO news_authors VALUES (NULL, '$newsId', '$authorId')";
		return $this->DB->query($query);
	}

	//delete 1 row from news_authors table
	public function deleteAuthor(int $newsId, int $authorId)
	{
		$query = "DELETE FROM news_authors WHERE NEWS_ID = $newsId AND AUTHOR_ID = $authorId";
		return $this->DB->query($query);
	}

	//delete all news links by given author ID 
	public function deleteWholeAuthor(int $newsId)
	{
		$query = "DELETE FROM news_authors WHERE NEWS_ID = $newsId";
		return $this->DB->query($query);
	}

	//delete all categories links by given news ID
	public function deleteCategories(int $newsId)
	{
		$query = "DELETE FROM news_categories WHERE NEWS_ID = $newsId";
		return $this->DB->query($query);
	}

	public function deleteCategory(int $newsId, int $categoryId)
	{
		$query = "DELETE FROM news_categories WHERE NEWS_ID = $newsId AND CATEGORY_ID = $categoryId";
		return $this->DB->query($query);
	}

	public function getCategories($newsId)
	{
		$result = [];
		$query = "SELECT CATEGORY_ID FROM news_categories WHERE NEWS_ID = '$newsId'";
		$category = new category;
		foreach($this->DB->query($query) as $catAr)
		{
			$result[] = $category->getById($catAr["CATEGORY_ID"]);
		}
		return $result;
	}

	public function getAuthors($newsId)
	{
		$result = [];
		$query = "SELECT AUTHOR_ID FROM news_authors WHERE NEWS_ID = '$newsId'";
		$author = new author;
		foreach ($this->DB->query($query) as $authorAr)
		{
			$result[] = $author->getById($authorAr["AUTHOR_ID"]);
		}
		return $result;
	}
}