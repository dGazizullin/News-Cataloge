<?php
include_once '../../classes/Author.php';
include_once '../../classes/Category.php';
include_once '../../classes/News.php';?>

<?if ($_POST)
{
    //add data into news table
	$news = new news;
    $news->add($_POST['ANNOUNCEMENT'], $_POST['TEXT'], $_POST['HEADER'], intval($_POST['SORT']));
    //getting ID of added news
    $newsId = $news->getLastId();
    //extracting authors IDs from _POST
	$keysArr = array_keys($_POST, 'on');
	foreach ($keysArr as $value)
	{
		if(substr($value, 0, 6) == 'AUTHOR')
		{
			$authorsIds[] = substr($value, 6);
		}
	}
	//add data into news_authors table
	foreach ($authorsIds as $authorId)
	{
		$news->addAuthor($newsId, $authorId);
	}
	//extracting categories IDs from _POST
	unset($keysArr);
	$keysArr = array_keys($_POST, 'on');
	foreach ($keysArr as $value)
	{
		if(substr($value, 0, 8) == 'CATEGORY')
		{
			$catArr[] = $value;
		}
	}
	//add data into news_categories table
	foreach ($catArr as $catId)
	{
		$news->setCategory($newsId, substr($catId, 8));
	}
}
if($_POST['SAVE'] == 'SAVE')
{
	header("Location: /admin/news/");
	exit;
}?>	

<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Add news</title>
	</head>
	<body>
		<div class="container">
			<div class="page-header">
				<h1 style='text-align: center;'>Добавить новость</h1>
			</div>
			<form role="form" method="POST" action="" id="addForm">
				<div class="form-group col-auto">
					<label for="header">Заголовок</label>
					<input type="text" class="form-control" id="header" placeholder="Введите заголовок:" name="HEADER" required>
				</div>
				<div class="form-group col-auto">
					<label for="announcement">Анонс</label>
					<input type="text" class="form-control" id="announcement" placeholder="Введите анонс:" name="ANNOUNCEMENT" required>
				</div>
				<div class="form-group col-auto">
					<label for="text">Текст</label>
					<textarea class="form-control" id="text" placeholder="Введите текст:" name="TEXT" required></textarea>
				</div>
				<div class="form-group col-auto">
					<label for="sort">Сортировка</label>
					<input type="text" class="form-control" id="sort" placeholder="Введите сортировочный номер:" value="10" pattern="^[0-9]+$" name="SORT" required>
				</div>
				<div class="row col-auto">
					<div class="form-group col-auto">
						<label for="authors">Авторы</label>
						<?$author = new author();
						$arAuthors = $author->getList(99, 1);
						foreach($arAuthors as $arAuthor):?>
							<br>
							<input type="checkbox" name="AUTHOR<?echo $arAuthor->getId()?>">
								<div style="display: inline; margin-left: 10;">
									<a href="/author/<?=$arAuthor->getId()?>/">
										<?=$arAuthor->getFirstName()?>
										<?=$arAuthor->getLastName()?>
										<?=$arAuthor->getPatronimic()?>
									</a>
								</div>
						<?endforeach;?>					
					</div>
					<div class="form-group col-auto">
						<label>Категории</label>
						<?$category = new category();
						$catsAr = $category->getList(99, 1);?>
						<?foreach($catsAr as $catAr):?>
							<br>
							<input type="checkbox" id='categories' name="CATEGORY<?echo $catAr->getId()?>">
								<div style="display: inline; margin-left: 10;">
									<a href="/category/<?=$catAr->getId()?>/">
										<?=$catAr->getName()?>
									</a>
								</div>
						<?endforeach;?>
					</div>
				</div>
				<div class="btn-group" role="group" aria-label="Basic example">
					<button type="submit" class="btn btn-outline-primary btn-lg" form="addForm" name="SAVE" value="SAVE">Сохранить</button>
					<button type="submit" class="btn btn-outline-primary btn-lg" form="addForm">Применить</button>
					<a href="/admin/news/" class="btn btn-outline-danger btn-lg">Отмена</a>
				</div>
			</form>
		</div>
	</body>
</html>

