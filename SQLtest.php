<?
include "Author.php";
include "Category.php";
include "News.php";

ini_set('display_errors','On');
error_reporting(E_ALL|E_STRICT);


$author = new author(
	// $lastName,
	// $firstName,
	// $patronimic,
	// $av,
	// $sign
);

// $header = "Çàãîëîâîê";
// $announcement = "Àíîíñ";
// $text = "Òåêñò íîâîñòè";
// $category = 4;

$news = new news(
	//$header, 
	//$announcement, 
	//$text, 
	//$category
);

$category = new Category(3, "name", $arr = [NULL]);

echo "<pre>";
//print_r(get_class_methods($author));
/*
print_r($author->getList(3));
print_r($category->getList(3));
print_r($news->getList(3));
*/
print_r($category->getByID(2));
print_r($news->getByID(3));
print_r($author->getByID(3));
echo "</pre>";