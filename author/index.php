<?php
require '../classes/Author.php';
$author = new author();
$id = htmlspecialchars($_GET["id"]);
if($id > 0)
{
	$author = $author->getByID($id);
}?>
<html>
<head>
	<title>
		<?if($author && $id != 0)
		{
			echo $title = $author->getLastName().' '.$author->getFirstName();
		}else if($id == 0)
		{
			echo "Authors";
		}else
		{
			echo 'Ошибка';
		}?>
	</title>	
</head>
<body>
	<?if(intval($id) > 0 && $author)
	{
		include 'table.php';
	}
	else if($id == 0 && $author)
	{
		$author = new author();
		$arAuthors = $author->getList(99);?>
		<ul>
			<?foreach($arAuthors as $arAuthor):?>
				<li>
					<a href="/author/<?=$arAuthor->getId()?>/">
						<?=$arAuthor->getFirstName()?>
						<?=$arAuthor->getLastName()?>
						<?=$arAuthor->getPatronimic()?>
					</a>
				</li>
			<?endforeach;?>
		</ul><?
	}else
	{
		echo "Автор не найден.";
	}?>
</body>
</html>
