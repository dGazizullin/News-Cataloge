<?php
include_once '../../classes/Author.php';
include_once '../../classes/News.php';?>

<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Add author</title>
	</head>
	<body>
		
		<div class="container">
			<div class="page-header">
				<h1 style='text-align: center;'>Добавить автора</h1>
			</div>
			<form role="form" method="POST" action="" id="addForm">
				<div class="form-group col-auto">
					<label for="lastName">Фамилия</label>
					<input type="text" class="form-control" id="lastName" placeholder="Введите фамилию:" name="LASTNAME" required>
				</div>
				<div class="form-group col-auto">
					<label for="firstName">Имя</label>
					<input type="text" class="form-control" id="firstName" placeholder="Введите имя:" name="FIRSTNAME" required>
				</div>
				<div class="form-group col-auto">
					<label for="patronimic">Отчество</label>
					<input class="form-control" id="patronimic" placeholder="Введите отчество:" name="PATRONIMIC" required>
				</div>
				<div class="form-group col-auto">
					<label for="avatar">Аватар</label>
					<input class="form-control" id="avatar" placeholder="Введите путь для аватара:" name="AVATAR" required>
				</div>
				<div class="form-group col-auto">
					<label for="sign">Подпись</label>
					<input class="form-control" id="sign" placeholder="Введите путь для подписи:" name="SIGN" required>
				</div>
				<div class="form-group col-auto">
					<label for="sort">Сортировка</label>
					<input type="text" class="form-control" id="sort" placeholder="Введите сортировочный номер" value="10" pattern="^[0-9]+$" name="SORT">
				</div>
				<div class="form-group col-auto">
					<label>Новости автора:</label>
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
				<div class="btn-group" role="group" aria-label="Basic example">
					<button type="submit" class="btn btn-outline-primary btn-lg" form="addForm" name="SAVE" value="SAVE">Сохранить</button>
					<button type="submit" class="btn btn-outline-primary btn-lg" form="addForm">Применить</button>
					<a href="/admin/author/" class="btn btn-outline-danger btn-lg">Отмена</a>
				</div>
			</form>
		</div>
	</body>
</html>

<?if ($_POST)
{
    //insert data into authors table
	$author = new author;
	$news = new news;
    $author->add($_POST['FIRSTNAME'], $_POST['LASTNAME'], $_POST['PATRONIMIC'], $_POST['AVATAR'], $_POST['SIGN'], intval($_POST['SORT']));
    //getting ID of added author
    $authorId = $author->getLastId();
    //extracting news IDs from _POST
	$keysArr = array_keys($_POST, 'on');
	foreach ($keysArr as $value)
	{
		if(substr($value, 0, 4) == 'NEWS')
		{
			$newsIds[] = (substr($value, 4));
		}
	}
	//add data to news_authors table
	foreach ($newsIds as $newsId)
	{
		$news->addAuthor($newsId, $authorId);
	}
}
if($_POST['SAVE'] == 'SAVE')
{
	header("Location: /admin/author/");
	exit;
}?>