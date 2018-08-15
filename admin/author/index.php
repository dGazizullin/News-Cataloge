<?php
include_once '../../classes/Author.php';
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
				margin-bottom: 2.3rem
			}
			.btn-group
			{
				position: absolute;
				bottom: 0.8rem;
			}
			.sort
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
				if(confirm("Удалить автора?"))
				{
					return true;
				}
				return false;
			}
		</script>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Authors</title>
	</head>
	<body>
		<div class="container">
			<div class="page-header">
				<h1>Авторы</h1>
			</div>
			<a href="/admin/author/add/" class="btn btn-success btn-block row">Добавить автора</a>
			<div class="card-group row">
				<?$author = new author;
				$authors = $author->getList();
				foreach ($authors as $author):?>
					<div style="order: <?echo $author->getSort()?>">
						<div class="card col-auto">
							<img class="card-img-top " src="<?echo $author->getAv()?>" alt="Не удалось загрузить аватар">
							<div class="card-body">
								<h5 class="card-title">
									<a href="/author/<?echo $author->getId()?>/">
														<?echo $author->getFirstName()." ";
														echo $author->getLastName()." ";
														echo $author->getPatronimic()." ";?>
									</a>
								</h5>
								<p class="card-text"><strong>Новости автора:</strong><br>
									<?$news = new news;
									$news = $author->getNews($author->getId());
									foreach ($news as $new):?>
										<span>
											<a href="/news/<?echo $new->getId()?>/"><?echo $new->getHeader().'<br>';?></a>
										</span>
									<?endforeach?>
								</p>
								<div class='sort'>
									<strong>Сортировка:</strong>
									<?
										echo ' '.$author->getSort();
									?>
								</div>
								<div class="btn-group" role="group" aria-label="Basic example">
									<a href="/admin/author/edit/<?echo $author->getId()?>" class="btn btn-primary">Изменить</a>
									<a href="/admin/author/delete/<?echo $author->getId()?>" class="btn btn-outline-danger" onclick="return del()">Удалить</a>
								</div>
							</div>
						</div>
					</div>
				<?endforeach?>
			</div>
		</div>
	</body>
</html>