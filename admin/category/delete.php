<?php
include_once '../../classes/Category.php';
if($_GET)
{
	$category = new category;
	$category->delete($_GET['id']);
	header("Location: /admin/category");
	exit;
}?>
