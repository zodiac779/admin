<?php 
require_once("../includes/restriction.php");
ob_start(); 
if (!isset($_SESSION)) { session_start(); }
?>
<?php require_once('../../Connections/conn_database.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
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



if (isset($_POST["dataId"]) && $_POST["dataId"] != "" && isset($_POST["dataRank"]) && $_POST["dataRank"] != "") {

  $dataId = $_POST["dataId"];
  $dataRank = $_POST["dataRank"];
  $loopSize = count($dataId);
  for($i=0;$i<$loopSize;$i++){
    $updateSQL = sprintf("UPDATE product_gallery SET rank=%s WHERE id=%s",
                         GetSQLValueString($dataRank[$i], "int"),
                         GetSQLValueString($dataId[$i], "int"));

     mysql_select_db($database_conn_database, $conn_database);
     $Result1 = mysql_query($updateSQL, $conn_database) or die(mysql_error());
  }


  


}

 

?>