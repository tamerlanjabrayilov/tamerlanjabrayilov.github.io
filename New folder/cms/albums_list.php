<?
	include ("_configs.php");
	include ("modules/_authorization.php");

	$title = "Фото альбомы";
	include ("cms_template/_top.php");
//----------------------------------------------

?>
<style>
.small { font-size:12px;}
</style>
	<table class="tbl">
        <tr class="title">
            <td>Категории и альбомы</td>
        </tr>
        <tr>
            <td>
                <?
					$query = " 	SELECT 
									*
								FROM 
									albums_category
								ORDER BY weight"; 
					$albums_category = query($query,"s");
					foreach ($albums_category as $album_cat)
					{
						echo "<h1 style='margin:20px 0px 5px 0px;'>".$album_cat['name']." &nbsp; <a href='albums_category_edit.php?id=".$album_cat['id']."' class='small'>(редактировать)</a> <a href='albums_category_edit.php?id=".$album_cat['id']."&delete=1' class='small'>(удалить)</a></h1>";
						$query = " 	SELECT 
										albums.*,
										UNIX_TIMESTAMP(albums.date_created) as date_created,
										albums_category.id as category_id,
										albums_category.name as category_name
									FROM 
										albums 
									LEFT JOIN albums_category ON albums.category_id=albums_category.id
									WHERE albums.category_id='".$album_cat['id']."'
									ORDER BY albums.name"; 
						$albums = query($query,"s");
				
						foreach ($albums as $album)
						{
							echo "<h2><a href='albums_edit.php?id=".$album['id']."' style='display:block; padding:10px 0px 5px 20px;'>".$album['name']."</a></h2>";
						}
						
					}
                ?>
            </td>
        </tr>
        <tr>
            <td><br><a href="albums_category_edit.php?id=new" class="button">Добавить категорию</a> &nbsp; &nbsp; <a href="albums_edit.php?id=new" class="button">Создать новый альбом</a></td>
        </tr>		
	</table>
<? 
//----------------------------------------------
	include ("cms_template/_bottom.php");
?>