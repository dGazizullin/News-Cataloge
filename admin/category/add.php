<?php
include_once '../../classes/Category.php';
include_once '../../classes/News.php';
if ($_POST)
{
    //add data into categories table
	$category = new category;
	$news = new news;
    $category->add($_POST['NAME'], intval($_POST['SORT']));
    //getting ID of added category
    $categoryId = $category->getLastId();
    //extracting news IDs from _POST
	$keysArr = array_keys($_POST, 'on');
	foreach ($keysArr as $value)
	{
		if(substr($value, 0, 4) == 'NEWS')
		{
			$newsIds[] = substr($value, 4);
		}
	}
	//add data into news_categories table
	foreach ($newsIds as $newId)
	{
		$news->setCategory($newId, $categoryId);
	}
	//extracting categories IDs from _POST
	unset($keysArr);
	$keysArr = array_keys($_POST, 'on');
	foreach ($keysArr as $value)
	{
		if(substr($value, 0, 8) == 'CATEGORY')
		{
			$catsIdsArr[] = substr($value, 8);
		}
	}
	//add data into news_categories table
	foreach ($catsIdsArr as $catId)
	{
		$category->setParent($categoryId, $catId);
	}
	//redirect on save button
	if($_POST['SAVE'] == 'SAVE')
	{
		header("Location: /admin/category/");
		exit;
	}
}?>
<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
		<script>
			//synchronize 2 or more checkboxes with same name attributes (checked = true/false)
			function syncChecks(id, name)
			{
				var curChBox = document.getElementById(id);
				var relChBoxs = document.getElementsByName(name);
				if(curChBox.checked == false)
				{
					for(var i = 0; i < relChBoxs.length; ++i)
					{
						var rel = relChBoxs[i];
						$(rel).prop('checked', false);
					}
				}else
				{
					for(var i = 0; i < relChBoxs.length; ++i)
					{
						var rel = relChBoxs[i];
						$(rel).prop('checked', true);
					}
				}
			}			
		</script>
		<title>Add category</title>
	</head>
	<body>
		<div class="container">
			<div class="page-header">
				<h1 style='text-align: center;'>Добавить категорию</h1>
			</div>
			<form role="form" method="POST" action="" id="addForm">
				<div class="form-group">
					<label for="name">Название категории</label>
					<input type="text" class="form-control" id="name" placeholder="Введите название категории:" name="NAME" required>
				</div>
				<div class="form-group">
					<label for="sort">Сортировка</label>
					<input type="text" class="form-control" id="sort" placeholder="Введите сортировочный номер:" value="10" pattern="^[0-9]+$" name="SORT" required>
				</div>
				<div class="row">
					<div class="form-group col-3">
						<label>Новости категории</label>
						<?$news = new news();
						$newsList = $news->getList(99, 1);?>
						<?foreach($newsList as $news):?>
							<br>
							<input type="checkbox" name="NEWS<?echo $news->getId()?>">
								<div style="display: inline; margin-left: 10;">
									<a href="/news/<?=$news->getId()?>/">
										<?=$news->getHeader()?>
									</a>
								</div>
						<?endforeach;?>					
					</div>
					<div class="form-group col">
						<label>Категория входит в:</label>
						<?$category = new category();
						//getting root's IDs
						$roots = $category->getRootCats();
						foreach ($roots as $root)
						{
							$rootIds[] = $root['CATEGORY_ID'];
						}
						//building trees for every root category
						foreach($rootIds as $rootId):?>
							<br
							<input type="checkbox" id="<?echo $rootId?>" name="CATEGORY<?echo $rootId?>">
							<a href="/category/<?echo $rootId?>"><?echo $category->getById($rootId)->getName()?></a>
							<?$rel = $category->getRelations();
							echo $category->getTree($rel, $rootId, 0, 0, 0, 0);?>
						<?endforeach;?>
					</div>
				</div>
				<div class="btn-group" role="group" aria-label="Basic example">
					<button type="submit" class="btn btn-outline-primary btn-lg" form="addForm" name="SAVE" value="SAVE">Сохранить</button>
					<button type="submit" class="btn btn-outline-primary btn-lg" form="addForm" name="APPLY" value="APPLY">Применить</button>
					<a href="/admin/category/" class="btn btn-outline-danger btn-lg">Отмена</a>
				</div>
			</form>
		</div>
	</body>
</html>

