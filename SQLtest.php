<?
include "Category.php";
include "Author.php";
include "News.php";
ini_set('display_errors','On');
error_reporting(E_ALL|E_STRICT);

$cat = new Category;
$category = $cat->getByID(1);

echo $category->getId();
echo "<br>";

echo $category->getName();
echo "<br>";

echo "<pre>";
print_r($category->getParents(1));
echo "</pre>";


$auth = new Author;
$author = $auth->getByID(1);
echo $author->getFirstName();
echo "<br>";


$ns = new News;
$news = $ns->getByID(1);
echo $news->getID();
echo "<br>";
echo $news->getText();
echo "<br>";
