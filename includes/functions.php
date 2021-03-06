<?php

ob_start(); 
if (!isset($_SESSION)) { session_start(); }
date_default_timezone_set('Asia/Bangkok');
if (!function_exists("GetSQLValueString")) {
	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
	{
		if (PHP_VERSION < 6) {
			$theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
		}
		$theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string(conn_database(),$theValue) : mysqli_escape_string($theValue);
		switch ($theType) {
			case "text":
				$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
				break;
			case "char":
				$theValue = ($theValue != "") ? $theValue : "NULL";
				break;    
			case "long":
			case "int":
				$theValue = ($theValue != "") ? intval($theValue) : "NULL";
				break;
			case "double":
				$theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
				break;
			case "date":
				$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
				break;
			case "defined":
				$theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
				break;
		}
		return $theValue;
	}
}

//Check if member is logged in
function isLoggedIn(){
	if(isset($_SESSION["mcf_membervalid"]) && $_SESSION["mcf_membervalid"]){
		return true;
	}else{
		return false;
	}
}

//Update profile
function updateProfile($fname,$lname,$userid)
{ 
	$query = sprintf("UPDATE administrator SET adminFirstname = %s, adminLastname = %s WHERE adminId = %s", 
						GetSQLValueString($fname, "text"),
						GetSQLValueString($lname, "text"),
						GetSQLValueString($userid, "int"));
	//$result = mysqli_query(conn_database(),$query);
	$_SESSION['mcf_memberfname'] = $fname;
	if(mysqli_query(conn_database(),$query)){
			$result = "success";
		}else{
			$result = "fail";
		}
	
	return $result;
}

//Update password
function updatePassword($oldpassword,$newpassword,$userid)
{ 
	$query = sprintf("SELECT adminId FROM administrator WHERE adminId=%s AND adminPassword=%s", 
						GetSQLValueString($userid, "int"),
						GetSQLValueString(md5($oldpassword), "text"));
	$result = mysqli_query(conn_database(),$query);
	$userData = mysqli_fetch_array($result, MYSQL_ASSOC);
	if(mysqli_num_rows($result) < 1){
		$result = "fail";
	}else{
		$query = sprintf("UPDATE administrator SET adminPassword = %s WHERE adminId = %s AND adminPassword = %s", 
						GetSQLValueString(md5($newpassword), "text"),
						GetSQLValueString($userid, "int"),
						GetSQLValueString(md5($oldpassword), "text"));
		mysqli_query(conn_database(),$query);
		$result = "success";
	}
	return $result;
}

//Log out
function logout(){
	$_SESSION = array(); //destroy all of the session variables
    session_destroy();
	header('Location: index.php');
}

?>