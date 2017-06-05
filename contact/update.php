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


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "contact")) {

  $updateSQL = sprintf("UPDATE contact SET  name=%s, email=%s, contact=%s, subject=%s, message=%s, lastupdate=now() WHERE id=%s",
                         GetSQLValueString($_POST['name'], "text"),
                         GetSQLValueString($_POST['email'], "text"),
                         GetSQLValueString($_POST['phone'], "text"),
                         GetSQLValueString($_POST['subject'], "text"),
                         GetSQLValueString($_POST['message'], "text"),
                         GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_conn_database, $conn_database);
  $Result1 = mysql_query($updateSQL, $conn_database) or die(mysql_error());

   
}


$colname_rs_update = "-1";
if (isset($_GET['id'])) {
  $colname_rs_update = $_GET['id'];
}
mysql_select_db($database_conn_database, $conn_database);
$query_rs_update = sprintf("SELECT * FROM contact WHERE id = %s", GetSQLValueString($colname_rs_update, "int"));
$rs_update = mysql_query($query_rs_update, $conn_database) or die(mysql_error());
$row_rs_update = mysql_fetch_assoc($rs_update);
$totalRows_rs_update = mysql_num_rows($rs_update);
?>
<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.6
Version: 4.5.4
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title>WOW Project | Administrator</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
        <link href="../assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="../assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="../assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="../assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css"/>
        <link href="../assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css"/>
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="../assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="../assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link rel="icon" href="../../images/favicon.png" type="image/png" />
 </head>
    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
        <!-- BEGIN HEADER -->
                    <?php include('../includes/header.php'); ?>
        <!-- END HEADER -->
        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"> </div>
        <!-- END HEADER & CONTENT DIVIDER -->
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN SIDEBAR -->
            <div class="page-sidebar-wrapper">
                <!-- BEGIN SIDEBAR -->
                <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                <div class="page-sidebar navbar-collapse collapse">
                    <!-- BEGIN SIDEBAR MENU -->
                    <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                    <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
                    <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
                    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                    <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
                    <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                    <?php include('../includes/left.php'); ?>
                    <!-- END SIDEBAR MENU -->
                    <!-- END SIDEBAR MENU -->
                </div>
                <!-- END SIDEBAR -->
            </div>
            <!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                    <!-- BEGIN THEME PANEL -->
                    
                    <!-- END THEME PANEL -->
                    <!-- BEGIN PAGE BAR --><!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                       
                                        <span class="caption-subject  uppercase">Contact Form</span>
                                    </div>
                                    <div class="actions">
                                        
                                    </div>
                                </div>
                                <div class="portlet-body form">
                                    
                                        <div class="form-body" style="padding-bottom: 0px;">
                                        
                                        <form action="<?php echo $editFormAction; ?>" name="banner" id="banner" method="POST" enctype="multipart/form-data">
                                            


                                <?php if(isset($Result1)){?>
                                                <div class="alert alert-block alert-success fade in" style="margin-top: 8px;">
                                <button type="button" class="close" data-dismiss="alert"></button>
                                <h4 class="alert-heading" style="margin-bottom:0px">Your contact has been updated.</h4>
                                              </div>
                                <?php }?>

                                           <div class="row">
                                              <div class="col-md-12">
                                                <div class="light">
                                <div class="portlet-body">
                                    <ul class="nav nav-tabs">
                                        <li class="<?php if(!isset($_POST["current_tab"]) || $_POST["current_tab"] == "tab_1_1"){echo 'active';}?>">
                                            <a href="#tab_1_1" onclick="setDefaultTab('tab_1_1');" data-toggle="tab" aria-expanded="true"> Main</a>
                                        </li>
                                    </ul>

                                    <div class="tab-content">

                                        <div class="tab-pane fade  <?php if(!isset($_POST["current_tab"]) || $_POST["current_tab"] == "tab_1_1"  ){echo 'active in';}?>" id="tab_1_1">
                                            
                                            <div class="row">
                                              <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><B>Name</B></label>
                                                    <input type="text" name="name" value="<?php echo $row_rs_update['name']; ?>" readonly class="form-control" placeholder=""> 
                                                </div>
                                              </div>

                                              <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><B>E-mail</B></label>
                                                    <input type="text" name="email" value="<?php echo $row_rs_update['email']; ?>" readonly class="form-control" placeholder=""> 
                                                </div>
                                              </div>
                                            </div>

                                            <div class="row">
                                              <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><B>Telephone</B></label>
                                                    <input type="text" name="phone" value="<?php echo $row_rs_update['phone']; ?>" readonly class="form-control" placeholder=""> 
                                                </div>
                                              </div>
                                              
                                              <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><B>Subject</B></label>
                                                    <input type="text" name="subject" value="<?php echo $row_rs_update['subject']; ?>" readonly class="form-control" placeholder=""> 
                                                </div>
                                              </div>
                                            </div>

                                            <div class="form-group">
                                                <label><B>Message</B></label>
                                                <textarea class="form-control ckeditor" name="message" id="message" cols="45" rows="5"><?php echo $row_rs_update['message']; ?></textarea>
                                            </div>

                                        </div>
                                        
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix margin-bottom-20"> </div>
                            </div>

                                              </div>


                                        
                                        <div class="form-actions right">
                                          <a href="index.php" class="btn  btn-outline dark">Back</a>
                                          <input name="id" type="hidden" value="<?php echo $row_rs_update['id']; ?>">
                                          <input name="existing_file_5" id="existing_file_5" type="hidden" value="<?php echo $row_rs_update['application_form']; ?>">
                                          <input name="current_tab" id="current_tab" type="hidden" value="<?php if(isset($_POST["current_tab"])){ echo $_POST["current_tab"];}else{ echo 'tab_1_1';}?>">
                                        </div>
                                      
                                        <input type="hidden" name="MM_update" value="contact">
                                        </form>
                               <script>
                              function setDefaultTab(id)
                              {
                                $("#current_tab").val(id);

                              }
                              function deleteIMG(id)
                              {
                                $(".delete_image_"+id).hide();
                                $("#existing_file_"+id).val('');
                              }
                              </script> 
                                </div>
                            </div>
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
            <!-- BEGIN QUICK SIDEBAR -->
            
            <!-- END QUICK SIDEBAR -->
        </div>
        <!-- END CONTAINER -->
        <!-- BEGIN FOOTER -->
        <?php include('../includes/footer.php'); ?>
        <!-- END FOOTER -->
        <!--[if lt IE 9]>
<script src="../assets/global/plugins/respond.min.js"></script>
<script src="../assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="../assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="../assets/global/scripts/app.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="../assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js"></script>
        <script type="text/javascript" src="../assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script>
        <script src="../ckeditor/ckeditor.js"></script>
        <script src="../ckfinder/ckfinder.js"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="../assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
        <script src="../assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
        <script src="../assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>

</html>
<?php
mysql_free_result($rs_update);
?>
