<?php
include_once '../classes/DB.php';

function addSort($tableNames)
{
	$db = new db;
	$size = count($tableNames);
	for ($i = 0; $i < $size; $i++)
	{
		$query = "SHOW COLUMNS FROM $tableNames[$i] WHERE ";
		$query = "ALTER TABLE $tableNames[$i] ADD COLUMN SORT int DEFAULT 10";
		$db->query($query);
	}
}

$tableNames = ['news', 'categories', 'authors'];
addSort($tableNames);
?>
