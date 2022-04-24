<?
	array_push($MODULES,"users");
// users functions

function GetUser($id="*",$group_id=0)
{
	global $SITE_LANGUAGE_ID;
		$query = " 	SELECT 
						users.*,
						UNIX_TIMESTAMP(users.date_registered) as date_registered,
						groups.id as group_id,
						groups.name as group_name,
						groups.access_level,
						groups.available_modules
					FROM 
						users
					LEFT JOIN groups ON groups.id = users.group_id 
					WHERE 1=1 ";

	if (!empty($group_id))
		$query .= " AND groups.id='$group_id' "; 

	if ($id=="*")
		$query .= " ORDER BY users.name DESC "; 
	else
		$query .= " AND users.id='$id' "; 

	$result = query($query,"s");
	if (!empty($result))
		return $result;
//	else
//		DropError("Error! Incorrect index");
	
}

function GetGroup($id=0)
{
	global $SITE_LANGUAGE_ID;
	if ($id==0) // Get all groups
	{
		$query = " 	SELECT 
						*
					FROM 
						groups
					WHERE access_level<='".$_SESSION['LoggedUser']['level']."'
					ORDER BY access_level"; 
	}
	else // Get provided group
	{
		$query = " 	SELECT 
						*
					FROM 
						groups
					WHERE id='$id'"; 
	}
	$result = query($query,"s");
	if (!empty($result))
		return $result;
	else
		DropError("Error! Incorrect index");
}


?>