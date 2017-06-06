<?php
	require_once('../Connections/conn_database.php');
	if(!isset($database_conn_database)AND !isset($conn_database)){
		echo 'error DB Connect';
		exit();
	}
	if (!function_exists('CheckInSlug')) {
		function CheckInSlug($tableName,$dataValue,$cID,$data_conn,$conn_db){
			if(empty($dataValue)){
				echo 'CanNotUse';
				exit();
			}
			mysql_select_db($data_conn, $conn_db);
			$query_rs_check = "SELECT count(meta_slug) hasData FROM `".$tableName."` WHERE `meta_slug` = '".$dataValue."' AND id != '".$cID."'";
			$rs_check = mysqli_query(conn_database(),$query_rs_check, $conn_db) or die('query error');
			$row_rs_check = mysqli_fetch_assoc($rs_check);
			$totalRows_rs_check = mysqli_num_rows($rs_check);
			if($totalRows_rs_check>0){
				if($row_rs_check['hasData']==0){
					echo 'CanUse';
					exit();
				}else{
					echo 'CanNotUse';
					exit();
				}
			}
				echo 'error';
				exit();
		}
	}
	if(isset($_POST['tableName'])AND isset($_POST['dataValue']) AND isset($_POST['cID'])){
		CheckInSlug($_POST['tableName'],$_POST['dataValue'],$_POST['cID'],$database_conn_database,$conn_database);
	}
 ?>