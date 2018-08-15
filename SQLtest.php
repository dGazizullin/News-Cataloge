<?
ini_set('display_errors','On');
error_reporting(E_ALL|E_STRICT);

include "./classes/Category.php";
include "./classes/Author.php";
include "./classes/News.php";

$category = new Category;
$author = new Author;
$news = new News;

$arr = $category-> getRelations();
// print_r($arr);
echo '<br>';


$roots = $category->getRootCats();
foreach ($roots as $root)
{
	$rootIds[] = $root['CATEGORY_ID'];
}
foreach ($rootIds as $rootId)
{
	$rel = $category->getRelations();
	$root = $category->getById($rootId);
	echo $root->getName().'<br>';
	echo $category->getTree($rel, $rootId);
}
//var_dump($tree);
// $category->get(5, "1/2/4");
// $category = $category->getTree();
// var_dump($asd);
//var_dump($nest);
//var_dump($test);
//print_r($test = $author->add('vasya2','фамилия','отчество','ava', 'sign'));



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