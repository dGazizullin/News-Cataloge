<?php
include_once '../../classes/Author.php';
include_once '../../classes/Category.php';
include_once '../../classes/News.php';

if ($_POST)
{
    //add data into news table
	$news = new news;
	//getting instance of news 
	$id = intval(htmlspecialchars($_GET["id"]));
	$news = $news->getById($id);
	//edit news instance
    $news->edit($id, $_POST['ANNOUNCEMENT'], $_POST['TEXT'], $_POST['HEADER'], intval($_POST['SORT']));
    //extracting chosen authors IDs from _POST
	$keysArr = array_keys($_POST, 'on');
	foreach ($keysArr as $value)
	{
		if(substr($value, 0, 6) == 'AUTHOR')
		{
			$newAuthIds[] = (intval(substr($value, 6)));
		}
	}
	//edit authors in news_authors table
	//getting current authors IDs
	$curAuthors = $news->getAuthors($id);
	foreach ($curAuthors as $curAuthor)
	{
		if($curAuthor)
		{
			$curAuthIds[] = $curAuthor->getId();
		}
	}
	//if authors updated, change news_authors table
	//search for elements to delete
	$authorsDelete = array_diff($curAuthIds, $newAuthIds);
	if($newAuthIds == null)
	{
		$authorsDelete = $curAuthIds;
	}
	foreach ($authorsDelete as $authorDelete)
	{
		$news->deleteAuthor($id, $authorDelete);
	}
	//search for elements to add
	$authorsAdd = array_diff($newAuthIds, $curAuthIds);
	if($curAuthIds == null)
	{
		$authorsAdd = $newAuthIds;
	}
	foreach ($authorsAdd as $newAuthor)
	{
		$news->addAuthor($id, $newAuthor);
	}
	unset($keysArr);

	//editing categories
	$category = new category;
	//getting current categories IDs
	$curCats = $news->getCategories($id);
	foreach ($curCats as $curCat)
	{
		if($curCat)
		{
			$curCatsIds[] = $curCat->getId();
		}
	}
	//extracting chosen categories IDs from _POST
	$keysArr = array_keys($_POST, 'on');
	foreach ($keysArr as $value)
	{
		if(substr($value, 0, 8) == 'CATEGORY')
		{
			$newCatsIds[] = (intval(substr($value, 8)));
		}
	}
	//if categories updated, change news_categories table
	//search for elements to delete
	$catsDelete = array_diff($curCatsIds, $newCatsIds);
	if($newCatsIds == null)
	{
		$catsDelete = $curCatsIds;
	}
	if($catsDelete)
	{
		foreach ($catsDelete as $catDelete)
		{
			$news->deleteCategory($id, $catDelete);
		}
	}
	
	//search for elements to add
	$catsAdd = array_diff($newCatsIds, $curCatsIds);
	if($curCatsIds == null)
	{
		$catsAdd = $newCatsIds;
	}
	if($catsAdd)
	{
		foreach ($catsAdd as $newCategory)
		{
			$news->setCategory($id, $newCategory);
		}
	}
}?>

<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Edit news</title>
	</head>
	<body>
	<?if($_POST['SAVE'] == "SAVE"):
		header("Location: /admin/news/");
		exit;
	endif;?>
		<div class="container">
			<div class="page-header">
				<h1 style = 'text-align: center;'>Редактировать новость</h1>
			</div>
			<form role="form" method="POST" action="" id="editForm">
				<div class="form-group col-auto">
					<label for="header">Заголовок</label>
					<?
					$news = new news;
					$news = $news->getById($_GET['id']);?>
					<input type="text" class="form-control" id="header" placeholder="Заголовок" name="HEADER" value="<?echo $news->getHeader()?>" required>
				</div>
				<div class="form-group col-auto">
					<label for="announcement">Анонс</label>
					<input type="text" class="form-control" id="announcement" placeholder="Анонс" name="ANNOUNCEMENT" value="<?echo $news->getAnnouncement()?>" required>
				</div>
				<div class="form-group col-auto">
					<label for="text">Текст</label>
					<textarea class="form-control" id="text" placeholder="Текст" name="TEXT" required><?echo $news->gettext()?></textarea>
				</div>
				<div class="form-group col-auto">
					<label for="sort">Сортировка</label>
					<input type="text" class="form-control" id="sort" placeholder="Введите сортировочный номер" value="<?echo $news->getSort()?>" pattern="^[0-9]+$" name="SORT">
				</div>

				<div class="container col-auto">
					<div class="row">
						<div class="form-group  col-auto">
							<label for="authors">Авторы</label>
							<?$author = new author();
							$arAuthors = $author->getList(99, 1);
							//getting IDs of linked authors
							$authors = $news->getAuthors($_GET['id']);
							foreach ($authors as $author)
							{
								$authorsIds[] = $author->getId();
							}
							//add 'checked' attribute to authors
							foreach($arAuthors as $arAuthor):?>
								<br>
								<input type="checkbox" name="AUTHOR<?echo $arAuthor->getId()?>" 
								<?foreach ($authorsIds as $authorId)
								{
									if($arAuthor->getId() == $authorId)
									{
										echo " checked";
									}
								}?>>
								<!-- authors links output -->
									<div style="display: inline; margin-left: 10;">
										<a href="/author/<?=$arAuthor->getId()?>/">
											<?=$arAuthor->getFirstName()?>
											<?=$arAuthor->getLastName()?>
											<?=$arAuthor->getPatronimic()?>
										</a>
									</div>
							<?endforeach;?>					
						</div>
						<div class="form-group  col-auto">
							<label>Категории</label>
							<?
							$category = new category();
							$catsList = $category->getList(99, 1);
							//output list of categories
							foreach($catsList as $catArr)
							{?>
								<br>
								<input type="checkbox" name="CATEGORY<?echo $catArr->getId()?>"
								<?
								//add 'checked' attribute to categories
								$curCats = $news->getCategories($_GET['id']);
								foreach ($curCats as $curCat)
								{
									if($catArr->getId() == $curCat->getId())
									{
										echo " checked";
									}
								}?>>
								<div style="display: inline; margin-left: 10;">
										<a href="/category/<?=$catArr->getId()?>/">
											<?=$catArr->getName()?>
										</a>
								</div><?
							}?>
						</div>
					</div>
				</div>
				<div class="btn-group row">
					<button  type="submit" class="btn btn-outline-primary btn-lg" form='editForm' name='SAVE' value='SAVE'>Сохранить</button>
					<button type="submit" class="btn btn-outline-primary btn-lg col-auto" form="editForm" name='APPLY' value='APPLY'>Применить</button>
					<a href="/admin/news/" class="btn btn-outline-danger btn-lg col-auto">Отмена</a>
				</div>
			</form>
		</div>
	</body>
</html>