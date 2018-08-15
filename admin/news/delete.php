<?php
include_once '../../classes/News.php';
if($_GET)
{
	$news = new news;
	$news->delete($_GET['id']);
	header("Location: /admin/news/");
	exit;
}?>