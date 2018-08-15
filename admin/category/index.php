<?php
include_once '../../classes/Category.php';
include_once '../../classes/News.php';?>

<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<style type="text/css">
			.card
			{
				width: 23rem; 
				height: 100%;
			}
			.card-body
			{
				margin-bottom: 2rem
			}
			.btn-group
			{
				position: absolute;
				bottom: 0.8rem;
			}
			.parentCategories
			{
				margin-top: -1rem;
			}
			h1
			{
				text-align: center;
			}
		</style>
		<script>
			function del()
			{
				if(confirm("Удалить категорию?"))
				{
					return true;
				}
				return false;
			}
		</script>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Categories</title>
	</head>
	<body>
		<div class="container">
			<div class="page-header">
				<h1>Категории</h1>
			</div>
			<a href="/admin/category/add/" class="btn btn-success btn-block row">Добавить категорию</a>
			<div class="card-group row">
					<?$category = new category;
					$cats = $category->getList();
					foreach ($cats as $category):?>
						<div style="order: <?echo $category->getSort()?>">
							<div class="card col-auto">
								<div class="card-body">
									<h5 class="card-title">
										<a href="/category/<?echo $category->getId()?>/">
															<?echo $category->getName()." ";?>
										</a>
									</h5>
									<p class="card-text"><strong>Новости категории:</strong><br>
										<?$news = new news;
										$news = $category->getNews($category->getId());
										foreach ($news as $new):?>
											<span>
												<a href="/news/<?echo $new->getId()?>/"><?echo $new->getHeader();?></a>
											</span>
											<br>
										<?endforeach?>
									</p>
									<div class="parentCategories">
									 	<strong>Категория входит в:</strong>
									 	<?$catsIds = $category->getParents($category->getId());
								 		foreach ($catsIds as $catId)
								 		{?>
								 			<div>
								 				<a href="/category/<?echo $catId?>"> 
								 				<?
								 				$cat = $category->getById($catId);
								 				echo $cat->	getName();?>
								 				</a>
							 				</div><?
								 		}?>
										<div class='sort'>
											<strong>Сортировка:</strong>
											<?
												echo ' '.$category->getSort();
											?>
										</div>								 		
									</div>
									<div class="btn-group" role="group" aria-label="Basic example">
										<a href="/admin/category/edit/<?echo $category->getId()?>" class="btn btn-primary">Изменить</a>
										<a href="/admin/category/delete/<?echo $category->getId()?>" class="btn btn-outline-danger" onclick="return del()">Удалить</a>
									</div>
								</div>
							</div>
						</div>
					<?endforeach?>
			</div>
		</div>
	</body>
</html>