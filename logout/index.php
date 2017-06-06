<?php
// *** Logout the current user.
$logoutGoTo = "../login/";
if (!isset($_SESSION)) {
  session_start();
}
$_SESSION['mcf_membervalid'] = NULL;
unset($_SESSION['mcf_membervalid']);
if ($logoutGoTo != "") {header("Location: $logoutGoTo");
exit;
}
?>
