<?php
include_once '../../classes/Author.php';
include_once '../../classes/Category.php';
include_once '../../classes/News.php';
error_reporting(E_ALL);
?>

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
				margin-bottom: 1.2rem
			}
			.btn-group
			{
				position: absolute;
				bottom: 0.8rem;
			}
			.announcement
			{
				margin-bottom: 0rem;
			}
			h1
			{
				text-align: center;
			}
		</style>
		<script>
			function del()
			{
				if(confirm("Удалить новость?"))
				{
					return true;
				}
				return false;
			}
		</script>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>News</title>
	</head>
	<body>
		<div class="container">
			<div class="page-header">
				<h1>Новости</h1>
			</div>
			<a href="/admin/news/add/" class="btn btn-success btn-block row">Добавить новость</a>
			<div class="card-group row">
					<?$news = new news;
					$newsAr = $news->getList();
					foreach ($newsAr as $new):?>
						<div style="order: <?echo $new->getSort()?>">
							<div class="card col-auto">
								<div class="card-body">
									<h5 class="card-title">
										<a href="/news/<?echo $new->getId()?>/">
															<?echo $new->getHeader()." ";?>
										</a>
									</h5>
									<p class="card-text announcement">
											<strong>Анонс новости:</strong>
											<?echo substr($new->getAnnouncement(), 0, 130);
											if(strlen(substr($new->getAnnouncement(), 0, 130)) < strlen($new->getAnnouncement()))
											{
												echo "...";
											}?>
										<div>
											<strong>Авторы новости:</strong>
											<?$author = new author;
											$authors = $new->getAuthors($new->getId());
											foreach ($authors as $author):?>
												<div>
													<a href="/author/<?echo $new->getId()?>">
													<?echo $author->getFirstName().' ';
													echo $author->getLastName().' ';
													echo $author->getPatronimic().'<br>';?>
													</a>
												</div>
											<?endforeach?>
										</div>
										<div>
											<strong>Категории новости:</strong>
											<?
											$category = new category;
											$cats = $new->getCategories($new->getId());
											foreach ($cats as $category):?>
												<div>
													<a href="/category/<?echo $category->getId()?>">
													<?echo $category->getName();?>
													</a>
												</div>
											<?endforeach?>
										</div>
										<div class='sort'>
											<strong>Сортировка:</strong>
											<?
												echo ' '.$new->getSort();
											?>
										</div>
									</p>
									<div class="btn-group" role="group" aria-label="Basic example">
										<a href="/admin/news/edit/<?echo $new->getId()?>" class="btn btn-primary">Изменить</a>
										<a href="/admin/news/delete/<?echo $new->getId()?>" class="btn btn-outline-danger" onclick="return del()">Удалить</a>
									</div>
								</div>
							</div>
						</div>
					<?endforeach?>
			</div>
		</div>
	</body>
</html>