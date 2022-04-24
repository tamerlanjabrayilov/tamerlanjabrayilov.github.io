<?
	include ("_configs.php");
	include ("modules/_authorization.php");

	CheckModuleAccess("pages");

	if (@$_SESSION['LoggedUser']['level']<50)
		DropError("Доступ закрыт! Недостаточно прав!<br><a href='$CMS_ROOT'>вернуться в начало</a>");

	@ $menu_set_id = $_GET['menu_set_id'];
	$title = (empty($menu_set_id)?"Все меню сайта":"<a href='menu_list.php'>Все меню</a> :: Редактирование структуры меню");
	include ("cms_template/_top.php");
//----------------------------------------------

?>

	<style>
		.cms_menu_item { padding:0px 0px 0px 0px;}
		.cms_menu_item select {font-size:11px; display:inline}
		.cms_menu_item select {font-size:11px; display:inline}
		.cms_menu_item h2 { font-size:20px;}
		.cms_menu_item h3 { font-size:15px;}
		.cms_menu_item h2,h3 {display:inline}
		.but {border:none; width:16px; height:16px; }
		.but_down{background:url(images/down.gif) no-repeat; border:none;  width:16px; height:16px; cursor:pointer }
		.but_up{background:url(images/up.gif) no-repeat; border:none;  width:16px; height:16px; cursor:pointer }
		.but_del{background:url(images/delete.gif) no-repeat; border:none;  width:16px; height:16px; cursor:pointer }
	</style>
			<form name="menu_list" method="get" action="menu_list.php">
			<?
				if (empty($menu_set_id))
				{
					$MenuSet = GetMenuSet();
					
					foreach ($MenuSet as $ms)
					{
						$section = "<div class='cms_menu_item'><h2><a href='menu_list.php?menu_set_id=".$ms['id']."'>";
						$section .= $ms['description'];
						$section .= "</a></h2></div>";
						echo $section;
					}
				}
				else
				{
					@ $do = $_GET['doit'];
					@ $id = $_GET['id'];
					@ $parent_id = $_GET['parent_id'];

					echo "<input type='hidden' name='doit' value='$do'>";
					echo "<input type='hidden' name='id' value='$id'>";
					echo "<input type='hidden' name='parent_id' value='$parent_id'>";
					echo "<input type='hidden' name='menu_set_id' value='$menu_set_id'>";


					if ($do=="up")
					{
						$query = " 	SELECT DISTINCT
										weight
									FROM 
										menu
									WHERE id='$id'"; 
						$menu_weight = query($query,"s");
						@ $menu_weight = $menu_weight[0]['weight'];
						if ($menu_weight>0)
						{
							$query = " 	UPDATE menu SET
											weight = weight+1
										WHERE weight = '".($menu_weight-1)."' AND menu_set_id='$menu_set_id' AND parent_id='$parent_id'"; 
	//						echo "up: $query<br>";
							query($query,"u");					
							$query = " 	UPDATE menu SET
											weight = weight-1
										WHERE id = '$id'"; 
	//						echo "up2: $query<br>";
							query($query,"u");					
						}
					}
					elseif ($do=="down")
					{
						$query = " 	SELECT DISTINCT
										weight
									FROM 
										menu
									WHERE id='$id'"; 
						$menu_weight = query($query,"s");
						@ $menu_weight = $menu_weight[0]['weight'];

						$query = " 	SELECT
										MAX(weight) as max_weight
									FROM 
										menu
									WHERE menu_set_id='$menu_set_id' AND parent_id='$parent_id' ";
						$max = query($query,"s");
						$max = $max[0]['max_weight'];
			
						if ($menu_weight<$max)
						{
	
							$query = " 	UPDATE menu SET
											weight = weight-1
										WHERE weight = '".($menu_weight+1)."' AND menu_set_id='$menu_set_id' AND parent_id='$parent_id'"; 
							query($query,"u");					
							$query = " 	UPDATE menu SET
											weight = weight+1
										WHERE id = '$id'"; 
							query($query,"u");
						}						
					}
					elseif ($do=="swap")					
					{
						$query = " 	SELECT
										COUNT(weight) as total_weight
									FROM 
										menu
									WHERE menu_set_id='$menu_set_id' AND parent_id='$parent_id' ";
						$count = query($query,"s");
						$count = $count[0]['total_weight'];
								
						$query = " 	SELECT
										parent_id,
										weight
									FROM 
										menu
									WHERE id='$id'";
						$old_parent = query($query,"s");
						$old_parent_id = $old_parent[0]['parent_id'];
						$old_weight = $old_parent[0]['weight'];
						
						$query = " 	UPDATE menu SET
										parent_id = '$parent_id',
										weight='".($count)."'
									WHERE id = '$id'"; 
						query($query,"u");					
					
						$query = " 	UPDATE menu SET
										weight=weight-1
									WHERE menu_set_id='$menu_set_id' AND parent_id='$old_parent_id' AND weight>'$old_weight'"; 
						query($query,"u");					
					
					}
					elseif ($do=="delete")					
					{
						
						$mc = SelectMenuChilds($id,$menu_set_id);
						if (!empty($mc))
						{
							echo "<script language='javascript'>alert('У этого пункта меню есть подпункты! Сперва удалите их или перенесите.');location.href='menu_list.php?menu_set_id=$menu_set_id';</script>";
						}
						else
						{
							$query = " 	SELECT
											COUNT(weight) as total_weight
										FROM 
											menu
										WHERE menu_set_id='$menu_set_id' AND parent_id='$parent_id' ";
							$count = query($query,"s");
							$count = $count[0]['total_weight'];
									
							$query = " 	SELECT
											parent_id,
											weight
										FROM 
											menu
										WHERE id='$id'";
							$old_parent = query($query,"s");
							$old_parent_id = $old_parent[0]['parent_id'];
							$old_weight = $old_parent[0]['weight'];
							
							$query = " 	DELETE FROM menu
										WHERE id = '$id'"; 
							query($query,"d");
						
							$query = " 	UPDATE menu SET
											weight=weight-1
										WHERE menu_set_id='$menu_set_id' AND parent_id='$old_parent_id' AND weight>'$old_weight'"; 
							query($query,"u");					
							echo "<script language='javascript'>location.href='menu_list.php?menu_set_id=$menu_set_id';</script>";
						}
					}
					
					ShowMenu(0,$menu_set_id,0,"cms",0,0);
					echo "<br><br><a href='menu_list.php' class='button'>Вернуться к списку</a><br><br>";
				}
			?>
			</form>

<? 
//----------------------------------------------
	include ("cms_template/_bottom.php");
?>