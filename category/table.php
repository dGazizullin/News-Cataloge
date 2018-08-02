<table border = "1" cellpadding="20">
	<caption>Categories</caption>
	<th>ID</th>
	<th>Name</th>
	<th>Parent categories</th>
	<th>Child categories</th>
	<tr>
		<td><?echo $category->getId();?></td>
		<td><?echo $category->getName();?></td>
		<td>
			<?$categories = $category->getParents($category->getId());
			foreach($categories as $catId)
				{
					echo "<a href='/category/$catId/'>";
					$cat = new category;
					$cat = $cat->getByID($catId);
					echo $cat->getName();
					echo '</a>'.'<br>';
				}?>
		</td>
		<td>
			<?$categories = $category->getChildren($category->getId());
			foreach($categories as $catId)
				{
					echo "<a href='/category/$catId/'>";
					$category = new category;
					$category = $category->getByID($catId);
					echo $category->getName();
					echo '</a>'.'<br>';
				}?>
		</td>
	</tr>
</table>
