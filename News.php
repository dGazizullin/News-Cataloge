<?php

class News
{
	public $newsID;
	public $header;
	public $announcement;
	public $text;
	public $catIds = [];

	public function __construct($newsID = NULL, $header = "", $announcement = "", $text = "", $catIds = "")
	{
		if(!empty($newsID) && !empty($header) && !empty($announcement) && !empty($text) && !empty($catIds))
		{
			$this->setID($newsID);
			$this->setHeader($header);
			$this->setAnnouncement($announcement);
			$this->setText($text);
			$this->setCat($catIds);
		}
	}

	public function getID()
	{
		return $this->newsID;
	}

	public function setID(int $newsID)
	{
		$this->newsID = $newsID;
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
			throw new Exception($this->catIds . ' is wrong ID. ');
		}
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
			$sql = "SELECT * FROM news LIMIT $limit";
			$result = mysqli_query($conn, $sql) or die ("ERROR! " . mysqli_error());
			while ($row = mysqli_fetch_assoc($result))
			{
				$news[] = new News($row['ID'], $row['HEADER'], $row['ANNOUNCEMENT'], $row['NEWS_TEXT'], $catIds = [NULL]);
			}
			mysqli_close($conn);
			return $news;
		}
	}
	public function getByID($ID = 1)
	{
		if(!empty($ID))
		{
			$conn = mysqli_connect("localhost", "bitrix0", "bitrix", "news") or die("NO CONNECTION: " . mysqli_error());
			if (!$conn)
			{
				die('Ошибка соединения: ' . mysqli_connect_errno());
			}
			$sql = "SELECT * FROM news WHERE ID = '$ID'";
			$result = mysqli_query($conn, $sql) or die ("ERROR! " . mysqli_error());
			while ($row = mysqli_fetch_assoc($result))
			{
				$news = new News($row['ID'], $row['HEADER'], $row['ANNOUNCEMENT'], $row['NEWS_TEXT'], $catIds = [NULL]);
			}
			mysqli_close($conn);
			return $news;
		}
	}
}