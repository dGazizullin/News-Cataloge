<?php
require '../classes/Category.php';
$category = new category();
$id = htmlspecialchars($_GET["id"]);
if($id > 0)
{
	$category = $category->getById($id);
}?>
<html>
<head>
	<title>
		<?if($category && $id != 0)
		{
			echo $category->getName();
		}else if($id == 0)
		{
			echo 'Categories';
		}else
		{
			echo 'Ошибка';
		}?>
	</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<?if(intval($id) > 0 && $category)
	{
		include 'table.php';
	}else if($id == 0 && $category)
	{
		$category = new category();
		$catsAr = $category->getList(99, 1);?>
		<ul>
			<?foreach($catsAr as $catAr):?>
				<li>
					<a href="/category/<?=$catAr->getId()?>/">
						<?=$catAr->getName()?>
					</a>
				</li>
			<?endforeach;?>
		</ul><?
	}else
	{
		echo "Категория не найдена.";
	}?>
</body>
</html>
