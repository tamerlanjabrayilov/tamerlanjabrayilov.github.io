<?
	array_push($MODULES,"menu");
// Menu functions

function GetMenuItem($id)
{
	$query = " 	SELECT 
					*
				FROM 
					menu
				WHERE id='$id'"; 
	$result = query($query,"s");
	return $result;
}

function GetMenuSet($menu_set_id=0)
{
	global $SITE_LANGUAGE_ID;
	$query = " 	SELECT 
					*
				FROM 
					menu_set";
	if ($menu_set_id!=0)
		$query .= " WHERE id='$menu_set_id' ";
	else 
		$query .= " ORDER BY id"; 
	$result = query($query,"s");
	return $result;
}

function SelectMenuParent($parent_id,$menu_set_id,$nearest=false)
{
	$query = " 	SELECT DISTINCT
					*
				FROM 
					menu
				WHERE menu_set_id='$menu_set_id' 
				AND id='$parent_id'";
	$tresult = query($query,"s");
	
	if ($nearest || empty($tresult[0]['parent_id']))
	{
		return @$tresult[0]['id'];
	}
	else
	{
		return SelectMenuParent($tresult[0]['parent_id'],$menu_set_id);
	}
}

function SelectMenuChilds($id=0,$menu_set_id=0,$column=0) //column - определяет столбец для отобравения 1 или 2
{
	global $SITE_LANGUAGE_ID;
	$query = " 	SELECT DISTINCT
					menu.*
				FROM 
					menu
				LEFT JOIN menu_set ON menu_set.id=menu.menu_set_id
				WHERE menu.menu_set_id='$menu_set_id' ";
	if (!empty($column))
		$query .= " AND menu.colum='$column'";
	$query .= " AND menu.parent_id='$id'";
	$query .= "	ORDER BY menu.parent_id, menu.weight"; 
	$result = query($query,"s");
	return $result;
}

function ShowMenu($menu_id,$menu_set_id,$level,$type="menu",$current=0,$parent="",$disable=false, $chpu="")
{
	global $SITE_LANGUAGE_ID, $ROOT, $IMAGES, $CMS_ROOT, $ROOT_RELATIVE, $MODULES, $INSIDE_SHOP, $TEXT_VARS;

	$sections = SelectMenuChilds($menu_id,$menu_set_id);
	
	$menu_size = sizeof($sections);
	$count=1;
	foreach ($sections as $s)
	{
		$section="";
		if ($type=="menu")
		{
			if (substr($s['href'],0,4)=="http")
				$section .= "<a href=\"".$s['href']."\" title='".str_replace("<br>"," ",$s['menu_name'."_l$SITE_LANGUAGE_ID"])."' ";
			else
				$section .= "<a href=\"/".$s['href']."/\" title='".str_replace("<br>"," ",$s['menu_name'."_l$SITE_LANGUAGE_ID"])."' ";
			$section .=	">";
			$section .= str_replace(" ","&nbsp;",$s['menu_name'."_l$SITE_LANGUAGE_ID"])."</a>";
			echo $section;
		}
		elseif ($type=="bottom_menu")
		{
			if (substr($s['href'],0,4)=="http")
				$section .= "<a href=\"".$s['href']."\" title='".str_replace("<br>"," ",$s['menu_name'."_l$SITE_LANGUAGE_ID"])."' ";
			else
				$section .= "<a href=\"/".$s['href']."/\" title='".str_replace("<br>"," ",$s['menu_name'."_l$SITE_LANGUAGE_ID"])."' ";
			$section .=	">";
			$section .= str_replace(" ","&nbsp;",$s['menu_name'."_l$SITE_LANGUAGE_ID"])."</a>";
			echo $section;
		}
		elseif ($type=="top_menu")
		{
			if ($level==0)
				$section = "<div class=\"tm\">";
			elseif ($level>0)
				$section = "<div class=\"sm\">";
				
			if (empty($s['href']))
				$section .= "<strong>";
			elseif (substr($s['href'],0,4)=="http")
				$section .= "<a href=\"".$s['href']."\" title=\"".str_replace("<br>"," ",$s['menu_name'."_l$SITE_LANGUAGE_ID"])."\" >";
			else
				$section .= "<a href=\"/".$s['href']."/\" title=\"".str_replace("<br>"," ",$s['menu_name'."_l$SITE_LANGUAGE_ID"])."\" >";
			$section .= str_replace(" ","&nbsp;",$s['menu_name'."_l$SITE_LANGUAGE_ID"]);
			
			if (empty($s['href']))
				$section .= "</strong>";
			else 
				$section .= "</a>";

			$section .= "</div>";
			
			if ($s['href']=="heal") // add all heal types
			{
				$toursHeal = GetToursHeal();
				foreach ($toursHeal as $c)
				{
					$section .=	"<div class='sm'><a href='/heal/".$c['id']."/'>".$c['heal']."</a></div>";
				}
			}
			
			echo $section;
		}
		elseif ($type=="menu_at_list")
		{
			if ($level==0)
				$section = "<div class='mi";
			elseif ($level>0)
				$section = "<div class='smi";

			if ($s['href']==$chpu)
				$section .=	"a";
				
			$section .= "'><a ";
			
			$section .=	" href='/".$s['href']."/'>".str_replace("<br>"," ",$s['menu_name_l'.$SITE_LANGUAGE_ID])."</a></div>";
			echo $section;

		}
		elseif ($type=="country_index")
		{
			if ($level==0)
				$section = "<div class='mi";
			elseif ($level>0)
			{
				$section = "<div class='smi";
				if ($s['href']==$chpu)
					$section .=	"a";
			}
			$section .= "' ";
			
			if ($count==1)
				$section .= " id='mtop'";
			elseif ($count==$menu_size)
				$section .= " id='mbot'";
			
			$section .= ">";
			if ($level==0)
				$section .=	"<h1>".strip_tags($s['menu_name_l'.$SITE_LANGUAGE_ID])."</h1></div>";
			else
				$section .=	"<a href='/".$s['href']."/'>".strip_tags($s['menu_name_l'.$SITE_LANGUAGE_ID])."</a></div>";
			$count++;	
			echo $section;

		}
		elseif ($type=="cms")
		{

			$query = " 	SELECT
							MAX(weight) as max_weight
						FROM 
							menu
						WHERE menu_set_id='$menu_set_id' AND parent_id='$parent' ";
			$max = query($query,"s");
			$max = $max[0]['max_weight'];
			
			if ($s['colum']==1)
				$bgcolor = " background:url(images/lcolumn.gif) top right no-repeat; ";
			elseif ($s['colum']==2)
				$bgcolor = " background:url(images/rcolumn.gif) top right no-repeat; ";
			else
				$bgcolor = "";

			echo "<div class='cms_menu_item' style='margin-left:".(20*$level)."px; margin-top:10px; $bgcolor'>";
			if ($s['weight']!=0)
				echo "<img src='images/n.gif' class='but_up' onClick=\"document.menu_list.doit.value='up';document.menu_list.id.value='".$s['id']."';document.menu_list.parent_id.value='".$s['parent_id']."';document.menu_list.submit();\"> ";
			else
				echo "<img class='but' src='images/up_g.gif'> ";
			if ($s['weight']!=$max)
				echo "<img src='images/n.gif' class='but_down' onClick=\"document.menu_list.doit.value='down';document.menu_list.id.value='".$s['id']."';document.menu_list.parent_id.value='".$s['parent_id']."';document.menu_list.submit();\"> ";
			else
				echo "<img class='but' src='images/down_g.gif'> ";
				
				
			if ($level==0)	
				$menu_name = "<h2>".str_replace("<br>"," ",$s['menu_name'."_l$SITE_LANGUAGE_ID"])."</h2>";
			else if ($level==1)
				$menu_name = "<h3>".str_replace("<br>"," ",$s['menu_name'."_l$SITE_LANGUAGE_ID"])."</h3>";
			else
				$menu_name = "".str_replace("<br>"," ",$s['menu_name'."_l$SITE_LANGUAGE_ID"])."";
				
			echo " &nbsp;&nbsp;&nbsp;<a href=\"/cms/menu_edit.php?id=".$s['id']."&menu_set_id=$menu_set_id\">$menu_name</a> &nbsp;&nbsp;&nbsp;";
			echo "<select name='menu_".$s['id']."' onChange=\"document.menu_list.doit.value='swap';document.menu_list.id.value='".$s['id']."';document.menu_list.parent_id.value=this.value;document.menu_list.submit();\"><option value='0'>ROOT</option>";
			ShowMenu(0,$menu_set_id,0,"select",$s['id'],$s['parent_id']);
			echo "</select>";
			$mc = SelectMenuChilds($s['id'],$s['menu_set_id']);
			if (!empty($mc))
				echo " &nbsp;&nbsp;&nbsp;<img class='but' src='images/delete_g.gif'> </div>";
			else
				echo " &nbsp;&nbsp;&nbsp;<img src='images/n.gif' class='but_del' onClick=\"if (confirm('Уверены, что хотите удалить? Операцию нельзя будет отменить.')){document.menu_list.doit.value='delete';document.menu_list.id.value='".$s['id']."';document.menu_list.parent_id.value='".$s['parent_id']."';document.menu_list.submit();}else{return false;}\"> </div>";
		
		}
		elseif ($type=="select")
		{

			$section="";
			if ($s['id']!=$current && !$disable)
			{
				$section = "<option value=".$s['id']." ";
				if ($s['id']==$parent)
					$section .= " selected ";
				$section .= " >";
				for ($i=0; $i<=$level; $i++)
					$section .= "- ";
				$section .= substr($s['menu_name'."_l$SITE_LANGUAGE_ID"],0,75)."</option>";
			}
			echo $section;
		}

		$level++;
		if ($s['id']==$current)
			$disable = true;
		ShowMenu($s['id'],$menu_set_id,$level,$type,$current,$s['id'],$disable,$chpu);
		$level--;
		if ($s['id']==$current)
			$disable = false;
	}
}

?>