<?php

class Author
{
	public $lastName;
	public $firstName;
	public $patronimic;
	public $avatarPath;
	public $sign;

	public function __construct($lastName = "", $firstName = "", $patronimic = "", $avatarPath = "", $sign = "") 
	{
		if(!empty($lastName) && !empty($firstName) && !empty($patronimic) && !empty($avatarPath) && !empty($sign))
		{
			$this->setLastName($lastName);
			$this->setFirstName($firstName);
			$this->setPatronimic($patronimic);
			$this->setAv($avatarPath);
			$this->setSign($sign);
		}
	}

	public function getLastName()
	{
		return $this->lastName;
	}

	public function setLastName(string $lastName) 
	{
		$this->lastName = $lastName;
	}

	public function getFirstName() 
	{
		return $this->firstName;
	}

	public function setFirstName(string $firstName) 
	{
		$this->firstName = $firstName;
	}

	public function getPatronimic()
	{
		return $this->patronimic;
	}

	public function setPatronimic(string $patronimic)
	{
		$this->patronimic = $patronimic;
	}

	public function getAv()
	{
		return $this->avatarPath;
	}

	public function setAv(string $avatarPath)
	{
		$this->avatarPath = $avatarPath;
	}

	public function getSign()
	{
		return $this->sign;
	}

	public function setSign(string $sign)
	{
		$this->sign = $sign;
	}
	
	//num - максимальное получаемое число авторов
	public function getList($num)
	{
	$conn = msqli_connect("5.188.41.42", "bitrix", "bitrix")
	or die("NO CONNECTION: " . mysqli_error());
	mysqli_select_db("news", $conn);
	$sql = "SELECT * FROM authors WHERE ID < ($num + 1)";
	$sql = (string) $sql;
	$result = mysqli_query($sql, $conn)
	or die ("ERROR! ".mysql_error());
	while ($row = mysqli_fetch_assoc($result))
	{
		$firstName = $row["firstName"];
		$lastName = $row["lastName"];
		$patronimic = $row["patronimic"];
		$avatarPath = $row["avatarPath"];
		$sign = row["sign"];
		echo $firstName . ' ' . $lastName . ' ' . $patronimic . ' ' .  $avatarPath . ' ' .  $sign . "<br>";
	}	
	mysqli_close($conn);
	}
}
