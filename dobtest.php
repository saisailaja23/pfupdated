<?php
require_once './inc/header.inc.php';
require_once BX_DIRECTORY_PATH_INC . 'design.inc.php';
require_once BX_DIRECTORY_PATH_INC . 'admin.inc.php';
require_once BX_DIRECTORY_PATH_INC . 'db.inc.php';

$columns = "ID,Age,DateOfBirth";
$sql = "SELECT ID,Age,DateOfBirth FROM Profiles where Age > 0";
$query = db_res($sql);
$cmdtuples = mysql_num_rows($query);
$arrColumns = explode(",", $columns);

while (($row = mysql_fetch_array($query, MYSQL_BOTH))) {
	$arrValues = array();
	foreach ($arrColumns as $column_name) {
		if ($column_name == 'Age') {
			if ($row[$column_name] < 120 && $row[$column_name] > 0) {
				$dobyear = date("Y") - $row[$column_name];

			}
			if ($row[$column_name] > 1800) {
				$dobyear = $row[$column_name];
			}
			$dob = $dobyear . "-01-01";
			$curId = $row['ID'];
			if ($row['DateOfBirth'] == "0000-00-00") {
				echo $sql1 = 'UPDATE Profiles SET DateOfBirth = "' . $dob . '" where ID = ' . $curId;
				mysql_query($sql1);
			}
		}

	}
}
?>