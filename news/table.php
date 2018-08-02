<?include '../classes/Category.php';
include '../classes/Author.php';?>
<table border = "1" cellpadding="20">
	<caption>News</caption>
	<th>ID</th>
	<th>Header</th>
	<th>Announcement</th>
	<th>Text</th>
	<th>Categories</th>
	<th>Authors</th>
	<tr>
		<td><?echo $news->getId();?></td>
		<td><?echo $news->getHeader();?></td>
		<td><?echo $news->getAnnouncement();?></td>
		<td><?echo $news->getText();?></td>
		<td>
			<?$categories = $news->getCategories($news->getID());
			echo "<pre>";
			foreach ($categories as $category)
			{
				$catId = $category->getID();
				echo "<a href='/category/$catId/'>";
				$cat = new category;
				$cat = $cat->getByID($catId);
				print_r($cat->getName()) ;
				echo "</a>"."<br>";
			}
			echo "</pre>";?>
		</td>
		<td>
			<?$authors = $news->getAuthors($news->getID());
			echo "<pre>";
			foreach ($authors as $author)
			{
				$authorId = $author->getID();
				echo "<a href='/author/$authorId/'>";
				$author = new author;
				$author = $author->getByID($authorId);
				echo $author->getFirstName().' ';
				echo $author->getLastName().' ';
				echo $author->getPatronimic();
				echo "</a>"."<br>";
			}
			echo "</pre>";?>
		</td>
	</tr>
</table>
