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

$colname_rs_insert = "-1";
if (isset($_GET['id'])) {
  $colname_rs_insert = $_GET['id'];
}
mysql_select_db($database_conn_database, $conn_database);
$query_rs_insert = sprintf("SELECT * FROM collection WHERE id = %s", GetSQLValueString($colname_rs_insert, "int"));
$rs_insert = mysql_query($query_rs_insert, $conn_database) or die(mysql_error());
$row_rs_insert = mysql_fetch_assoc($rs_insert);
$totalRows_rs_insert = mysql_num_rows($rs_insert);

mysql_select_db($database_conn_database, $conn_database);
$query_rs_collection = "SELECT p.id, (SELECT pl.title1 FROM collection_localization pl WHERE pl.collection_id = p.id AND pl.lang_id = 1) AS title FROM collection p ORDER BY p.rank DESC";
$rs_collection = mysql_query($query_rs_collection, $conn_database) or die(mysql_error());
$row_rs_collection = mysql_fetch_assoc($rs_collection);
$totalRows_rs_collection = mysql_num_rows($rs_collection);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "collection")) {

  $allowtype = array('bmp', 'gif', 'jpg', 'jpeg', 'gif', 'png');      // allowed file extension
  $max_size = 10000000;                                               // max file size 10MB
  $savefolder = '../../assets/images/collection/';                 // folder for upload
  $checker1 = true;

  if(isset($_FILES["image1"]) and $_FILES["image1"]["name"]!= ""){
      $value1= $_FILES['image1']['name'];
      $value2 = explode(".", strtolower($value1));
      $type = end($value2);
      if (in_array($type, $allowtype)){
            if ($_FILES['image1']['size'] <= $max_size){
              $filename1 = imageUpload('image1', $savefolder);
              if(!$filename1){
                  $uploadMessage1 = "cannot be uploaded.";
                  $checker1 = false;
              }
            }else{
                $uploadMessage1 = "cannot be uploaded. (Your file is too large.)";
                $checker1 = false;
            }
      }else{
          $uploadMessage1 = "cannot be uploaded. (Incorrect File Type.)";
          $checker1 = false;
      }
   }else{
      $uploadMessage1 = 'is required.';
      $checker1 = false;
   }

  if($checker1){

  mysql_select_db($database_conn_database, $conn_database);
  $query_rs_index = "SELECT rank FROM collection ORDER BY rank DESC LIMIT 1";
  $rs_index = mysql_query($query_rs_index, $conn_database) or die(mysql_error());
  $row_rs_index = mysql_fetch_assoc($rs_index);

  $newrank = $row_rs_index['rank']+1;

  $insertSQL = sprintf("INSERT INTO collection (meta_title, meta_keywords, meta_description, meta_slug, image1, alt1, status, rank) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['meta_title'], "text"),
                       GetSQLValueString($_POST['meta_keywords'], "text"),
                       GetSQLValueString($_POST['meta_description'], "text"),
                       GetSQLValueString($_POST['meta_slug'], "text"),    
                       GetSQLValueString($filename1, "text"),
                       GetSQLValueString($_POST['alt1'], "text"),
                       GetSQLValueString(isset($_POST['status']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($newrank , "int"));
  mysql_select_db($database_conn_database, $conn_database);
  $Result1 = mysql_query($insertSQL, $conn_database) or die(mysql_error());
  $insert_id = mysql_insert_id();

  mysql_data_seek($rs_lang, 0);
  while ($row_rs_lang = mysql_fetch_assoc($rs_lang)){
  $insertSQL = sprintf("INSERT INTO collection_localization (title1, title2, detail1, collection_id, lang_id) VALUES(%s, %s, %s, %d, %d) ",
              GetSQLValueString($_POST['title1_'.strtolower($row_rs_lang['lang_abbr'])],'text'),
              GetSQLValueString($_POST['title2_'.strtolower($row_rs_lang['lang_abbr'])],'text'),
              GetSQLValueString($_POST['detail1_'.strtolower($row_rs_lang['lang_abbr'])],'text'),
              GetSQLValueString($insert_id, "int"),
              GetSQLValueString($row_rs_lang['id'], "int"));

  mysql_select_db($database_conn_database, $conn_database);
  $Result1 = mysql_query($insertSQL, $conn_database) or die(mysql_error());
  }  

      if(isset($_POST['insert_more']))
        $insertGoTo = "insert.php";
      else
        $insertGoTo = "index.php";
      if (isset($_SERVER['QUERY_STRING'])) {
        $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
        $insertGoTo .= $_SERVER['QUERY_STRING'];
      }
  header(sprintf("Location: %s", $insertGoTo));
}

}

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
        <title>Corner 43 Decor | Administrator</title>
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
        <link rel="icon" href="../../assets/images/favicon.png" type="image/png" />
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
                    <!-- BEGIN PAGE BAR -->
                    
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->

                    <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                      <span class="caption-subject uppercase">Collection Insert</span>
                                    </div>
                                    <div class="actions">
                                        
                                    </div>
                                </div>
                                <div class="portlet-body form">

                                <?php if(isset($uploadMessage1)){?>
                                        <div class="alert alert-block alert-danger fade in">
                                <button type="button" class="close" data-dismiss="alert"></button>
                                <h4 class="alert-heading" style="margin-bottom:0px">Collection Thumbnail <?php echo $uploadMessage1; ?></h4>
                                              </div>
                                <?php }?>
                                    
                                        <div class="form-body" style="padding-bottom: 0px;">
                                        
                                        <form action="<?php echo $editFormAction; ?>" name="collection" id="collection" method="POST" enctype="multipart/form-data">     



                                            <div class="row">
                                              <div class="col-md-12">
                                                <div class="light">
                                <div class="portlet-body">
                                    <ul class="nav nav-tabs">
                                        <li class="<?php if(!isset($_POST["current_tab"]) || $_POST["current_tab"] == "tab_1_1"){echo 'active';}?>">
                                            <a onclick="setDefaultTab('tab_1_1');" href="#tab_1_1" data-toggle="tab" aria-expanded="true"> Main</a>
                                        </li>
                                        <?php 
                                        mysql_data_seek($rs_lang, 0);
                                        while ($row_rs_lang = mysql_fetch_assoc($rs_lang)){ ?>

                                        <li class="<?php if(isset($_POST["current_tab"]) && $_POST["current_tab"] == "tab_".$row_rs_lang['lang_abbr']."_1"){echo 'active';}?>">
                                            <a onclick="setDefaultTab('tab_<?php echo $row_rs_lang['lang_abbr']; ?>_1');" href="#tab_<?php echo $row_rs_lang['lang_abbr']; ?>_1" data-toggle="tab" aria-expanded="true"> <?php echo $row_rs_lang['lang_name']; ?></a>
                                        </li>

                                        <?php } ?>
                                    </ul>
                                    <div class="tab-content">
                                          <div class="tab-pane fade  <?php if(!isset($_POST["current_tab"]) || $_POST["current_tab"] == "tab_1_1"){echo 'active in';}?>" id="tab_1_1">

                                            <div class="form-group">
                                            <label>Meta Title</label>
                                            <input type="text" name="meta_title" id="meta_title" class="form-control" placeholder="" > 
                                          </div>
                                                
                                          <div class="form-group">
                                            <label>Meta Keywords</label>
                                            <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" placeholder="" > 
                                          </div>
                                                
                                          <div class="form-group">
                                            <label>Meta Description</label>
                                            <input type="text" name="meta_description" id="meta_description" class="form-control" placeholder="" > 
                                          </div>
                                      
                                          <div class="row">
                                            <div class="col-md-6">
                                              <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <label>Collection Thumbnail</label> <span style="font-size:11px; color:#F00;">Size : 600 x 600 px<span>
                                                  <div class="input-group">
                                                    <div class="form-control uneditable-input span3" data-trigger="fileinput">
                                                      <i class="fa fa-file fileinput-exists"></i><span class="fileinput-filename">
                                                      </span>
                                                    </div>
                                                    <span class="input-group-addon btn default btn-file">
                                                    <span class="fileinput-new">
                                                    Select file </span>
                                                    <span class="fileinput-exists">
                                                    Change </span>
                                                    <input type="file" name="image1" id="image1" onchange="imageSelectHandler(this);" data-width="600" data-height="600">
                                                    </span>
                                                    <a href="#" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput">
                                                    Remove </a>
                                                  </div>
                                              </div>
                                            </div>

                                            <div class="col-md-6">
                                              <div class="form-group">                                                
                                                <label>Alternative Text Collection Thumbnail</label>
                                                <input type="text" name="alt1" id="alt1" class="form-control" placeholder=""> 
                                              </div>
                                            </div>
                                          </div>

                                          <div class="row">
                                            <div class="col-md-6">
                                              <div class="form-group">
                                                  <label>Collection URL</label> <span style="font-size:11px; color:#F00;">all lowercase letter and no blank space</span>
                                                  <input name="meta_slug" type="text" class="form-control" id="meta_slug" onChange="javascript:this.value=this.value.toLowerCase();" value="">
                                              </div>
                                            </div>
                                          </div>

                                          <div class="row">
                                            <div class="col-md-2">
                                              <div class="form-group">
                                                  <label>Status</label>
                                                  <div class="bootstrap-switch-container">
                                                  <input type="checkbox" name="status" id="status" checked class="make-switch" data-size="normal" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>">
                                                  </div>
                                              </div>
                                            </div> 

                                          </div>                          

                                      </div> 
                                        <?php mysql_data_seek($rs_lang, 0);
                                        while ($row_rs_lang = mysql_fetch_assoc($rs_lang)){ ?>
                                        <div class="tab-pane fade <?php if(isset($_POST["current_tab"]) && $_POST["current_tab"] == "tab_".$row_rs_lang['lang_abbr']."_1"){echo 'active in';}?>" id="tab_<?php echo $row_rs_lang['lang_abbr']; ?>_1">

                                            <div class="form-group">
                                                <label>Collection Title</label>
                                                <input type="text" name="title1_<?php echo strtolower($row_rs_lang['lang_abbr']); ?>" id="title1_<?php echo strtolower($row_rs_lang['lang_abbr']); ?>" class="form-control" placeholder=""> 
                                            </div>

                                            <div class="form-group">
                                                <label>By Collection Title</label>
                                                <input type="text" name="title2_<?php echo strtolower($row_rs_lang['lang_abbr']); ?>" id="title2_<?php echo strtolower($row_rs_lang['lang_abbr']); ?>" class="form-control" placeholder=""> 
                                            </div> 

                                            <div class="form-group">
                                                <label>Collection Detail</label>
                                                <textarea class="form-control ckeditor" name="detail1_<?php echo strtolower($row_rs_lang['lang_abbr']); ?>" id="detail1_<?php echo strtolower($row_rs_lang['lang_abbr']); ?>" cols="45" rows="5"></textarea>
                                            </div> 

                                        </div>

                                        </div>

                                      <?php } ?>
                                        </div>
                                    </div>
                                </div>
                               <div class="clearfix margin-bottom-20"> </div>
                                        <div class="form-actions right">
                                          <a href="index.php" class="btn dark btn-outline">Back</a>
                                          <button type="submit" name="Insert" class="btn blue btn-outline">Insert</button>
                                          <a href="#" class="btn btn-outline yellow" onclick="javascript:insert_more(this);">Insert More</a>
                                        </div>
                                        <input type="hidden" name="MM_insert" value="collection">
                                        </form>

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
        <!-- END THEME LAYOUT SCRIPTS -->
        <script src="../assets/global/plugins/jcrop/js/jquery.Jcrop.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="../assets/pages/scripts/form-image-crop.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <script>
            $("#meta_slug").on('blur',checkSlug);
            $("#collection").on('submit',checkSlug);
            function checkSlug(evnt){
              if($('#meta_slug').prop('value')===''){
                if(evnt.type=='submit')
                  alert('URL can not empty');
                return false;
              }
              var resultCheck = $.ajax({
                type: 'POST',
                url: '../checkSlug.php',
                data: {tableName:'collection',dataValue:$('#meta_slug').prop('value'),cID:'0'},
                success: function( data ) {
                if(data=='CanUse'){
                  $("#meta_slug").parent().find('span').css({'font-size':'13px','color':'greenyellow'}).text('available');
                }else{
                  $("#meta_slug").parent().find('span').css({'font-size':'13px','color':'red'}).text('not avilable');
                }
                return data;
                },
                async:false
              });
              if(resultCheck.readyState===4){
                if(resultCheck.responseText=="CanUse"){
                  return true;
                }
                if(evnt.type=='submit'){
                  alert('URL not avilable');
                  $('#meta_slug').focus();
                }
                return false;
              }else{
                alert('Error Slug Checker');
                return false;
              }
            }

        </script>
    </body>

</html>
<?php
mysql_free_result($rs_insert);

mysql_free_result($rs_collection);
?>