 <?php
/*********************************************************************************
* Name:    Prashanth A
* Date:    02/11/2013
* Purpose: Gettting the family information
*********************************************************************************/
require_once ('../../inc/header.inc.php');
require_once ('../../inc/profiles.inc.php');

$logid = getLoggedId();
$cmdtuples = 1;

$stringSQL_Children = "SELECT config_value, config_description FROM sys_configuration WHERE config_key = 'noofchildren' ORDER BY config_order ASC ";
$query_children     = mysql_query($stringSQL_Children);

$arrColumns = explode(",", 'config_description');
$arrRows_children = array();
while (($row_children = mysql_fetch_array($query_children, MYSQL_BOTH))) {
	$arr_child_Values = array();
	foreach ($arrColumns as $column_name) {
		array_push($arr_child_Values, $row_children[$column_name]);
	}

	array_push($arrRows_children, array(
			'id' => $row_children[0],
			'data' => $arr_child_Values,
		));
}
if ($cmdtuples > 0)
{
echo json_encode(array(
'status' => 'success',
'Profiles_value' => array(
'rows' => $logid
),
'children_value' =>$arrRows_children
));
}
else
{
echo json_encode(array(
'status' => 'err',
'response' => 'Could not read the data: ' . mssql_get_last_message()
));
}

?>
