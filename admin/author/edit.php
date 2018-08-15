<?php
include_once '../../classes/Author.php';
include_once '../../classes/Category.php';
include_once '../../classes/News.php';

if ($_POST)
{
    //add data into authors table
	$author = new author;
	$news = new news;
	//getting instance of author 
	$id = intval(htmlspecialchars($_GET["id"]));
	$author = $author->getById($id);
	//edit author instance
    $author->edit($id, $_POST['FIRSTNAME'], $_POST['LASTNAME'], $_POST['PATRONIMIC'], $_POST['AVATAR'], $_POST['SIGN'], intval($_POST['SORT']));
    //extracting chosen news IDs from _POST
	$keysArr = array_keys($_POST, 'on');
	foreach ($keysArr as $value)
	{
		if(substr($value, 0, 4) == 'NEWS')
		{
			$newNewsIds[] = (intval(substr($value, 4)));
		}
	}
	//edit news in news_authors table
	//getting current news IDs
	$curNews = $author->getNews($id);
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
		$news->deleteAuthor($newDelete, $id);
	}
	//search for elements to add
	$newsAdd = array_diff($newNewsIds, $curNewsIds);
	if($curNewsIds == null)
	{
		$newsAdd = $newNewsIds;
	}
	foreach ($newsAdd as $newAuthor)
	{
		$news->addAuthor($newAuthor, $id);
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
		<!-- find out which submit button was used to POST the form -->
		<?if($_POST['SAVE'] == "SAVE"):
			header("Location: /admin/author/");
			exit;
		endif;?>
		<div class="container">
			<div class="page-header">
				<h1 style = 'text-align: center;'>Изменить данные автора</h1>
			</div>
			<form role="form" method="POST" action="" id="editForm">
				<div class="form-group col-auto">
					<label for="lastName">Фамилия</label>
					<?$author = new author;
					$author = $author->getById($_GET['id']);?>
					<input type="text" class="form-control" id="lastName" placeholder="Введите фамилию:" name="LASTNAME" value="<?echo $author->getLastname()?>" required>
				</div>
				<div class="form-group col-auto">
					<label for="firstName">Имя</label>
					<input type="text" class="form-control" id="firstName" placeholder="Введите имя:" name="FIRSTNAME" value="<?echo $author->getFirstName()?>" required>
				</div>
				<div class="form-group col-auto">
					<label for="patronimic">Отчество</label>
					<input class="form-control" id="patronimic" placeholder="Введите отчество:" name="PATRONIMIC" value ="<?echo $author->getPatronimic()?>" required>
				</div>
				<div class="form-group col-auto">
					<label for="avatar">Аватар</label>
					<input class="form-control" id="avatar" placeholder="Введите путь для аватара:" name="AVATAR" value ="<?echo $author->getAv()?>" required>
				</div>
				<div class="form-group col-auto">
					<label for="sign">Подпись</label>
					<input class="form-control" id="sign" placeholder="Введите путь для подписи:" name="SIGN" value ="<?echo $author->getSign()?>" required>
				</div>
				<div class="form-group col-auto">
					<label for="sort">Сортировка</label>
					<input type="text" class="form-control" id="sort" placeholder="Введите сортировочный номер" value="<?echo $author->getSort()?>" pattern="^[0-9]+$" name="SORT">
				</div>
				<div class="form-group col-auto">
					<label for="authors">Новости</label>
					<?$news = new news();
					$arNews = $news->getList(99, 1);
					//getting IDs of linked news
					$news = $author->getNews($_GET['id']);
					foreach ($news as $new)
					{
						$newsIds[] = $new->getId();
					}
					//add 'checked' attribute to news
					foreach($arNews as $arNew):?>
						<br>
						<input type="checkbox" name="NEWS<?echo $arNew->getId()?>" 
						<?foreach ($newsIds as $newId)
						{
							if($arNew->getId() == $newId)
							{
								echo " checked";
							}
						}?>>
						<!-- news links output -->
							<div style="display: inline; margin-left: 10;">
								<a href="/news/<?=$arNew->getId()?>/">
									<?=$arNew->getHeader()?>
								</a>
							</div>
					<?endforeach;?>			
				</div>
				<div class="btn-group row">
					<button  type="submit" class="btn btn-outline-primary btn-lg" form='editForm' name='SAVE' value='SAVE'>Сохранить</button>
					<button type="submit" class="btn btn-outline-primary btn-lg col-auto" form="editForm">Применить</button>
					<a href="/admin/author/" class="btn btn-outline-danger btn-lg col-auto">Отмена</a>
				</div>
			</form>
		</div>
	</body>
</html>