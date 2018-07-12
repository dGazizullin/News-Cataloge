<?

class DB
{
	public $host = "localhost";
	public $login = "bitrix0";
	public $pass = "bitrix";
	public $dbName = "news";
	public $connection;

	function __construct() {
		$this->connection = mysqli_connect($this->host, $this->login, $this->pass, $this->dbName) or die("NO CONNECTION: " . mysqli_error());
		if (!$this->connection)
		{
			die('Ошибка соединения: ' . mysqli_connect_errno());
		}
	}

	public function query(string $query = "") {
		if(strlen($query) > 0) {
			$res = mysqli_query($this->connection, $query);
			$result = [];
			while ($row = mysqli_fetch_assoc($res))
			{
				$result[] = $row;
			}
			return $result;
		}
		die ("ERROR! " . mysqli_error(self::$connection));	
	}
}