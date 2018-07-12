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
}