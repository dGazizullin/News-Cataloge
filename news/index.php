<?php
require '../classes/News.php';
$news = new news();
$id = htmlspecialchars($_GET["id"]);
if($id > 0)
{
	$news = $news->getById($id);
}?>
<html>
<head>
	<title>
		<?if($news && $id != 0)
		{
			echo $news->getHeader();
		}else if($id == 0)
		{
			echo "News";
		}else
		{
			echo 'Ошибка';
		}?>
	</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<?if(intval($id) > 0 && $news)
	{
		include 'table.php';
	}else if($id == 0 && $news)
	{
		$news = new news();
		$arNews = $news->getList(99, 1);?>
		<ul>
			<?foreach($arNews as $arNew):?>
				<li>
					<a href="/news/<?=$arNew->getId()?>/">
						<?=$arNew->getHeader()?>
					</a>
				</li>
			<?endforeach;?>
		</ul><?
	}else
	{
		echo "Новость не найдена.";
	}?>
</body>
</html>
