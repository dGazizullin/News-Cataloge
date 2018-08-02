<?include "../classes/News.php";?>
<table border = "1" cellpadding="20">
	<caption>Authors</caption>
	<th>ID</th>
	<th>firstName</th>
	<th>lastName</th>
	<th>patronimic</th>
	<th>avatar</th>
	<th>sign</th>
	<th>news</th>
	<tr>
		<td><?echo $author->getId();?></td>
		<td><?echo $author->getFirstName();?></td>
		<td><?echo $author->getLastName();?></td>
		<td><?echo $author->getPatronimic();?></td>
		<td><?echo $author->getAv();?></td>
		<td><?echo $author->getSign();?></td>
		<td><?$newsIds = $author->getNews($author->getId());
			echo "<pre>";
			foreach ($newsIds as $news)
			{
				$newsId = $news->getId();
				echo "<a href='/news/$newsId/'>";
				$new = new news;
				$new = $new->getByID($newsId);
				echo $new->getHeader();
				echo '</a>'.'<br>';
			}
			echo "</pre>";?>
		</td>
	</tr>
</table>
