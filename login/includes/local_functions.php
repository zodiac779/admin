<?php

//Validate user
function validateUser($userid)
{
    session_regenerate_id (); //this is a security measure
    $_SESSION['mcf_membervalid'] = 1;
    $_SESSION['mcf_memberid'] = $userid;
	$query = sprintf("UPDATE administrator SET lastLogin=NOW() WHERE adminId = %s", 
						GetSQLValueString($userid, "int"));
	$result = mysql_query($query);
}

//Log in
function login($username,$password){
	$query = sprintf("SELECT adminId, adminFirstname, adminType FROM administrator WHERE adminUsername=%s AND adminPassword=%s", 
						GetSQLValueString($username, "text"),
						GetSQLValueString(md5($password), "text"));
	$result = mysql_query($query);
	$userData = mysql_fetch_array($result, MYSQL_ASSOC);
	if(mysql_num_rows($result) < 1){
		header('Location: index.php?error=invalid');
	}else{
		validateUser($userData['adminId']);
		$_SESSION['mcf_memberfname'] = $userData['adminFirstname'];
		$_SESSION['mcf_admintype'] = $userData['adminType'];
		$_SESSION['mcf_memberlogin'] = date('H:i', time());
		header('Location: ../dashboard');
	}
}
?>