<?
include "Author.php";
include "Category.php";
include "News.php";


$author = new author(
	// $lastName,
	// $firstName,
	// $patronimic,
	// $av,
	// $sign
);

// $header = "Заголовок";
// $announcement = "Анонс";
// $text = "Текст новости";
// $category = 4;

$news = new news(
	//$header, 
	//$announcement, 
	//$text, 
	//$category
);

$category = new category(
	//$header,
	//$announcement,
	//$text,
	//$catArr,
	//$catName,
);

echo "<pre>";
// print_r(get_class_methods($author));
print_r($author->getList(3));
print_r($category->getList(3));
print_r($news->getList(3));
echo "</pre>";