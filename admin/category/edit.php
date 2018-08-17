<?php
// ini_set('display_errors','On');
// error_reporting(E_ALL|E_STRICT);
include_once '../../classes/Author.php';
include_once '../../classes/Category.php';
include_once '../../classes/News.php';

if ($_POST['edit'] == 'edit')
{
	echo $_POST;
    //add data into categories table
	$category = new category;
	$news = new news;
	//getting instance of category
	$id = intval(htmlspecialchars($_GET["id"]));
	$category = $category->getById($id);
	//edit category instance
	var_dump($_POST);
    $category->edit($id, $_POST['NAME'], intval($_POST['SORT']));
    //extracting chosen news IDs from _POST
	$keysArr = array_keys($_POST, 'on');
	foreach ($keysArr as $value)
	{
		if(substr($value, 0, 4) == 'NEWS')
		{
			$newNewsIds[] = (intval(substr($value, 4)));
		}
	}

	//edit news in news_categories table
	//getting current news IDs
	$curNews = $category->getNews($id);
	foreach ($curNews as $curNew)
	{
		if($curNew)
		{
			$curNewsIds[] = $curNew->getId();
		}
	}
	//if news updated, change news_authors table
	//search for elements to delete
	$newsDelete = array_diff($curNewsIds, $newNewsIds);
	if($newNewsIds == null)
	{
		$newsDelete = $curNewsIds;
	}
	foreach ($newsDelete as $newDelete)
	{
		$news->deleteCategory($newDelete, $id);
	}
	//search for elements to add
	$newsAdd = array_diff($newNewsIds, $curNewsIds);
	if($curNewsIds == null)
	{
		$newsAdd = $newNewsIds;
	}
	foreach ($newsAdd as $newAuthor)
	{
		$news->setCategory($newAuthor, $id);
	}

	//edit parent_categories table
	//getting current parent categories IDs
	$curCatsIds = $category->getParents($id);
	foreach ($curCatsIds as $curCatId)
	{
		if($curCatId)
		{
			$curCatsIds[] = $curCatId;
		}
	}
	
    //extracting chosen parent_categories IDs from _POST
    unset($keysArr);
	$keysArr = array_keys($_POST, 'on');
	foreach ($keysArr as $value)
	{
		if(substr($value, 0, 8) == 'CATEGORY')
		{
			$newCatsIds[] = (intval(substr($value, 8)));
		}
	}
	//if categories updated, change parent_categories table
	//search for elements to delete
	$catsDelete = array_diff($curCatsIds, $newCatsIds);
	if($newCatsIds == null)
	{
		$catsDelete = $curCatsIds;
	}
	foreach ($catsDelete as $catDelete)
	{
		$category->deleteParent($id, $catDelete);
	}
	//search for elements to add
	$catsAdd = array_diff($newCatsIds, $curCatsIds);
	if($curCatsIds == null)
	{
		$catsAdd = $newCatsIds;
	}
	foreach ($catsAdd as $newCategory)
	{
		$category->setParent($id, $newCategory);
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
		<title>Edit category</title>
	</head>
	<body>
		<?if($_POST['SAVE'] == "SAVE"):
			header("Location: /admin/category/");
			exit;
		endif;?>
		<div class="container">
			<div class="page-header">
				<h1 style = 'text-align: center;'>Изменить категорию</h1>
			</div>
			<form role="form" method="POST" action="" id="editForm">
				<div class="form-group">
					<label for="name">Название категории</label>
					<?$category = new category;
					$id = intval(htmlspecialchars($_GET["id"]));
					$category = $category->getById($_GET['id']);?>
					<input type="text" class="form-control" id="name" placeholder="Введите название категории:" name="NAME" value="<?echo $category->getName()?>" required>
				</div>
				<div class="form-group">
					<label for="sort">Сортировка</label>
					<input type="text" class="form-control" id="sort" placeholder="Введите сортировочный номер:" value="<?echo $category->getSort()?>" pattern="^[0-9]+$" name="SORT" required>
				</div>
				<div class="row">
					<div class="form-group col-3">
						<label for="authors">Новости</label>
						<?$news = new news;
						$category = new category;
						$arNews = $news->getList(99, 1);
						//getting IDs of linked news
						$news = $category->getNews($_GET['id']);
						foreach ($news as $new)
						{
							$newsIds[] = $new->getId();
						}
						//add 'checked' attribute to news
						foreach($arNews as $arNew):?>
							<br>
							<input type="checkbox" name="NEWS<?echo $arNew->getId()?>" 
							<?foreach ($newsIds as $newId):
							
								if($arNew->getId() == $newId)
								{
									echo " checked";
								}
							endforeach?>>
							<!-- news links output -->
								<div style="display: inline; margin-left: 10;">
									<a href="/news/<?=$arNew->getId()?>/">
										<?=$arNew->getHeader()?>
									</a>
								</div>
						<?endforeach;?>			
					</div>
					<div class="form-group 	col">
						<label>Категория входит в:</label>
						<?
						$category = new category;
						//getting root's IDs
						$roots = $category->getRootCats();
						foreach ($roots as $root)
						{
							$rootIds[] = $root['CATEGORY_ID'];
						}
						//building trees for every root category
						foreach ($rootIds as $rootId):?>
							<br>
							<?$rel = $category->getRelations();
							//output root category?>
							<input type="checkbox" id="<?echo $rootId?>" name="CATEGORY<?echo $rootId?>"
							<?$curIds = $category->getParents($id);
							foreach ($curIds as $curId):
								if($rootId == $curId):
									echo " checked";
								endif;
							endforeach;?>>
							<a href="/category/<?echo $rootId?>"><?echo $category->getById($rootId)->getName()?></a>
							<?echo $category->getTree($rel, $rootId, 0, $id, $rootId, '');						
						endforeach;?>
					</div>
				</div>
				<input type="text" name='edit' hidden>
				<div class="btn-group row">
					<button  type="submit" class="btn btn-outline-primary btn-lg" form='editForm' name='SAVE' value='SAVE'>Сохранить</button>
					<button type="submit" class="btn btn-outline-primary btn-lg col-auto" form="editForm" name='APPLY' value='APPLY'>Применить</button>
					<a href="/admin/category/" class="btn btn-outline-danger btn-lg col-auto">Отмена</a>
				</div>
				<input type="text" name="edit" value="edit" hidden>
			</form>
		</div>
	</body>
</html>