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

mysql_select_db($database_conn_database, $conn_database);
$query_rs_lang = "SELECT * FROM languages WHERE status = 1 ORDER BY rank DESC";
$rs_lang = mysql_query($query_rs_lang, $conn_database) or die(mysql_error());
$row_rs_lang = mysql_fetch_assoc($rs_lang);
$totalRows_rs_lang = mysql_num_rows($rs_lang);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "content")) {

    $updateSQL = sprintf("UPDATE content SET lastupdate = now() WHERE id = %s", GetSQLValueString($_POST['id'], "int"));
    mysql_select_db($database_conn_database, $conn_database);
    $Result1 = mysql_query($updateSQL, $conn_database) or die(mysql_error());

    $updateSQL = sprintf("UPDATE setting SET website = %s, copy_right = %s, lastupdate = now() WHERE id = %s",
                        GetSQLValueString($_POST['website'], "text"),
                        GetSQLValueString($_POST['copy_right'], "text"),
                        GetSQLValueString(1, "int"));
    mysql_select_db($database_conn_database, $conn_database);
    $Result1 = mysql_query($updateSQL, $conn_database) or die(mysql_error());

    mysql_data_seek($rs_lang, 0);
    while ($row_rs_lang = mysql_fetch_assoc($rs_lang)){
        $updateSQL = sprintf("UPDATE content_localization SET title1 = %s, title2 = %s, title3 = %s, title4 = %s WHERE content_id = %d AND lang_id = %d",
                        GetSQLValueString($_POST['title1_'.strtolower($row_rs_lang['lang_abbr'])],'text'),
                        GetSQLValueString($_POST['title2_'.strtolower($row_rs_lang['lang_abbr'])],'text'),
                        GetSQLValueString($_POST['title3_'.strtolower($row_rs_lang['lang_abbr'])],'text'),
                        GetSQLValueString($_POST['title4_'.strtolower($row_rs_lang['lang_abbr'])],'text'),
                        GetSQLValueString($_POST['id'], "int"),
                        GetSQLValueString($row_rs_lang['id'], "int"));

        mysql_select_db($database_conn_database, $conn_database);
        $Result1 = mysql_query($updateSQL, $conn_database) or die(mysql_error());
    }
}

mysql_select_db($database_conn_database, $conn_database);
$query_rs_update = "SELECT * FROM content WHERE id = 9";
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
        <title>WOW III : Administrator</title>
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
        <link href="../assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css" rel="stylesheet" type="text/css" />
        <link href="../assets/global/plugins/jquery-minicolors/jquery.minicolors.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="../assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="../assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="../assets/global/plugins/jcrop/css/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="../assets/pages/css/image-crop.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <link href="../assets/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet" type="text/css" />
        <link href="../assets/global/plugins/typeahead/typeahead.css" rel="stylesheet" type="text/css" />
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
                                <span class="caption-subject  uppercase">Content Footer Update</span>
                            </div>
                            <div class="actions">
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <div class="form-body" style="padding-bottom: 0px;">
                                <form action="<?php echo $editFormAction; ?>" name="content" id="content" method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="light">
                                                <div class="portlet-body">
                                                    <?php if(isset($Result1)){ ?>
                                                        <div class="alert alert-block alert-success fade in">
                                                            <button type="button" class="close" data-dismiss="alert"></button>
                                                            <h4 class="alert-heading" style="margin-bottom:0px">Your content has been updated.</h4>
                                                        </div>
                                                    <?php } ?>                                                   

                                                    <ul class="nav nav-tabs">
                                                        <li class="<?php if(!isset($_POST["current_tab"]) || $_POST["current_tab"] == "tab_1_1"){echo 'active';}?>">
                                                            <a onclick="setDefaultTab('tab_1_1');" href="#tab_1_1" data-toggle="tab" aria-expanded="true"> Main</a>
                                                        </li>
                                                        <?php
                                                        mysql_data_seek($rs_lang, 0);
                                                        while ($row_rs_lang = mysql_fetch_assoc($rs_lang)){ ?>
                                                        <li class="<?php if(isset($_POST["current_tab"]) && $_POST["current_tab"] == "tab_1_".$row_rs_lang['lang_abbr']){echo 'active';}?>">
                                                            <a onclick="setDefaultTab('tab_1_<?php echo $row_rs_lang['lang_abbr']; ?>');" href="#tab_1_<?php echo $row_rs_lang['lang_abbr']; ?>" data-toggle="tab" aria-expanded="true"> <?php echo $row_rs_lang['lang_name']; ?></a>
                                                        </li>
                                                        <?php } ?>
                                                    </ul>
                                                    <div class="tab-content">

                                                        <div class="tab-pane fade <?php if(!isset($_POST["current_tab"]) || $_POST["current_tab"] == "tab_1_1"){echo 'active in';}?>" id="tab_1_1">

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Link Facebook Fanpage</label> <span style="font-size:11px; color:#F00;">Facebook Fanpage : [LINK CODE]</span>
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon"><i class="fa fa-facebook"></i></span>
                                                                            <input type="text" name="website" id="website" class="form-control" placeholder="" value="<?php echo $row_rs_setting['website']; ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Copyrights</label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon"><i class="fa fa-text-width"></i></span>
                                                                            <input type="text" id="copy_right" name="copy_right" class="form-control" value="<?php echo $row_rs_setting['copy_right']; ?>"/> 
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                         </div>

                                                        <?php mysql_data_seek($rs_lang, 0);
                                                        while ($row_rs_lang = mysql_fetch_assoc($rs_lang)) { 
                                                        $counter=0;
                                                        do {
                                                        mysql_select_db($database_conn_database, $conn_database);
                                                        $query_rs_content = sprintf("SELECT * FROM content_localization ctl WHERE ctl.lang_id = %d AND ctl.content_id = %d", GetSQLValueString($row_rs_lang['id'],'int'), GetSQLValueString($row_rs_update['id'],'int'));
                                                        $rs_content = mysql_query($query_rs_content, $conn_database) or die(mysql_error());
                                                        $row_rs_content = mysql_fetch_assoc($rs_content);
                                                        $totalRows_rs_content = mysql_num_rows($rs_content);
                                                        if($totalRows_rs_content==0){
                                                          mysql_select_db($database_conn_database, $conn_database);
                                                          $query_add_content = sprintf("INSERT INTO content_localization(lang_id, content_id) VALUES (%d,%d)",
                                                          GetSQLValueString($row_rs_lang['id'],'int'),GetSQLValueString($row_rs_update['id'],'int'));
                                                          mysql_query($query_add_content, $conn_database) or die(mysql_error());
                                                        }
                                                        $counter++;
                                                        } while ($totalRows_rs_content==0 and $counter <= 1) ?>

                                                        <div class="tab-pane fade <?php if(isset($_POST["current_tab"]) && $_POST["current_tab"] == "tab_1_".$row_rs_lang['lang_abbr']){echo 'active in';}?>" id="tab_1_<?php echo $row_rs_lang['lang_abbr']; ?>">

                                                            <div class="form-group">
                                                                <label>Site Links Title</label>
                                                                <input type="text" name="title1_<?php echo strtolower($row_rs_lang['lang_abbr']); ?>" id="title1_<?php echo strtolower($row_rs_lang['lang_abbr']); ?>" class="form-control" placeholder="" value="<?php echo $row_rs_content['title1']; ?>"> 
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Subscribe Title</label>
                                                                <input type="text" name="title2_<?php echo strtolower($row_rs_lang['lang_abbr']); ?>" id="title2_<?php echo strtolower($row_rs_lang['lang_abbr']); ?>" class="form-control" placeholder="" value="<?php echo $row_rs_content['title2']; ?>"> 
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Subscribe Button Title</label>
                                                                <input type="text" name="title3_<?php echo strtolower($row_rs_lang['lang_abbr']); ?>" id="title3_<?php echo strtolower($row_rs_lang['lang_abbr']); ?>" class="form-control" placeholder="" value="<?php echo $row_rs_content['title3']; ?>"> 
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Facebook Fanpage Title</label>
                                                                <input type="text" name="title4_<?php echo strtolower($row_rs_lang['lang_abbr']); ?>" id="title4_<?php echo strtolower($row_rs_lang['lang_abbr']); ?>" class="form-control" placeholder="" value="<?php echo $row_rs_content['title4']; ?>"> 
                                                            </div>

                                                        </div>

                                                        <?php } ?>

                                                        <div class="clearfix margin-bottom-20"> </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                              
                                    <div class="form-actions right">
                                      <i class="fa fa-clock-o"></i> <?php echo date("d/m/Y H:i:s",strtotime($row_rs_update['lastupdate'])); ?>&nbsp;
                                      <button type="submit" name="Insert" class="btn blue btn-outline">Update</button>
                                      <input name="id" type="hidden" value="<?php echo $row_rs_update['id']; ?>">
                                      <input name="current_tab" id="current_tab" type="hidden" value="<?php if(isset($_POST["current_tab"])){ echo $_POST["current_tab"];}else{ echo 'tab_1_1';} ?>">
                                    </div>
                                    <input type="hidden" name="MM_update" value="content">
                                </form>
                            <script>
                            function setDefaultTab(id)
                            {
                              $("#current_tab").val(id);

                            }
                            </script> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
        <script src="../assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/jquery-minicolors/jquery.minicolors.min.js" type="text/javascript"></script>
        <script src="../assets/pages/scripts/components-color-pickers.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="../assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
        <script src="../assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
        <script src="../assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/typeahead/handlebars.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/typeahead/typeahead.bundle.min.js" type="text/javascript"></script>
        <script src="../assets/pages/scripts/components-bootstrap-tagsinput.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="../assets/global/plugins/jcrop/js/jquery.Jcrop.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="../assets/pages/scripts/form-image-crop.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
    </body>

</html>
<?php
mysql_free_result($rs_update);
?>