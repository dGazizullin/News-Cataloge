<?
ini_set('display_errors','On');
error_reporting(E_ALL|E_STRICT);

include "Category.php";
include "Author.php";
include "News.php";



$category = new Category;
$author = new Author;
$news = new News;

//print_r($test = $news->(2,2));
print_r($test = $author->edit(3,'Ксения','dsa','dsa','dsa', 'выф'));

/*
print_r($add = $category->add(4, "category"));
echo '<br>';
print_r($add = $author->add(4, "Fname", "Lname", "patre", "av", "sign"));
echo '<br>';
print_r($add = $news->add(4, "anoncement", "text", "header"));
echo '<br>';

print_r($edit = $category->edit(4, "cat"));
echo '<br>';
print_r($edit = $author->edit(4, "newFname", "newLname", "newpatre", "av", "sign"));
echo '<br>';

print_r($edit = $news->edit(4, "announcement", "text", "header"));
echo '<br>';

print_r($delete = $category->delete(4));
echo '<br>';
print_r($delete = $author->delete(4));
echo '<br>';
print_r($delete = $news->delete(4));
*/
