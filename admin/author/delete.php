<?php
include_once '../../classes/Author.php';
include_once '../../classes/News.php';
if($_GET)
{
	$author = new author;
	$author->delete($_GET['id']);
	header("Location: /admin/author");
	exit;
}?>