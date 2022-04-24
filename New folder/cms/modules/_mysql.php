<?

// MySQL functions

//	DB connection
if (!isset($dblink))
{
	@	$dblink=mysql_connect($config->host, $config->login, $config->pass);
	@	mysql_select_db($config->dbname,$dblink) or die("SELECT DB failed");
	mysql_query("SET NAMES utf8") or die("SET NAMES failed");

//	mysql_query("SET NAMES cp1251") or die("DB connection failed. Check login/password");
//	mysql_query("SET CHARACTER SET cp1251") or die("OoO");
	$insert_id = 0;
}


// Connection close
function MySQLClose($dblink)
{
	@ mysql_close($dblink);
}


// MySQL query
function query($q,$mode="s")
{
	global $config, $dblink, $insert_id, $last_result_rows;

	$res = Array();
//	echo "Query: $q\n";

	if (! $query_res = mysql_query($q,$dblink) )
	{
		die(mysql_error());
	}	
	$insert_id = mysql_insert_id ($dblink);

	if($mode == "s") 
	{
    	while ($qr = mysql_fetch_array($query_res)) 
		{
    		array_push($res, $qr);
		}
		$last_result_rows = mysql_num_rows($query_res);
    	mysql_free_result($query_res);
	}
	elseif ($mode == "i")
		return $insert_id;
	else
		$last_result_rows = mysql_affected_rows($dblink);

	return $res;
}

function GetFields($table)
{
	global $dblink,$config;
	$fields = mysql_list_fields($config->dbname, $table, $dblink);

	return $fields;
}

?>