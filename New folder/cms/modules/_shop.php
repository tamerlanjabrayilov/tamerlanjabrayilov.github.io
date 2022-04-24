<?
	array_push($MODULES,"shop");


function ShowShopPath($category, $brand, $item)
{
	global $SITE_LANGUAGE_ID;

	$full_path="";
	
	if (!empty($category))
	{
		$sub_path = "";
		if (!empty($category['parent_id']))
		{
			$parent = GetCategory($category['parent_id']);
			
			while (!empty($parent[0]['id']) && !empty($parent[0]['parent_id']))
			{
				$sub_path = "&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;<a href=\"/catalog/".$parent[0]['chpu']."/\" title='".$parent[0]['name']."'>".$parent[0]['name']."</a>".$sub_path;
				$parent = GetCategory($parent[0]['parent_id']);
			}
			$sub_path = "&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;<a href=\"/catalog/".$parent[0]['chpu']."/\" title='".$parent[0]['name']."'>".$parent[0]['name']."</a>".$sub_path;
		}
		
		if (empty($item) && empty($brand))
			$full_path .= $sub_path."&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;<strong>".$category['name']."</strong>";
		else
			$full_path .= $sub_path."&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;<a href=\"/catalog/".$category['chpu']."/\" title='".$category['name']."'>".$category['name']."</a>";
	}
/*	
	if (!empty($brand))
		if (empty($item))
			$full_path .= "&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;<strong>".$brand['name']."</strong>";
		else
			$full_path .= "&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;<a href=\"/catalog/".$category['chpu']."/&brand=".$brand['chpu']."\" title='".$brand['name']."'>".$brand['name']."</a>";
*/		
	if (!empty($item))
	{
//		$brand = GetBrand($item['brand_id']);
//		$brand = $brand[0];
//		if (!empty($brand))
//			$full_path .= "&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;<a href=\"/catalog/".$category['chpu']."/&brand=".$brand['chpu']."\" title='".$brand['name']."'>".$brand['name']."</a>";
		$full_path .= "&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;<strong>".$item['name']."</strong>";
	}

	return $full_path;
}


function GetBrand($id=0,$chpu="")
{
	global $SITE_LANGUAGE_ID;
	$query = " 	SELECT 
					*
				FROM 
					item_brand
				WHERE 1=1 ";

	if (!empty($id))
		$query .= " AND id='$id'";
	elseif (!empty($chpu))
		$query .= " AND chpu='$chpu' ";
	else
		$query .= " ORDER BY name";

	$result = query($query,"s");
	return $result;
}

function GetCategory($id=0,$chpu="")
{
	global $SITE_LANGUAGE_ID;
	$query = " 	SELECT 
					*
				FROM 
					item_category
				WHERE 1=1 ";

	if (!empty($id))
		$query .= " AND id='$id'";
	elseif (!empty($chpu))
		$query .= " AND chpu='$chpu' ";
	else
		$query .= " ORDER BY weight";

	$result = query($query,"s");
	return $result;
}

function GetSubCategorys($id)
{
	$subcategorys = array();
	$sub = GetCategoryChilds($id);
	$subcategorys = array_merge($subcategorys,$sub);

	foreach ($sub as $s)
	{
		$subcategorys = array_merge($subcategorys,GetCategoryChilds($s['id']));
	}
	return $subcategorys;
}


function GetCategoryChilds($id=0)
{
	global $SITE_LANGUAGE_ID;
	$query = " 	SELECT DISTINCT
					*
				FROM 
					item_category
				WHERE parent_id='$id'
				ORDER BY weight"; 
	$result = query($query,"s");
	return $result;
}

function ShowCategory($id,$level,$type="menu",$current=0,$parent=0,$chpu="")
{
	global $SITE_LANGUAGE_ID, $ROOT, $IMAGES, $CMS_ROOT, $ROOT_RELATIVE, $MODULES, $TEXT_VARS;

	$childs = GetCategoryChilds($id);

	foreach ($childs as $s)
	{
		if ($type=="menu")
		{
			$subCats = GetCategoryChilds($s['id']);
			$subClass = "";
			if (sizeof($subCats)>0)
				$subClass = " haveSubs";
			
			$section = "<a class='l".$level.$subClass.($s['id']==$current?" haveSelected":"")."' href='/catalog/".$s['chpu']."/'>".$s['name']."</a>";
		}
		elseif ($type=="cms")
		{
			$section = "<a href='categorys_edit.php?id=".$s['id']."' class='size".($level+1)."'>".$s['name']."</a>";
		}
		elseif ($type=="select")
		{
			$section="";
			if ($s['id']!=$current && !$disable)
			{
				$section = "<option value='".$s['id']."' ";
				if ($s['id']==$parent)
					$section .= " selected ";
				$section .= " >";
				for ($i=0; $i<=$level; $i++)
					$section .= "- ";
				$section .= "".substr($s['name'],0,75)."</option> \n";
			}
		}
		echo $section;

		$level++;
		if ($s['id']==$current)
			$disable = true;
		ShowCategory($s['id'],$level,$type,$current,$parent,$chpu);
		$chpu = substr($chpu,0,strrpos($chpu,"/"));
		$level--;
		if ($s['id']==$current)
			$disable = false;
	}
	
}

function GetItems($id=0,$category_id=0,$brand_id=0,$page=0,$onpage=0,$hot=0,$order="",$not=0)
{
	global $SITE_LANGUAGE_ID;

	$query = " 	SELECT 
					item.*,
					(SELECT item_photo.filename FROM item_photo WHERE item_photo.item_id=item.id AND item_photo.isMain='1' LIMIT 1) AS filename,
					item_category.name AS category_name,
					item_category.chpu AS category_chpu,
					item_category.parent_id AS category_parent_id,
					item_brand.name AS brand_name,
					item_brand.chpu AS brand_chpu
				FROM 
					item
				LEFT JOIN item_brand ON item_brand.id=item.brand_id
				LEFT JOIN item_category ON item_category.id=item.category_id
				WHERE 1=1 ";

	if (!empty($not))
		$query .= " AND item.id!='$not'";
		
	if (!empty($category_id))
		$query .= " AND item.category_id='$category_id'";
		
	if (!empty($brand_id))
		$query .= " AND item.brand_id='$brand_id'";
		
	if (!empty($hot))
		$query .= " AND item.hot='1'";
		
	if (!empty($id))
		$query .= " AND item.id='$id'";
	else
		if (!empty($order))
			$query .= " ORDER BY $order";
		else
			$query .= " ORDER BY item.hot, item.brand_id, item_brand.name, item.name";
		
	if (!empty($page))
		$query .= " LIMIT ".(($page-1)*$onpage).", $onpage";

	$result = query($query,"s");
	return $result;
}
?>