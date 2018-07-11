<?php

class News
{
	public $ID;
	public $header;
	public $announcement;
	public $text;
	public $catIds = [];

	public function __construct($ID = NULL, $header = "", $announcement = "", $text = "", $catIds = "")
	{
		if(!empty($ID) && !empty($header) && !empty($announcement) && !empty($text) && !empty($catIds))
		{
			$this->setID($ID);
			$this->setHeader($header);
			$this->setAnnouncement($announcement);
			$this->setText($text);
			$this->setCat($catIds);
		}
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
	public function getByID($ID = 0)
	{
		if(!empty($ID))
		{
			$conn = mysqli_connect("localhost", "bitrix0", "bitrix", "news") or die("NO CONNECTION: " . mysqli_error());
			if (!$conn)
			{
				die('Ошибка соединения: ' . mysqli_connect_errno());
			}
			$sql = "SELECT * FROM news WHERE ID = '$ID'";
			$result = mysqli_query($conn, $sql) or die ("ERROR! " . mysqli_error($conn));
			while ($row = mysqli_fetch_assoc($result))
			{
				$news = new News($row['ID'], $row['HEADER'], $row['ANNOUNCEMENT'], $row['NEWS_TEXT'], $catIds = [NULL]);
			}
			if($news)
			{
				return $news;
			}else
			{
				print_r($ID . " id wrong ID. " . "<br>");
			}
		}else
		{
			print_r($ID . " id wrong ID. " . "<br>");
		}
	}
}