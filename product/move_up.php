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

  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string(conn_database(),$theValue) : mysqli_escape_string($theValue);

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


$insertGoTo = "index.php?id=".$_GET["cate_id"]."&status=error";
if ((isset($_GET["id"])) && ($_GET["id"] != "") && (isset($_GET["rank"]))) {


  $query = sprintf("SELECT id, rank FROM product WHERE rank > %s ORDER BY rank ASC LIMIT 1",
            GetSQLValueString($_GET["rank"], "int"),
            GetSQLValueString($_GET["cate_id"], "int"));
    $rs = mysqli_query(conn_database(),$query) or die(mysql_error());
    $rows = mysqli_fetch_assoc($rs);

  $updateSQL = sprintf("UPDATE product SET rank=%s WHERE id=%s",
                       GetSQLValueString($rows['rank'], "int"),
                       GetSQLValueString($_GET['id'], "int"));

  
  $Result1 = mysqli_query(conn_database(),$updateSQL) or die(mysql_error());

  $updateSQL = sprintf("UPDATE product SET rank=%s WHERE id=%s",
                       GetSQLValueString($_GET['rank'], "int"),
                       GetSQLValueString($rows['id'], "int"));

  
  $Result1 = mysqli_query(conn_database(),$updateSQL) or die(mysql_error());


$insertGoTo = "index.php?id=".$_GET["cate_id"]."&status=success";
}

  header(sprintf("Location: %s", $insertGoTo));

?>