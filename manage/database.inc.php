<?
function dbConnect($server = DB_SERVER, $username = DB_USERNAME, $password = DB_PASSWORD, $database = DB_DATABASE, $link = 'db_link') {
	global $$link;

	$$link = mysql_connect($server, $username, $password);


	/*if (USE_PCONNECT == 'true') {
		$$link = mysql_pconnect($server, $username, $password);
	} else {*/
	$$link = mysql_connect($server, $username, $password);
	//}
	if ($$link) mysql_select_db($database);
	return $$link;
}

function dbClose($link = 'db_link'){
	global $$link;
	return mysql_close($$link);
}

function dbError($query, $errno, $error){
	die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000"></font></small><br><br></b></font>');
}

function dbQuery($query, $link = 'db_link'){
	global $link;
	$$link = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
	if ($$link) mysql_select_db(DB_DATABASE);
		$result = mysql_query($query, $$link) or dbError($query, mysql_errno(), mysql_error());
	return $result;
}

function dbPerform($table, $data, $action = 'insert', $parameters = '', $link = 'db_link') {
	reset($data);
	if ($action == 'insert') {
	$query = 'insert into ' . $table . ' (';
	while (list($columns, ) = each($data)) {
		$query .= $columns . ', ';
	}
	$query = substr($query, 0, -2) . ') values (';
	reset($data);
	while (list(, $value) = each($data)) {
		switch ((string)$value) {
			case 'now()':
				$query .= 'now(), ';
				break;
			case 'null':
				$query .= 'null, ';
				break;
			default:
				$query .= '\'' . dbInput($value) . '\', ';
				break;
		}
	}
	$query = substr($query, 0, -2) . ')';
	} elseif ($action == 'update') {
	$query = 'update ' . $table . ' set ';
	while (list($columns, $value) = each($data)) {
		switch ((string)$value) {
		case 'now()':
		$query .= $columns . ' = now(), ';
		break;
		case 'null':
		$query .= $columns .= ' = null, ';
		break;
		default:
		$query .= $columns . ' = \'' . dbInput($value) . '\', ';
		break;
		}
	}
	$query = substr($query, 0, -2);
		if ($parameters != "")
			$query .= ' where ' . $parameters;
	}
	
	return dbQuery($query, $link);
}

function dbFetchArray($db_query) {
	return mysql_fetch_array($db_query);
}

function dbNumRows($db_query) {
	return mysql_num_rows($db_query);
}

function dbDataSeek($db_query, $row_number) {
	return mysql_data_seek($db_query, $row_number);
}

function dbInsertID() {
	return mysql_insert_id();
}

function dbFreeResult($db_query) {
	return mysql_free_result($db_query);
}

function dbFetchFields($db_query) {
	return mysql_fetch_field($db_query);
}

function dbOutput($string) {
	return htmlspecialchars($string);
}

function dbInput($string) {
	return addslashes($string);
}

function dbPrepareInput($string) {
	if (is_string($string)) {
		return trim(db_sanitize_string(stripslashes($string)));
	}
	elseif (is_array($string)){
		reset($string);
		while (list($key, $value) = each($string)) {
			$string[$key] = dbPrepareInput($value);
		}
		return $string;
	}
	else {
		return $string;
	}
 }

function dbLastInsertID($table) {
	$query = "SELECT LAST_INSERT_ID() FROM `$table`";
	$resultset = dbQuery($query);
	$row = dbFetchArray($resultset);
	return $row[0];
}

function dbDeleteRecord($table, $id){
	dbQuery("DELETE FROM `$table` WHERE {$table}_id=$id");
}
function dbDeleteRecords($table, $where){
	$where = str_replace("%", $table . "_", $where);
	dbQuery("DELETE FROM `$table` WHERE $where");
}
function dbRowResults($table, $rowid) {
	if(!isset($rowid)){
		die("No $table id");
	}
	elseif($rowid == 0)
		die("$table id is zero");
	else{
		$query = "SELECT * FROM `$table` WHERE {$table}_id=$rowid";
		return dbQuery($query);
	}
}
function dbRow($table, $rowid) {
	$results = dbRowResults($table, $rowid);
	return dbFetchArray($results);
}

function dbRowByTitle($table, $title) {
	$query = "SELECT * FROM `$table` WHERE {$table}_title='$title'";
	$results = dbQuery($query);
	return dbFetchArray($results);
}

function dbRows($table, $sort='Title', $dir='ASC', $where='') {
	$sort = str_replace("%", $table . "_", $sort);
	$where = str_replace("%", $table . "_", $where);
	$query = "SELECT * FROM `$table` $where ORDER BY $sort $dir";
	$results = dbQuery($query);

	return $results;
}

function dbLimitRows($table, $sort='Title', $limit="", $dir='ASC', $where='') {
	$sort = str_replace("%", $table . "_", $sort);
	if($limit != "")
		$limit = "LIMIT $limit";
	$query = "SELECT * FROM `$table` $where ORDER BY $sort $dir $limit";
	$results = dbQuery($query);

	return $results;
}
function dbGetRecord($table, $query) {
	$query = str_replace("%", $table . "_", $query);
	return dbFetchArray(dbQuery($query));
}
function dbNumRecords($table, $where="") {
	if($where == ""){
		$result = dbQuery("SHOW TABLE STATUS LIKE '$table'");
//		$result = dbQuery("SELECT COUNT(*) FROM $table");
		$rec = dbFetchArray($result);
		return $rec["Rows"];
	}
	else{
		$where = str_replace("%", $table . "_", $where);
		$result = dbQuery("SELECT COUNT(*) FROM $table WHERE $where");
		$rec = dbFetchArray($result);
		return $rec[0];
	}
}

?>
