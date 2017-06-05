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


$insertGoTo = "index.php?status=error";
if ((isset($_GET["id"])) && ($_GET["id"] != "") && (isset($_GET["rank"])) && ($_GET["rank"] != "")) {

  mysql_select_db($database_conn_database, $conn_database);
  $query = sprintf("SELECT id, rank FROM about_gallery WHERE rank < %s ORDER BY rank DESC LIMIT 1",
            GetSQLValueString($_GET["rank"], "int"));
    $rs = mysql_query($query) or die(mysql_error());
    $rows = mysql_fetch_assoc($rs);

  $updateSQL = sprintf("UPDATE about_gallery SET rank=%s WHERE id=%s",
                       GetSQLValueString($rows['rank'], "int"),
                       GetSQLValueString($_GET['id'], "int"));

  mysql_select_db($database_conn_database, $conn_database);
  $Result1 = mysql_query($updateSQL, $conn_database) or die(mysql_error());

  $updateSQL = sprintf("UPDATE about_gallery SET rank=%s WHERE id=%s",
                       GetSQLValueString($_GET['rank'], "int"),
                       GetSQLValueString($rows['id'], "int"));

  mysql_select_db($database_conn_database, $conn_database);
  $Result1 = mysql_query($updateSQL, $conn_database) or die(mysql_error());


$insertGoTo = "index.php?status=success";
}


  header(sprintf("Location: %s", $insertGoTo));

?>