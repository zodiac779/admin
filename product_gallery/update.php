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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "product_gallery")) {

    $allowtype1 = array('bmp', 'gif', 'jpg', 'jpeg', 'gif', 'png');       // allowed file extension
    $max_size = 10000000;                                                 // max file size 10MB
    $savefolder = '../../images/products/gallery/';                       // folder for upload
    $checker1 = true;

    if(isset($_FILES["image1"]) and $_FILES["image1"]["name"]!= "")
    {
      $value1= $_FILES['image1']['name'];
      $value2 = explode(".", strtolower($value1));
      $type = end($value2);
      if (in_array($type, $allowtype1)){
            if ($_FILES['image1']['size'] <= $max_size){
              $filename1 = imageUpload('image1',$savefolder);
              if(!$filename1){
                $uploadMessage1 = "cannot be uploaded.";
                $filename1 = $_POST['existing_file_1'];
                $checker1 = false;
              }
            }else{
                $uploadMessage1 = "cannot be uploaded. (Your file is too large.)";
                $filename1 = $_POST['existing_file_1'];
                $checker1 = false;
            }
      }else{
        $uploadMessage1 = "cannot be uploaded. (Incorrect File Type.)";
        $filename1 = $_POST['existing_file_1'];
        $checker1 = false;
      }
    }else{
        $filename1 = $_POST['existing_file_1'];
    }

    if($checker1){

        $updateSQL = sprintf("UPDATE product_gallery SET image1 = %s, alt1 = %s, status = %s, lastupdate = now() WHERE id = %s",
                            GetSQLValueString($filename1, "text"),
                            GetSQLValueString($_POST['alt1'], "text"),
                            GetSQLValueString(isset($_POST['status']) ? "true" : "", "defined","1","0"),
                            GetSQLValueString($_POST['id'], "int"));

        mysql_select_db($database_conn_database, $conn_database);
        $Result1 = mysql_query($updateSQL, $conn_database) or die(mysql_error());

        mysql_data_seek($rs_lang, 0);
        while ($row_rs_lang = mysql_fetch_assoc($rs_lang)){
            $updateSQL = sprintf("UPDATE product_gallery_localization SET title1 = %s WHERE product_gallery_id = %s AND lang_id = %s",
                            GetSQLValueString($_POST['title1_'.strtolower($row_rs_lang['lang_abbr'])],'text'),
                            GetSQLValueString($_POST['id'], "int"),
                            GetSQLValueString($row_rs_lang['id'], "int"));

            mysql_select_db($database_conn_database, $conn_database);
            $Result1 = mysql_query($updateSQL, $conn_database) or die(mysql_error());
        }

    }
}

$colname_rs_update = "-1";
if (isset($_GET['id'])) {
  $colname_rs_update = $_GET['id'];
}
mysql_select_db($database_conn_database, $conn_database);
$query_rs_update = sprintf("SELECT * FROM product_gallery WHERE id = %s", GetSQLValueString($colname_rs_update, "int"));
$rs_update = mysql_query($query_rs_update, $conn_database) or die(mysql_error());
$row_rs_update = mysql_fetch_assoc($rs_update);
$totalRows_rs_update = mysql_num_rows($rs_update);

$colname_rs_product = "-1";
if (isset($_GET['product_id'])) {
  $colname_rs_product = $_GET['product_id'];
}
mysql_select_db($database_conn_database, $conn_database);
$query_rs_product = sprintf("SELECT * FROM product_localization INNER JOIN languages ON languages.id = product_localization.lang_id WHERE product_id  = %d ORDER BY languages.rank DESC LIMIT 1", GetSQLValueString($colname_rs_product, "int"));
$rs_product = mysql_query($query_rs_product, $conn_database) or die(mysql_error());
$row_rs_product = mysql_fetch_assoc($rs_product);
$totalRows_rs_product = mysql_num_rows($rs_product);


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
                                <span class="caption-subject uppercase"><?php echo $row_rs_product['title1']; ?>'s Gallery Update</span>
                            </div>
                            <div class="actions">
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <div class="form-body" style="padding-bottom: 0px;">
                                <form action="<?php echo $editFormAction; ?>" name="product_gallery" id="product_gallery" method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="light">
                                                <div class="portlet-body">
                                                    <?php if(isset($Result1)){ ?>
                                                        <div class="alert alert-block alert-success fade in">
                                                            <button type="button" class="close" data-dismiss="alert"></button>
                                                            <h4 class="alert-heading" style="margin-bottom:0px">Your product gallery has been updated.</h4>
                                                        </div>
                                                    <?php } ?>

                                                    <?php if(isset($uploadMessage1)){?>
                                                        <div class="alert alert-block alert-danger fade in">
                                                            <button type="button" class="close" data-dismiss="alert"></button>
                                                            <h4 class="alert-heading" style="margin-bottom:0px"><?php echo 'Product Gallery Image'.$uploadMessage1; ?></h4>
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
                                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                        <label>Product Gallery Image</label>&nbsp;&nbsp;<span style="font-size:11px; color:#0070c0;">Size : 600 x 450 px</span>
                                                                        <div class="input-group ">
                                                                            <div class="form-control uneditable-input span3" data-trigger="fileinput">
                                                                                <i class="fa fa-image"></i>&nbsp; <span class="fileinput-filename"><?php echo $row_rs_update['image1']; ?></span>
                                                                            </div>
                                                                            <span class="input-group-addon btn default btn-file">
                                                                            <span class="fileinput-new">Select file </span>
                                                                            <span class="fileinput-exists">Change </span>
                                                                            <input type="file" name="image1" id="image1" onchange="imageSelectHandler(this);" data-width="600" data-height="450">
                                                                            </span>
                                                                            <a href="#" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Remove </a><?php if($row_rs_update['image1'] != ""){ ?><a href="../../images/products/gallery/<?php echo $row_rs_update['image1']; ?>" class="input-group-addon btn blue fancybox-button" target="_blank"><i class="icon-picture" style="color:#FFF;"></i></a><?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Alternative Text Product Gallery Image</label>
                                                                        <i style="padding-left: 5px;" class="fa fa-question-circle tooltips" data-original-title="The Alternative Text attribute is used in HTML and XHTML documents to specify alternative text (alt text) that is to be rendered when the element to which it is applied cannot be rendered."></i>
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon"><i class="fa fa-font"></i></span>
                                                                            <input type="text" id="alt1" name="alt1" class="form-control" value="<?php echo $row_rs_update['alt1']; ?>"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label>Status</label>
                                                                        <div class="bootstrap-switch-container">
                                                                        <input type="checkbox" name="status" id="status" <?php if (!(strcmp(1, $row_rs_update['status']))) {echo "checked";} ?> class="make-switch" data-size="normal" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>">
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
                                                        $query_rs_product_gallery = sprintf("SELECT * FROM product_gallery_localization pl WHERE pl.lang_id = %d AND pl.product_gallery_id = %d", GetSQLValueString($row_rs_lang['id'],'int'), GetSQLValueString($row_rs_update['id'],'int'));
                                                        $rs_product_gallery = mysql_query($query_rs_product_gallery, $conn_database) or die(mysql_error());
                                                        $row_rs_product_gallery = mysql_fetch_assoc($rs_product_gallery);
                                                        $totalRows_rs_product_gallery = mysql_num_rows($rs_product_gallery);
                                                        if($totalRows_rs_product_gallery==0){
                                                          mysql_select_db($database_conn_database, $conn_database);
                                                          $query_add_product_gallery = sprintf("INSERT INTO product_gallery_localization(lang_id, product_gallery_id) VALUES (%d, %d)",
                                                          GetSQLValueString($row_rs_lang['id'],'int'),GetSQLValueString($row_rs_update['id'],'int'));
                                                          mysql_query($query_add_product_gallery, $conn_database) or die(mysql_error());
                                                        }
                                                        $counter++;
                                                        } while ($totalRows_rs_product_gallery==0 and $counter <= 1) ?>

                                                        <div class="tab-pane fade <?php if(isset($_POST["current_tab"]) && $_POST["current_tab"] == "tab_1_".$row_rs_lang['lang_abbr']){echo 'active in';}?>" id="tab_1_<?php echo $row_rs_lang['lang_abbr']; ?>">

                                                            <div class="form-group">
                                                                <label>Product Gallery Title</label>
                                                                <input type="text" name="title1_<?php echo strtolower($row_rs_lang['lang_abbr']); ?>" id="title1_<?php echo strtolower($row_rs_lang['lang_abbr']); ?>" class="form-control" placeholder="" value="<?php echo $row_rs_product_gallery['title1']; ?>"> 
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
                                      <a href="index.php?id=<?php echo $row_rs_update['product_id']; ?>" class="btn dark btn-outline" >Back</a>
                                      <button type="submit" name="Insert" class="btn blue btn-outline">Update</button>
                                      <input name="id" type="hidden" value="<?php echo $row_rs_update['id']; ?>">
                                          <input name="existing_file_1" id="existing_file_1" type="hidden" value="<?php echo $row_rs_update['image1']; ?>">
                                      <input name="current_tab" id="current_tab" type="hidden" value="<?php if(isset($_POST["current_tab"])){ echo $_POST["current_tab"];}else{ echo 'tab_1_1';} ?>">
                                    </div>
                                    <input type="hidden" name="MM_update" value="product_gallery">
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