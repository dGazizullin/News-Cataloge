<?php

class News
{
	public $header;
	public $announcement;
	public $text;
	public $catArr = [];
	public $catName;

	public function __construct($header = "", $announcement = "", $text = "", $catArr = "")
	{
		if(!empty($header) && !empty($announcement) && !empty($text) && !empty($catArr))
		{
			$this->setHeader($header);
			$this->setAnnouncement($announcement);
			$this->setText($text);
			$this->setCatName($catName);
			$this->setCat($catArr);
		}
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


	public function getCatName ()
	{
		return $this->catName;
	}

	public function setCatName(string $catName)
	{
		$this->catName = $catName;
	}

	public function getCat()
	{
		return $catArr;
	}

	public function setCat(string $catName)
	{
		$this->catArr[] = $catName;
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
				$news[] = new News($row['ID'], $row['ANNOUNCEMENT'], row['NEWS_TEXT']);
			}
			mysqli_close($conn);
			return $news;
		}
		
	}
}